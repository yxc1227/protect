<?php

namespace App\Http\Controllers;

use App\Services\BiService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    private $biService;

    public function __construct(BiService $biService)
    {
        $this->biService = $biService;
    }

    public function index(Request $request)
    {
        $this->biService->connectiong2hupun();

        return $this->response(100);
    }
}
