<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;

class BaseController extends Controller
{
    protected function responseData($data, ?int $code = 200): JsonResponse
    {
        return response()->json($data, $code);
    }
}