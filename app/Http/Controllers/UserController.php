<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function show($id){
        return User::select('id','name','profile','biography','created_at')->where('id',$id)->first();
    }

}
