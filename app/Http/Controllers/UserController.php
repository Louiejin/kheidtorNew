<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\User;
use Illuminate\Validation\Rule;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function __construct() {
    
    }
    
    private static function isAuthorized() {
        if (!auth()->check()) {
            return false;
        }
        elseif(auth()->user()->admin || auth()->user()->managedb) {
            return true;
        }
        else {
            return false;
        }
    }
    
    public function readAll() {
        if (UserController::isAuthorized()){
            $users = User::all()->sortByDesc('id');
            return view('users.list', compact('users'));
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function create() {
        if (UserController::isAuthorized()){
            return view('users.create');
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function edit($id) {
        if ($id == 'me') {
            if (auth()->user()){
                $user = User::find(auth()->user()->id);
                return view('users.edit', compact('user'));
            }
            else {
                return view('unauthorized');
            }
        }
        else {
            if (UserController::isAuthorized()){
                $user = User::find($id);
                return view('users.edit', compact('user'));
            }
            else {
                return view('unauthorized');
            }
        }
    }
    
    public function store() {
        if (UserController::isAuthorized()){
            // form validation
            $this->validate(request(), [
                'fullname' => 'required',
                'email' => 'required|unique:users',
                'username' => 'required|unique:users|min:4',
                'password' => 'required|min:8',
            ]);
            
            $user = new User;
            $user->fullname = request('fullname');
            $user->email = request('email');
            $user->username = request('username');
            $user->password = bcrypt(request('password'));
            $user->created_by = auth()->user()->id;
            $user->updated_by = auth()->user()->id;
            $user->save();
            
            $checkboxes = ['admin', 'publish', 'edit', 'managedb'];
            
            Log::debug(request('admin'));
            
            foreach($checkboxes as $cb) {
                if (request($cb) == True) {
                    $user->addRole($cb);
                }
            }
            
            request()->session()->flash('success', 'User successfully added!');
            return redirect()->route('users');
        }
        else {
            return view('unauthorized');
        }
        
    }
    
    public function update(User $user) {
        // user is authorized or it is the current user
        if (UserController::isAuthorized() || $user->id == auth()->user()->id) {
            // form validation
            $this->validate(request(), [
                    'fullname' => 'required',
                    'email' => ['required', Rule::unique('users')->ignore($user->id)],
                    'username' => ['required', 'min:4', Rule::unique('users')->ignore($user->id)],
            ]);
            
            // save
            $user->fullname = request('fullname');
            $user->email = request('email');
            $user->username = request('username');
            $user->updated_by = auth()->user()->id;
            $user->save();
            if (UserController::isAuthorized()) {
                // clear roles
                $user->roles()->delete();
                
                $checkboxes = ['admin', 'publish', 'edit', 'managedb'];
                
                foreach($checkboxes as $cb) {
                    if (request($cb) == True) {
                        $user->addRole($cb);
                    }
                }
            }
            
            request()->session()->flash('success', 'Edit Successful!');
            return redirect()->back();
        }
        else {
            return view('unauthorized');
        }
        
    }
        
    public function updatePassword(User $user) {
        // user is authorized or it is the current user
        if (UserController::isAuthorized() || $user->id == auth()->user()->id) {
            // form validation
            $this->validate(request(), [
                    'password' => 'required|min:8',
            ]);
            
            $user->password = bcrypt(request('password'));
            $user->updated_by = auth()->user()->id;
            $user->save();
            
            request()->session()->flash('success', 'Password updated!');
            return redirect()->back();
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function updateWordpress(User $user) {
        if (UserController::isAuthorized() || $user->id == auth()->user()->id) {
            // validation
            $this->validate(request(), [
                    'wp_username' => 'required',
                    'wp_password' => 'required',
            ]);
            
            // check if can get a valid token
            $client = new Client([
                'verify' => false
            ]);
            $params = [
                'username' => request('wp_username'),
                'password' => request('wp_password')
            ];
            $res = $client->request('POST', 'https://www.kanjihybrid.com/wp-json/jwt-auth/v1/token', ['http_errors' => false, 'json' => $params]);
            Log::debug($res->getBody());
            $res = json_decode($res->getBody());
            if (isset($res->token)) {
                // store the credentials
                $user->wp_password = Crypt::encryptString(request('wp_password'));
                $user->wp_username = request('wp_username');
                $user->wp_token = $res->token;
                $user->save();
                request()->session()->flash('success', 'Wordpress credentials successfully saved');
                return redirect()->back();
            }
            else {
                request()->session()->flash('m_errors', ['Invalid Wordpress username/password']);
                return redirect()->back();
            }
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function unlinkWordpress(User $user) {
        if (UserController::isAuthorized() || $user->id == auth()->user()->id) {
            $user->wp_username = null;
            $user->wp_password = null;
            $user->wp_token = null;
            $user->save();
            request()->session()->flash('success', 'Wordpress credentials successfully unlinked');
            return redirect()->back();
        }
        else {
            return view('unauthorized');
        }
        
    }
    
    public function deactivate(User $user) {
        $user->enabled = 0;
        $user->save();
        request()->session()->flash('success', 'User successfully deactivated!');
        return redirect()->route('users');
        
    }

    public function activate(User $user) {
        $user->enabled = 1;
        $user->save();
        request()->session()->flash('success', 'User successfully activated!');
        return redirect()->route('users');
    
    }
    
}
