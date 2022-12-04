<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;
use App\User;
use App\AboutUs;
use Carbon\Carbon;
use Config;

class AboutUsController extends Controller
{
    public function index(Request $request)
    {                

        $aboutuss = AboutUs::limit(1)->get();
        $serAboutUss = $this->serializeAboutUs($aboutuss, 'object');
        if ($serAboutUss) {
            return response([
                'success'   => true,
                'message'   => 'List About Us',
                'data'      => $serAboutUss
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

        //validate data
        $validator = Validator::make($request->all(), [            
            'title'      => 'required',
            'description'      => 'required',
            'who_we_are'      => 'required',
            'vision'      => 'required',            
            'mission'      => 'required',
            'business_field'      => 'required',
            'owner_profile'      => 'required',
            'coorporate_values'    => 'required',
        ],
            [                
                'title'      => 'Title Is Required!',
                'description'      => 'Description Is Required!',
                'who_we_are'      => 'Who We Are Is Required!',
                'vision'      => 'Vision Is Required!',            
                'mission'      => 'Mission Is Required!',
                'business_field'      => 'Business Field Is Required!',
                'owner_profile'      => 'Owner Profile Is Required!',
                'coorporate_values'    => 'Coorporate Values Is Required!',
            ]
        );

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Please Fill The Required Fields!',
                'data'    => $validator->errors()
            ],400);

        } else {
            $aboutus = AboutUs::limit(1)->first();
            if ($aboutus){                                                                 
                
                    $aboutus = $aboutus->update([                
                        'title'      => $request->input('title'),
                        'description'      => $request->input('description'),
                        'who_we_are'      => $request->input('who_we_are'),
                        'vision'      => $request->input('vision'),
                        'mission'      => $request->input('mission'),
                        'business_field'      => $request->input('business_field'),
                        'owner_profile'      => $request->input('owner_profile'),
                        'coorporate_values'    => $request->input('coorporate_values'),
                    ]);                    

                    if ($aboutus) {
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
            }else{             
                
                $aboutus = AboutUs::create([
                    'title'      => $request->input('title'),
                    'description'      => $request->input('description'),
                    'who_we_are'      => $request->input('who_we_are'),
                    'vision'      => $request->input('vision'),
                    'mission'      => $request->input('mission'),
                    'business_field'      => $request->input('business_field'),
                    'owner_profile'      => $request->input('owner_profile'),
                    'coorporate_values'    => $request->input('coorporate_values'),
                ]);

                if ($aboutus) {
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
        $token = $request->header('token');        
        $checkToken = AppHelper::checkToken($token);
        if ($checkToken == 'true'){
            return response()->json(['success' => false,'message' => 'Token Expired!',], 400);
        }

        $aboutus = AboutUs::whereId($id)->get();
        $serAboutUs = $this->serializeAboutUs($aboutus, 'object');
        if ($serAboutUs) {
            return response()->json([
                'success' => true,
                'message' => 'Detail About Us!',
                'data'    => $serAboutUs
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
            'who_we_are'      => 'required',
            'vision'      => 'required',            
            'mission'      => 'required',
            'business_field'      => 'required',
            'owner_profile'      => 'required',
            'coorporate_values'    => 'required',
        ],
            [                
                'title'      => 'Title Is Required!',
                'description'      => 'Description Is Required!',
                'who_we_are'      => 'Who We Are Is Required!',
                'vision'      => 'Vision Is Required!',            
                'mission'      => 'Mission Is Required!',
                'business_field'      => 'Business Field Is Required!',
                'owner_profile'      => 'Owner Profile Is Required!',
                'coorporate_values'    => 'Coorporate Values Is Required!',
            ]
        );

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Please Fill The Required Fields!',
                'data'    => $validator->errors()
            ],400);

        } else {
            
            $aboutus = AboutUs::whereId($id)->first();                                            

            $aboutus = $aboutus->update([                
                'title'      => $request->input('title'),
                'description'      => $request->input('description'),
                'who_we_are'      => $request->input('who_we_are'),
                'vision'      => $request->input('vision'),
                'mission'      => $request->input('mission'),
                'business_field'      => $request->input('business_field'),
                'owner_profile'      => $request->input('owner_profile'),
                'coorporate_values'    => $request->input('coorporate_values'),
            ]);                    

            if ($aboutus) {
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

        $aboutus = AboutUs::findOrFail($id);        
        $aboutus->delete();        

        if ($aboutus) {            
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

    public static function serializeAboutUs($aboutuss, $type)
    {
        // error_log($aboutuss);
        $data = array();
        foreach ($aboutuss as $aboutus){            
            $item =  array (
            'id'    => $aboutus->id,              
            'title'      => $aboutus->title,
            'description'      => $aboutus->description,
            'who_we_are'      => $aboutus->who_we_are,
            'vision'      => $aboutus->vision,
            'mission'      => $aboutus->mission,
            'business_field'      => $aboutus->business_field,
            'owner_profile'      => $aboutus->owner_profile,
            'coorporate_values'    => $aboutus->coorporate_values,
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
