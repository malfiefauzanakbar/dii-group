<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;
use App\User;
use App\Home;
use Carbon\Carbon;
use Config;

class HomeController extends Controller
{
    public function index(Request $request)
    {                

        $homes = Home::get();
        $serHomes = $this->serializeHome($homes, 'object');
        if ($serHomes) {
            return response([
                'success'   => true,
                'message'   => 'List Home',
                'data'      => $serHomes
            ], 200);
        }else{
            return response([
                'success'   => true,
                'message'   => 'Data Not Found!',
                'data'      => (object)array()
            ], 200);
        }
    }

    public function store(Request $request)
    {        
        $token = $request->header('token');        
        $checkToken = AppHelper::checkToken($token);
        if ($checkToken == 'true'){
            return response()->json(['success' => false,'message' => 'Token Expired!',], 400);
        }

        
        $home = Home::limit(1)->first();
        if ($home){
            //validate data
            $validator = Validator::make($request->all(), [            
                'title'      => 'required',
                'description'      => 'required',
                'company_overview'      => 'required',         
            ],
                [                
                    'title'      => 'Title Is Required!',                
                    'description'      => 'Description Is Required!',                
                    'company_overview'      => 'Company Overview Is Required!',
                ]
            );

            if($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Please Fill The Required Fields!',
                    'data'    => $validator->errors()
                ],400);

            }else{

                //upload image                 
                $uimage;

                if($request->file('image') != '') {
                    $timenow = Carbon::now();
                    $convtime = Carbon::createFromFormat('Y-m-d H:i:s', $timenow)->format('YmdHis');            
                    $extension = $request->image->extension();          
                    $imageName = $convtime.".".$extension;                                
                    $uimage = $imageName;
                    Storage::disk('local')->delete('public/home/'.$home->image);                
                    $request->image->storeAs('public/home/', $imageName);
                }else{                                
                    $uimage = $home->image;           
                }
                $home = $home->update([                                
                    'title'      => $request->input('title'),
                    'description'      => $request->input('description'),
                    'company_overview'      => $request->input('company_overview'),
                    'image'     => $uimage,
                ]);                    
    
                if ($home) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Success Update Data!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed Update Data!',
                    ], 500);
                }
            }                
        }else{
            //validate data
            $validator = Validator::make($request->all(), [            
                'title'      => 'required',
                'description'      => 'required',
                'company_overview'      => 'required',      
                'image'     => 'required|image|mimes:jpeg,jpg,png|file|max:128000',      
            ],
                [                
                    'title'      => 'Title Is Required!',                
                    'description'      => 'Description Is Required!',                
                    'company_overview'      => 'Company Overview Is Required!',
                    'image'     => 'Image Is Required!',
                ]
            );

            if($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Please Fill The Required Fields!',
                    'data'    => $validator->errors()
                ],400);

            }else{
                //upload image
                $timenow = Carbon::now();
                $convtime = Carbon::createFromFormat('Y-m-d H:i:s', $timenow)->format('YmdHis');            
                $extension = $request->image->extension();          
                $imageName = $convtime.".".$extension;
                $request->image->storeAs('public/home/', $imageName);

                $home = Home::create([                                                
                    'title'      => $request->input('title'),
                    'description'      => $request->input('description'),
                    'company_overview'      => $request->input('company_overview'),                
                    'image'     => $imageName,
                ]);
        
                if ($home) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Success Create Data!',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed Create Data!',
                    ], 400);
                }
            }                
        }
                                                  
        
    }

    public function show($id, Request $request)
    {        

        $home = Home::whereId($id)->get();
        $serHome = $this->serializeHome($home, 'object');
        if ($serHome) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Home!',
                'data'    => $serHome
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Data Not Found!',
                'data'    => (object)array()
            ], 200);
        }
    }

    public function update($id, Request $request)
    {
        $token = $request->header('token');        
        $checkToken = AppHelper::checkToken($token);
        if ($checkToken == 'true'){
            return response()->json(['success' => false,'message' => 'Token Expired!',], 400);
        }

        //validate data
        $validator = Validator::make($request->all(), [            
            'title'      => 'required',
            'description'      => 'required',
            'company_overview'      => 'required',            
        ],
            [                
                'title'      => 'Title Is Required!',                
                'description'      => 'Description Is Required!',                
                'company_overview'      => 'Company Overview Is Required!',
            ]
        );

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Please Fill The Required Fields!',
                'data'    => $validator->errors()
            ],400);

        } else {

            //upload image            
            $home = Home::whereId($id)->first();                                                 

            $home = $home->update([                                
                'title'      => $request->input('title'),
                'description'      => $request->input('description'),
                'company_overview'      => $request->input('company_overview'),
            ]);                    

            if ($home) {
                return response()->json([
                    'success' => true,
                    'message' => 'Success Update Data!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed Update Data!',
                ], 500);
            }

        }

    }

    public function destroy($id, Request $request)
    {
        $token = $request->header('token');        
        $checkToken = AppHelper::checkToken($token);
        if ($checkToken == 'true'){
            return response()->json(['success' => false,'message' => 'Token Expired!',], 400);
        }

        $home = Home::findOrFail($id);       
        $home->delete();        

        if ($home) {            
            return response()->json([
                'success' => true,
                'message' => 'Success Delete Data!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed Delete Data!',
            ], 500);
        }

    }

    public static function serializeHome($homes, $type)
    {
        // error_log($homes);
        $data = array();
        foreach ($homes as $home){            
            $item =  array (
              'id'    => $home->id,              
              'title'    => $home->title,                            
              'description'    => $home->description,
              'company_overview'    => $home->company_overview,
              'image'   => config('environment.app_url')
              .config('environment.dir_home').$home->image,
            );                        

            if ($type == 'array'){
                $data[] = $item;
            }else{
                $data = $item;
            }
        }
        return $data;
    }
}
