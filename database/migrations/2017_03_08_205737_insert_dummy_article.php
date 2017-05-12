<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class InsertDummyArticle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user_id = User::all()->first()->id;
        DB::table ( 'articles' )->insertGetId ( array (
                'title' => 'Children lay motionless on ground – when the police helicopter realizes why, they immediately take action',
                'body' => 'Children are often full of energy and ideas, some more than others. I recall, for instance, that time when I was about 5 and thought it would be a great idea to paint me and my brother\'s bunk beds with a marker pen. 
Creative and beautiful, I thought... although my parents were not quite as amused. 
These kids from Surrey, UK, had a rather more urgent need to employ their creativity. While out on an Easter egg hunt as part of a charity event to raise money for a boy with leukemia, the children suddenly heard a helicopter.
When they looked up, they realized that it was a police helicopter.',
                'image' => '/storage/images/1234.jpg',
                'caption' => 'Children lay motionless on ground - when the police helicopter realizes why, they immediately take action',
                'url' => 'http://en.newsner.com/children-lay-motionless-on-ground-when-the-police-helicopter-realizes-why-they-immediately-take-action/about/news,family',
                'translated_title' => 'Children lay motionless on ground – when the police helicopter realizes why, they immediately take action',
                'translated_body' => '',
                'category' => 'Uncategorized',
                'status' => 'Draft',
                'updated_by' => $user_id,
                'created_by' => $user_id,
                'published_by' => 0
                
        ) );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('articles')->delete();
    }
}
