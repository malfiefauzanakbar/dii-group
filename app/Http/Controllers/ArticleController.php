<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;
use App\User;
use App\Article;
use Carbon\Carbon;
use Config;

class ArticleController extends Controller
{
    public function index(Request $request)
    {                

        $articles = Article::get();
        $serArticles = $this->serializeArticle($articles, 'array');
        if ($serArticles) {
            return response([
                'success'   => true,
                'message'   => 'List Article',
                'data'      => $serArticles
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
            'status'      => 'required',            
        ],
            [
                'image'     => 'Image Is Required!',
                'title'      => 'Title Is Required!',                
                'description'      => 'Description Is Required!',
                'status'      => 'Status Is Required!',                
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
            $request->image->storeAs('public/article/', $imageName);
            // $request->image->move(public_path('article/'), $imageName);       

            $article = Article::create([                                
                'image'     => $imageName,
                'title'      => $request->input('title'),
                'description'      => $request->input('description'),                
                'status'      => $request->input('status'),
            ]);

            if ($article) {
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

        $article = Article::whereId($id)->get();
        $serCompany = $this->serializeArticle($article, 'object');
        if ($serCompany) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Article!',
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
            'status'      => 'required',            
        ],
            [
                'title'      => 'Title Is Required!',                
                'description'      => 'Description Is Required!',
                'status'      => 'Status Is Required!',                
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
            $article = Article::whereId($id)->first();            

            $uimage;

            if($request->file('image') != '') {
                $timenow = Carbon::now();
                $convtime = Carbon::createFromFormat('Y-m-d H:i:s', $timenow)->format('YmdHis');            
                $extension = $request->image->extension();          
                $imageName = $convtime.".".$extension;                                
                $uimage = $imageName;
                Storage::disk('local')->delete('public/article/'.$article->image);                
                $request->image->storeAs('public/article/', $imageName);
            }else{                                
                $uimage = $article->image;           
            }                                

            $article = $article->update([                
                'image'     => $uimage,
                'title'      => $request->input('title'),
                'description'      => $request->input('description'),
                'status'      => $request->input('status'),
            ]);                    

            if ($article) {
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

        $article = Article::findOrFail($id);
        Storage::disk('local')->delete('/public/article/'.$article->image);
        $article->delete();        

        if ($article) {            
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

    public static function serializeArticle($articles, $type)
    {
        // error_log($articles);
        $data = array();
        foreach ($articles as $article){            
            $item =  array (
              'id'    => $article->id,
              // 'image'   => storage_path()."/articles/".$article->image,
              'image'   => config('environment.app_url')
              .config('environment.dir_article').$article->image,
              'title'    => $article->title,                            
              'description'    => $article->description,
              'status'    => $article->status,
              'created_at'    => $article->created_at,
              'updated_at'    => $article->updated_at,
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
