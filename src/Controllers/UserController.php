<?php

namespace AdminUI\AdminUIUserBase\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use AdminUI\AdminUIAdmin\Models\Country;

class UserController extends Controller
{
    /**
     * Display the user mini app
     *
     * @return [type]
     */
    public function index()
    {
        $country = Country::all();
        return view('content.userbase.index', compact('country'));
    }
}
