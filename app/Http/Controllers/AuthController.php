<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    
    public function showform() {
        if (auth()->user()){
            return redirect()->route('home');
        }
        else {
            return view('login');
        }
    }
    
    public function login() {
        // perform login authentication
        
        if (! auth()->attempt(request(['username', 'password']))) {
            request()->session()->flash('m_errors', ['Invalid username/password']);
            return redirect()->route('login');
        }
        if (!auth()->user()->enabled) {
            request()->session()->flash('m_errors', ['User `'. auth()->user()->username .'` is deactivated']);
            auth()->logout();
            return redirect()->route('login');
        }
        
        return redirect()->route('home');
    }
    
    public function logout() {
        auth()->logout();
        return redirect()->route('login');
    }
    
    public function me() {
        dd(auth()->user()->admin);
        //dd(\App\User::me()->roles);
    }
    
    public function unauthorized() {
        return view('unauthorized');
    }
    
}
