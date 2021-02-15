<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Category::all());
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
            'title'=>'required|min:3|string',
            'persian_title'=>'nullable|string|min:3'
        ]);

        $category_data=['title' =>$request->title,
        'persian_title'=> $request->persian_title
    ];
        $category=new Category($category_data);
        $category->save();
        return new Response($category,201,$headers=["Content-Type"=>"application/json"]) ;
        //
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return response()->json(Category::all()->where('id',$id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category==null)
        return new Response('{"error" : "please provide a valid discussion_id"}',400,$http_response_header=["Content-Type"=>"application/json"]);
        if(auth()->user()==null)
        return new Response('{"error" : "please provide a correct bearer token"}',401,$http_response_header=["Content-Type"=>"application/json"]);
       $user_id=auth()->user()->id;
       $category->delete(); 
       return new Response('{"message" : "category successfully deleted"}',200,$http_response_header=["Content-Type"=>"application/json"]);


        //
    }
}
