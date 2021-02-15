<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            'explanation'=>'required|string|min:3|max:65000',
            'user_id'=>'required|numeric',
            'discussion_id'=>'nullable|numeric',
            'post_id'=>'nullable|numeric',
        ]);
        
        $exists=Report::all()
        ->where('reporter_id',auth()->user()->id)
        ->where('discussion_id',$request->discussion_id)
        ->where('user_id',$request->user_id)
        ->where('post_id',$request->post_id)
        ->first();
        if($exists!=null)
        return new Response('{"error":"your report has been registered"}',409,$http_response_header=["Content-Type"=>"application/json"]);
        $report=new Report([
            'reporter_id'=>auth()->user()->id 
            , 'explanation'=> $request->explanation
            , 'user_id'=> $request->user_id
            , 'post_id'=> $request->post_id
            , 'discussion_id'=> $request->discussion_id]);
        $report->save();
        return new Response('{"message":"your report was successfully registered"}',201,$http_response_header=["Content-Type"=>"application/json"]);
   
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
