<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use Facade\FlareClient\Time\Time;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Converter\TimeConverterInterface;

class DiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->headers!=null)
        {$headers= request()->headers;
        $date=$headers->get('If-Modified-Since');
        $update_time=DB::table('discussions')->latest()->get('updated_at')->first();
        $last_update_time=$update_time->updated_at;
             if(strtotime($date)<strtotime($last_update_time))
             return new Response(Discussion::all(),200,$headers=["Content-Type"=>"application/json"]);
            else
            {
                return new Response(null,304);
            }
            }

        return new Response(Discussion::all(),200,$headers=["Content-Type"=>"application/json"]);
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
        if(auth()->user()==null)
        return new Response('{"error" : "please provide a correct bearer token"}',401,$http_response_header=["Content-Type"=>"application/json"]);
        $request->validate([
            'title'=>'required|min:1|string',
            'url'=>'required|string|url|min:3'
        ]);

        $discussion_data=['title' =>$request->title,
        'url'=> $request->url, 
        'user_id'=>auth()->user()->id
    ];
        $discussion=new Discussion($discussion_data);
        $discussion->save();
        return new Response($discussion,201,$headers=["Content-Type"=>"application/json"]) ;
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //
    }
}
