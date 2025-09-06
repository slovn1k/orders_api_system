<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return 'Hello World';
    }

    public function show(Request $request)
    {
    }

    public function store(Request $request)
    {
    }

    public function update(Request $request)
    {
    }
}
