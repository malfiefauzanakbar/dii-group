<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;
use App\User;
use App\Company;
use Carbon\Carbon;
use Config;

class CompanyController extends Controller
{
    public function index(Request $request)
    {                

        $companies = Company::limit(1)->get();
        $serCompanies = $this->serializeCompany($companies, 'object');
        if ($serCompanies) {
            return response([
                'success'   => true,
                'message'   => 'List Company',
                'data'      => $serCompanies
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

        $company = Company::limit(1)->first();
        if ($company){
             //validate data
            $validator = Validator::make($request->all(), [
                // 'name'      => 'required',
                // 'field'      => 'required',
                // 'description'      => 'required',
                'address'      => 'required',
                'map_link'      => 'required',            
                'phone_number'      => 'required',
                'mobile_number'      => 'required',
                'email'      => 'required',
                'instagram'    => 'required',
                'facebook'      => 'required',
                'twitter'      => 'required',
                'map_embed'      => 'required',
            ],
                [
                    // 'name'      => 'Name Is Required!',
                    // 'field'      => 'Field Is Required!',
                    // 'description'      => 'Description Is Required!',
                    'address'      => 'Address Is Required!',
                    'map_link'      => 'Map Link Is Required!',            
                    'phone_number'      => 'Phone Number Is Required!',
                    'mobile_number'      => 'Mobile Number Is Required!',
                    'email'      => 'Email Is Required!',
                    'instagram'    => 'Instagram Is Required!',
                    'facebook'      => 'Facebook Is Required!',
                    'twitter'      => 'Twitter Is Required!',
                    'map_embed'      => 'Map Embed Is Required!',
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
                // $ulogo;

                // if($request->file('logo') != '') {
                //     $timenow = Carbon::now();
                //     $convtime = Carbon::createFromFormat('Y-m-d H:i:s', $timenow)->format('YmdHis');            
                //     $extension = $request->logo->extension();          
                //     $logoName = $convtime.".".$extension;                                
                //     $ulogo = $logoName;
                //     Storage::disk('local')->delete('public/company/'.$company->logo);                
                //     $request->logo->storeAs('public/company/', $logoName);
                // }else{                                
                //     $ulogo = $company->logo;           
                // }                                

                $company = $company->update([                
                    'logo'     => '-',
                    'name'      => '-',
                    'field'      => '-',
                    'description'      => '-',
                    'address'      => $request->input('address'),
                    'map_link'      => $request->input('map_link'),            
                    'phone_number'      => $request->input('phone_number'),
                    'mobile_number'      => $request->input('mobile_number'),
                    'email'      => $request->input('email'),
                    'instagram'    => $request->input('instagram'),
                    'facebook'      => $request->input('facebook'),
                    'twitter'      => $request->input('twitter'),
                    'map_embed'      => $request->input('map_embed'),
                ]);                    

                if ($company) {
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
                // 'logo'     => 'required|image|mimes:jpeg,jpg,png|file|max:128000',
                // 'name'      => 'required',
                // 'field'      => 'required',
                // 'description'      => 'required',
                'address'      => 'required',
                'map_link'      => 'required',            
                'phone_number'      => 'required',
                'mobile_number'      => 'required',
                'email'      => 'required',
                'instagram'    => 'required',
                'facebook'      => 'required',
                'twitter'      => 'required',
                'map_embed'      => 'required',
            ],
                [
                    // 'logo'     => 'Logo Is Required!',
                    // 'name'      => 'Name Is Required!',
                    // 'field'      => 'Field Is Required!',
                    // 'description'      => 'Description Is Required!',
                    'address'      => 'Address Is Required!',
                    'map_link'      => 'Map Link Is Required!',            
                    'phone_number'      => 'Phone Number Is Required!',
                    'mobile_number'      => 'Mobile Number Is Required!',
                    'email'      => 'Email Is Required!',
                    'instagram'    => 'Instagram Is Required!',
                    'facebook'      => 'Facebook Is Required!',
                    'twitter'      => 'Twitter Is Required!',
                    'map_embed'      => 'Map Embed Is Required!',
                ]
            );

            if($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Please Fill The Required Fields!',
                    'data'    => $validator->errors()
                ],400);

            } else {

                //upload logo
                // $timenow = Carbon::now();
                // $convtime = Carbon::createFromFormat('Y-m-d H:i:s', $timenow)->format('YmdHis');            
                // $extension = $request->logo->extension();          
                // $logoName = $convtime.".".$extension;
                // $request->logo->storeAs('public/company/', $logoName);
                // $request->logo->move(public_path('company/'), $logoName);       

                $company = Company::create([                                
                    'logo'     => '-',
                    'name'      => '-',
                    'field'      => '-',
                    'description'      => '-',
                    'address'      => $request->input('address'),
                    'map_link'      => $request->input('map_link'),            
                    'phone_number'      => $request->input('phone_number'),
                    'mobile_number'      => $request->input('mobile_number'),
                    'email'      => $request->input('email'),
                    'instagram'    => $request->input('instagram'),
                    'facebook'      => $request->input('facebook'),
                    'twitter'      => $request->input('twitter'),
                    'map_embed'      => $request->input('map_embed'),
                ]);

                if ($company) {
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

        $company = Company::whereId($id)->get();
        $serCompany = $this->serializeCompany($company, 'object');
        if ($serCompany) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Company!',
                'data'    => $serCompany
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
            'name'      => 'required',
            'field'      => 'required',
            'description'      => 'required',
            'address'      => 'required',
            'map_link'      => 'required',            
            'phone_number'      => 'required',
            'mobile_number'      => 'required',
            'email'      => 'required',
            'instagram'    => 'required',
            'facebook'      => 'required',
            'twitter'      => 'required',
            'map_embed'      => 'required',
        ],
            [
                'name'      => 'Name Is Required!',
                'field'      => 'Field Is Required!',
                'description'      => 'Description Is Required!',
                'address'      => 'Address Is Required!',
                'map_link'      => 'Map Link Is Required!',            
                'phone_number'      => 'Phone Number Is Required!',
                'mobile_number'      => 'Mobile Number Is Required!',
                'email'      => 'Email Is Required!',
                'instagram'    => 'Instagram Is Required!',
                'facebook'      => 'Facebook Is Required!',
                'twitter'      => 'Twitter Is Required!',
                'map_embed'      => 'Map Link Is Required!',
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
            $company = Company::whereId($id)->first();            

            $ulogo;

            if($request->file('logo') != '') {
                $timenow = Carbon::now();
                $convtime = Carbon::createFromFormat('Y-m-d H:i:s', $timenow)->format('YmdHis');            
                $extension = $request->logo->extension();          
                $logoName = $convtime.".".$extension;                                
                $ulogo = $logoName;
                Storage::disk('local')->delete('public/company/'.$company->logo);                
                $request->logo->storeAs('public/company/', $logoName);
            }else{                                
                $ulogo = $company->logo;           
            }                                

            $company = $company->update([                
                'logo'     => $ulogo,
                'name'      => $request->input('name'),
                'field'      => $request->input('field'),
                'description'      => $request->input('description'),
                'address'      => $request->input('address'),
                'map_link'      => $request->input('map_link'),            
                'phone_number'      => $request->input('phone_number'),
                'mobile_number'      => $request->input('mobile_number'),
                'email'      => $request->input('email'),
                'instagram'    => $request->input('instagram'),
                'facebook'      => $request->input('facebook'),
                'twitter'      => $request->input('twitter'),
                'map_embed'      => $request->input('map_embed'),
            ]);                    

            if ($company) {
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

        $company = Company::findOrFail($id);
        Storage::disk('local')->delete('/public/company/'.$company->logo);
        $company->delete();        

        if ($company) {            
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

    public static function serializeCompany($companies, $type)
    {
        // error_log($companies);
        $data = array();
        foreach ($companies as $company){            
            $item =  array (
              'id'    => $company->id,
              // 'logo'   => storage_path()."/companies/".$company->logo,
              'logo'   => config('environment.app_url')
              .config('environment.dir_company').$company->logo,
              'name'    => $company->name,                            
              'field'    => $company->field,
              'description'    => $company->description,
              'address'    => $company->address,
              'map_link'    => $company->map_link,
              'phone_number'    => $company->phone_number,
              'mobile_number'    => $company->mobile_number,
              'email'    => $company->email,
              'instagram'    => $company->instagram,
              'facebook'    => $company->facebook,
              'twitter'    => $company->twitter,
              'map_embed'    => $company->map_embed,
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
