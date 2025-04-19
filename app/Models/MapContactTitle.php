<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapContactTitle extends Model {
    use HasFactory;

    protected $table = 'map_contact_titles';

    protected $fillable = [
        'title_id',
        'contact_id'
    ];


    public function getContact() {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
