<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;
use App\ContactUs;
use Carbon\Carbon;
use Config;

class ContactUsController extends Controller
{

    public function index(Request $request)
    {                

        $contactuss = ContactUs::get();
        $serContactUss = $this->serializeContactUs($contactuss, 'array');
        if ($serContactUss) {
            return response([
                'success'   => true,
                'message'   => 'List Contact Us',
                'data'      => $serContactUss
            ], 200);
        }else{
            return response([
                'success'   => true,
                'message'   => 'Data Not Found!',
                'data'      => []
            ], 200);
        }
    }

    public function store(Request $request)
    {                

        //validate data
        $validator = Validator::make($request->all(), [            
            'name'      => 'required',
            'mobile_phone'      => 'required',
            'email'      => 'required',
            'type'      => 'required',            
            'message'      => 'required',
        ],
            [                
                'name'      => 'Name Is Required!',
                'mobile_phone'      => 'Mobile Phone Is Required!',
                'email'      => 'Email Is Required!',
                'type'      => 'Type Is Required!',            
                'message'      => 'Message Is Required!',
            ]
        );

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Please Fill The Required Fields!',
                'data'    => $validator->errors()
            ],400);

        } else {                  
                
            $contactus = ContactUs::create([                
                'name'      => $request->input('name'),
                'mobile_phone'      => $request->input('mobile_phone'),
                'email'      => $request->input('email'),
                'type'      => $request->input('type'),            
                'message'      => $request->input('message'),
            ]);

            if ($contactus) {
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

    public static function serializeContactUs($contactuss, $type)
    {
        // error_log($contactuss);
        $data = array();
        foreach ($contactuss as $contactus){            
            $item =  array (
            'id'    => $contactus->id,              
            'name'      => $contactus->name,
            'mobile_phone'      => $contactus->mobile_phone,
            'email'      => $contactus->email,
            'type'      => $contactus->type,
            'message'      => $contactus->message,
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
