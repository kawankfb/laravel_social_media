<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Image;
class FilesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(auth()->user()==null)
        return new Response('{"message":"please provide correct Bearer Token."}',401,$http_response_header=['Content-Type'=>'application/json']);
        else
        {
        $user= User::find(auth()->user()->id);

        $request->validate([
            'file'=>'file|required|max:100000',
            'setUserProfile'=>'string|required|max:5|min:4',
            'setDiscussionProfile'=>'string|required|max:5|min:4'
        ]);
            if($request->setUserProfile=="true")
            $isProfilePic=true;
            else if($request->setUserProfile=="false")
            $isProfilePic=false;
            else return new Response('{"error" : "please provide a valid isUserProfile argument"}',400,$http_response_header=['Content-Type'=>'application/json']);
        $user_id=$user->id;
        $pathToStoreFile='/files/'.$user_id;
        
        if(!Storage::exists($pathToStoreFile))
        Storage::disk('local')->makeDirectory("/files".'/'.$user_id);

                $extension=$request->file('file')->getClientOriginalExtension();
                $file_name=$request->file('file')->getClientOriginalName();
                $file_name=substr($file_name,0,(strlen($file_name)-strlen($extension))-1);
                $file_path= $request->file('file')->store($pathToStoreFile);
                $size= Storage::size($file_path);
                $stored_file=new Files(['name'=>$file_name,'extension'=>$extension,'size'=>$size,'type'=>'other','path'=>$file_path,'user_id'=>$user_id]);
                $stored_file->save();
                if($isProfilePic)
                {
                    $img= Image::make($request->file('file'));
                    $img->resize(null,200 ,function ($constraint) {
                        $constraint->aspectRatio();
                    })->stream('jpg', 100);
                    Storage::put($pathToStoreFile.'/'.'profile.jpg', $img);
                    DB::table('users')
                    ->where('id', $user_id)
                    ->update(['profile' => 'profile_picture/'.$user_id]);
                }
                //return $stored_file;
            return new Response('{"message":"Successfully created "}',201,$http_response_header=['Content-Type'=>'application/json']);

    }
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Files  $files
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file= Files::find($id);
        $path=$file->path;
        //
        $exists = Storage::exists($path);
   if($exists) {
      
    //get content of image
    $content = Storage::get($path);
    
    //get mime type of image
    $mime = Storage::mimeType($path);
    //prepare response with image content and response code
    return new Response($content, 200,$http_response_header=['Content-Type'=>$mime]);
 } else {
    abort(404);
 }
        return Storage::get($path);
 
    }

    public function profile_picture($id)
    {
        $path='files/'.$id.'/profile.jpg';
        //
        $exists = Storage::exists($path);
   if($exists) {
      
    //get content of image
    $content = Storage::get($path);
    
    //get mime type of image
    $mime = Storage::mimeType($path);
    //prepare response with image content and response code
    return new Response($content, 200,$http_response_header=['Content-Type'=>$mime]);
 } else {
    abort(404);
 }
        return Storage::get($path);
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Files  $files
     * @return \Illuminate\Http\Response
     */
    public function destroy(Files $files)
    {
        //
    }
}
