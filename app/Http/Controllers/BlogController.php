<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;
use App\User;
use App\Blog;
use Carbon\Carbon;
use Config;

class BlogController extends Controller
{
    public function index(Request $request)
    {                

        $blogs = Blog::get();
        $serBlogs = $this->serializeBlog($blogs, 'array');
        if ($serBlogs) {
            return response([
                'success'   => true,
                'message'   => 'List Blog',
                'data'      => $serBlogs
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
        $token = $request->header('token');        
        $checkToken = AppHelper::checkToken($token);
        if ($checkToken == 'true'){
            return response()->json(['success' => false,'message' => 'Token Expired!',], 400);
        }

        //validate data
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,jpg,png|file|max:128000',
            'title'      => 'required',
            'description'      => 'required',            
        ],
            [
                'image'     => 'Image Is Required!',
                'title'      => 'Title Is Required!',                
                'description'      => 'Description Is Required!',                
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
            $timenow = Carbon::now();
            $convtime = Carbon::createFromFormat('Y-m-d H:i:s', $timenow)->format('YmdHis');            
            $extension = $request->image->extension();          
            $imageName = $convtime.".".$extension;
            $request->image->storeAs('public/blog/', $imageName);
            // $request->image->move(public_path('blog/'), $imageName);       

            $blog = Blog::create([                                
                'image'     => $imageName,
                'title'      => $request->input('title'),
                'description'      => $request->input('description'),                
            ]);

            if ($blog) {
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

    public function show($id, Request $request)
    {
        $token = $request->header('token');        
        $checkToken = AppHelper::checkToken($token);
        if ($checkToken == 'true'){
            return response()->json(['success' => false,'message' => 'Token Expired!',], 400);
        }

        $blog = Blog::whereId($id)->get();
        $serCompany = $this->serializeBlog($blog, 'object');
        if ($serCompany) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Blog!',
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
            'title'      => 'required',
            'description'      => 'required',
        ],
            [
                'title'      => 'Title Is Required!',
                'description'      => 'Description Is Required!',                
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
            $blog = Blog::whereId($id)->first();            

            $uimage;

            if($request->file('image') != '') {
                $timenow = Carbon::now();
                $convtime = Carbon::createFromFormat('Y-m-d H:i:s', $timenow)->format('YmdHis');            
                $extension = $request->image->extension();          
                $imageName = $convtime.".".$extension;                                
                $uimage = $imageName;
                Storage::disk('local')->delete('public/blog/'.$blog->image);                
                $request->image->storeAs('public/blog/', $imageName);
            }else{                                
                $uimage = $blog->image;           
            }                                

            $blog = $blog->update([                
                'image'     => $uimage,
                'title'      => $request->input('title'),
                'description'      => $request->input('description'),
            ]);                    

            if ($blog) {
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

        $blog = Blog::findOrFail($id);
        Storage::disk('local')->delete('/public/blog/'.$blog->image);
        $blog->delete();        

        if ($blog) {            
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

    public static function serializeBlog($blogs, $type)
    {
        // error_log($blogs);
        $data = array();
        foreach ($blogs as $blog){            
            $item =  array (
              'id'    => $blog->id,
              // 'image'   => storage_path()."/blogs/".$blog->image,
              'image'   => config('environment.app_url')
              .config('environment.dir_blog').$blog->image,
              'title'    => $blog->title,                            
              'description'    => $blog->description,
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
