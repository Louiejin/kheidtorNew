<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller
{
    public function index() {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        else {
            // role-based home page
            if (auth()->user()->admin) { return redirect()->route('articles'); }
            else if (auth()->user()->publish) { return redirect()->route('articles'); }
            else if (auth()->user()->edit) { return redirect()->route('articles'); }
            else if (auth()->user()->managedb) { return redirect()->route('databases'); }
        }
    }
}
