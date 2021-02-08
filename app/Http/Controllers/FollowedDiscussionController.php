<?php

namespace App\Http\Controllers;

use App\Models\FollowedDiscussion;
use App\Models\PostsFollowedDiscussion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FollowedDiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()==null)
        return new Response('{"error" : "please provide a correct bearer token"}',401,$http_response_header=["Content-Type"=>"application/json"]);
        if(request()->headers!=null)
        {$headers= request()->headers;
        $date=$headers->get('If-Modified-Since');
        $update_time=DB::table('discussions')->latest()->get('updated_at')->first();
        $last_update_time=$update_time->updated_at;
             if(strtotime($date)<strtotime($last_update_time))
             return new Response(PostsFollowedDiscussion::all()->where('user_id',auth()->user()->id),200,$headers=["Content-Type"=>"application/json"]);
            else
            {
                return new Response(null,304);
            }
            }
            return new Response(PostsFollowedDiscussion::all()->where('user_id',auth()->user()->id),200,$headers=["Content-Type"=>"application/json"]);

        //
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
        return new Response('{"error" : "please provide a correct bearer token"}',401,$http_response_header=["Content-Type"=>"application/json"]);
        if($request->discussion_id==null)
        return new Response('{"error" : "please provide a discussion id"}',401,$http_response_header=["Content-Type"=>"application/json"]);
        $followedDiscussion=new FollowedDiscussion(['user_id'=>auth()->user()->id , 'discussion_id'=> $request->discussion_id]);
        $followedDiscussion->save();
        return new Response($followedDiscussion,201,$http_response_header=["Content-Type"=>"application/json"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FollowedDiscussion  $followedDiscussion
     * @return \Illuminate\Http\Response
     */
    public function show(FollowedDiscussion $followedDiscussion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FollowedDiscussion  $followedDiscussion
     * @return \Illuminate\Http\Response
     */
    public function edit(FollowedDiscussion $followedDiscussion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FollowedDiscussion  $followedDiscussion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FollowedDiscussion $followedDiscussion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FollowedDiscussion  $followedDiscussion
     * @return \Illuminate\Http\Response
     */
    public function destroy($discussion_id)
    {
         
        if($discussion_id==null)
        return new Response('{"error" : "please provide a valid discussion_id"}',400,$http_response_header=["Content-Type"=>"application/json"]);

    $validator= Validator::make(
    array('discussion_id' => $discussion_id),
    array('discussion_id' => array('required', 'string', 'numeric')));
    if ($validator->fails())
    {
        // The given data did not pass validation
        return new Response('{"error" : "please provide a valid discussion_id"}',400,$http_response_header=["Content-Type"=>"application/json"]);
    }
        if(auth()->user()==null)
        return new Response('{"error" : "please provide a correct bearer token"}',401,$http_response_header=["Content-Type"=>"application/json"]);
       $user_id=auth()->user()->id;
        FollowedDiscussion::where('user_id',$user_id)->where('discussion_id',$discussion_id)->delete();
        return new Response('{"message" : "discussion successfully removed from your followed discussions"}',200,$http_response_header=["Content-Type"=>"application/json"]);

    }
}
