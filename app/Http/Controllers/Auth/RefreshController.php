<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

class RefreshController extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth:api']);
    }

    public function __invoke()
    {
        $token = auth()->refresh();
        return response()->json(compact('token'));
    }
}
