<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleConversation extends Model {
    use HasFactory;

    protected $table = 'title_conversations';

    protected $fillable = [
        'title_id',
        'message',
        'subject'
    ];

    public function getTitle() {
        return $this->belongsTo(Title::class, 'title_id', 'id');
    }

}
