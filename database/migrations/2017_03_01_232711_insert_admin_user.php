<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InsertAdminUser extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $user_id = DB::table ( 'users' )->insertGetId ( array (
                'email' => 'admin@kanjihybrid.com',
                'username' => 'admin',
                'password' => bcrypt ( 'jahrakal' ),
                'fullname' => 'Administrator',
                'remember_token' => '',
                'updated_by' => 0,
                'created_by' => 0 
        ) );
        
        DB::table ( 'roles' )->insert ( [ 
                array (
                        'user_id' => $user_id,
                        'role' => 'admin' 
                ),
                array (
                        'user_id' => $user_id,
                        'role' => 'edit' 
                ),
                array (
                        'user_id' => $user_id,
                        'role' => 'publish' 
                ),
                array (
                        'user_id' => $user_id,
                        'role' => 'managedb' 
                ) 
        ] );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	DB::table('users')->delete();
    	DB::table('roles')->delete();
    }
}
