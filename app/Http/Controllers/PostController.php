<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Image;

class PostController extends Controller
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
            'file'=>'file|nullable|max:20480',
            'discussion_id'=>'required|numeric',
        ]);
        $user_id=$user->id;
            


        if($request->file!=null) {
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
                    if($request->text!=null){
                        $post =new Post(['file'=>$file_path,'text'=>$request->text,'discussion_id'=>$request->discussion_id,'user_id'=>$user_id]);
                        $post->save();
                    }
                    else{
                        $post =new Post(['file'=>$file_path,'discussion_id'=>$request->discussion_id,'user_id'=>$user_id]);
                        $post->save();
                    }
    
        }
else {
    $request->validate([
        'text'=>'string|required',
    ]);

    $post =new Post(['text'=>$request->text,'discussion_id'=>$request->discussion_id,'user_id'=>$user_id]);
    $post->save();
    
    return new Response($post,201,$headers=["Content-Type"=>"application/json"]) ;

}
        }
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($discussion_id)
    {
        //
        /*
        if(request()->headers!=null)
        {$headers= request()->headers;
        $date=$headers->get('If-Modified-Since');
        $update_time=DB::table('discussions')->latest()->get('updated_at')->first();
        $last_update_time=$update_time->updated_at;
             if(strtotime($date)<strtotime($last_update_time))
             return new Response(Post::all()->where('discussion_id',$discussion_id),200,$headers=["Content-Type"=>"application/json"]);
            else
            {
                return new Response(null,304);
            }
            }
*/
            $result = DB::table('posts')->where('discussion_id',$discussion_id)->get();
            //$result = $result->toArray();
        return new Response($result ,200,$headers=["Content-Type"=>"application/json"]);
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){

        if(auth()->user()==null)
        return new Response('{"message":"please provide correct Bearer Token."}',401,$http_response_header=['Content-Type'=>'application/json']);

        $request->validate([
            'post_id'=>'required|numeric',
            'discussion_id'=>'required|numeric',
            'text'=>'string|required'
        ]);
        $post=Post::find($request->post_id);
        if($post->user_id==auth()->user()->id)
        {
            $post->text=$request->text;
            $post->save();
        return new Response('{"message" : "successfully edited"}',200,$headers=["Content-Type"=>"application/json"]);
        }
        else {
        return new Response('{"error" : "you are unauthorized to update this post"}',401,$headers=["Content-Type"=>"application/json"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($post_id)
    {
        if(auth()->user()==null)
        return new Response('{"message":"please provide correct Bearer Token."}',401,$http_response_header=['Content-Type'=>'application/json']);

        $post=Post::find($post_id);
        if($post->user_id==auth()->user()->id)
        {
            DB::table('posts')->delete($post_id);
        return new Response('{"message" : "successfully deleted"}',200,$headers=["Content-Type"=>"application/json"]);
        }
        else {
        return new Response('{"error" : "you are unauthorized to update this post"}',401,$headers=["Content-Type"=>"application/json"]);
        }
        //
    }
}
