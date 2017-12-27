<?php

namespace App\Http\Controllers\Exchange;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DecentralizedController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }
    public function index() {
        return view('decentralized.index');
    }
}
