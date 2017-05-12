<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Article extends Model
{
    
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    
    protected $fillable = [
            'created_by', 'updated_by', 'published_by', 'title', 'body', 'image', 'caption',
    ];
    
    
    public function updater() {
        return $this->belongsTo('\App\User', 'updated_by', 'id');
    }
    
    public function creator() {
        return $this->belongsTo('\App\User', 'created_by', 'id');
    }

    public function publisher() {
        return $this->belongsTo('\App\User', 'published_by', 'id');
    }
    
    public function getBodyAttribute($value) {
        return trim($value);
    }

    public function getTranslatedBodyAttribute($value) {
        return trim($value);
    }
    
    public function getCaptionAttribute($value) {
        return trim($value);
    }
    
    public function getCreatedDateAttribute($value) {
        return Carbon::parse($value)->setTimezone('Asia/Manila')->format('Y-m-d H:i');
    }

    public function getUpdatedDateAttribute($value) {
        return Carbon::parse($value)->setTimezone('Asia/Manila')->format('Y-m-d H:i');
    }
    
}
