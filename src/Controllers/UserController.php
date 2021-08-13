<?php

namespace AdminUI\AdminUIUserBase\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use AdminUI\AdminUIAdmin\Models\Country;

class UserController extends Controller
{
    public function index()
    {
        $country = Country::all();
        return view('content.userbase.index', compact('country'));
    }
}
