<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Update extends Model
{
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    
    protected $fillable = [
            'location', 'table', 'created_by',
    ];
    
    public function creator() {
        return $this->belongsTo('\App\User', 'created_by', 'id');
    }
    
    public function getNameAttribute() {
        $parts = pathinfo($this->location);
        return $this->_prettyType($this->type) . '_' . Carbon::parse($this->created_date)->format('d-m-Y_His') . '.' . 'csv' ;
    }
    
    private function _prettyType($str) {
        if ($str == 'cleaning') return "Cleaning";
        if ($str == 'kanji_hybrid_phrase') return "Phrases";
        if ($str == 'kanji_hybrid') return "KanjiHybrid";
    }
}
