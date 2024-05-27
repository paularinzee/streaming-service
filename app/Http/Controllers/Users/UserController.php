<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Models\Following\Following;
use Auth;

class UserController extends Controller
{
    public function followedShows(){


        $followedShows = Following::select()->orderBy('id', 'dasc')->get()
        ->where('user_id', Auth::user()->id)->get();

        return view('users.followed-shows', compact('followedShows'));

    }
}
