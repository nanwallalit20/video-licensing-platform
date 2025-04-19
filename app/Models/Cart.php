<?php

namespace App\Models;

use App\Enums\TitleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model {
    protected $fillable = [
        'buyer_id',
        'title_id',
        'season_id',
    ];

    protected $table = 'carts';

    /**
     * Get the title associated with this cart item.
     */
    public function getTitle(): BelongsTo {
        return $this->belongsTo(Title::class, 'title_id');
    }
}
