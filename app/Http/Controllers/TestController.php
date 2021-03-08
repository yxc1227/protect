<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class TestController extends Controller
{

    public function __construct()
    {
    }

    public function index(Request $request)
    {
        return $this->response(100);
    }
}
