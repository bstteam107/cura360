<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
class CategoryController extends Controller
{
    public function getDetail(Request $request)
    {
     echo   $request->get('hello');

    }
}
