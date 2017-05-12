<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KanjiHybridPhrase extends Model
{
    //
    protected $connection = 'kanjihybrid-db';
    protected $table = 'kanji_hybrid_phrase';
    
}
