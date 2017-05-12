<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
class User extends Authenticatable
{
    use Notifiable;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    
    private $ROLE_STRING = [
            'admin' => 'KH Administrator',
            'publish' => 'Publish Articles',
            'edit' => 'Edit Articles',
            'managedb' => 'Manage DB'
    ];
    
    protected $fillable = [
        'username', 'name', 'email', 'password', 'wp_username', 'wp_password', 'wp_token'
    ];

    protected $hidden = [
        'password'
    ];
    
    public static function me() {
        return User::find(auth()->user()->id);
        
    }
    
    public function roles() {
        return $this->hasMany('\App\Role');
    }
    
    public function updater() {
        return $this->belongsTo('\App\User', 'updated_by', 'id');
    }
    
    public function creator() {
        return $this->belongsTo('\App\User', 'created_by', 'id');
    }
    
    public function addRole($body) {
        $this->roles()->create([
            'role' => $body
        ]);
    }
    
    public function roles_arr() {
        $roles = array();
        foreach ($this->roles as $role) {
            array_push($roles, $this->ROLE_STRING[$role->role]);
        }
        return $roles;
    }
    
    public function getAdminAttribute($value) {
        foreach ($this->roles as $role){
            if ($role->role == 'admin') {
                return true;
            }
        }
        return false;
    }

    public function getEditAttribute($value) {
        foreach ($this->roles as $role){
            if ($role->role == 'edit') {
                return true;
            }
        }
        return false;
    }
    
    public function getPublishAttribute($value) {
        foreach ($this->roles as $role){
            if ($role->role == 'publish') {
                return true;
            }
        }
        return false;
    }

    public function getManagedbAttribute($value) {
        foreach ($this->roles as $role){
            if ($role->role == 'managedb') {
                return true;
            }
        }
        return false;
    }
    
    public function getWp_passwordAttribute($value) {
        return Crypt::decryptString($this->wp_password);
    }
}
