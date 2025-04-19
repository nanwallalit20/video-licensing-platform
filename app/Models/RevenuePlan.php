<?php

namespace App\Models;

use App\Enums\RevenuePlanStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenuePlan extends Model {
    use HasFactory;

    protected $table = 'revenue_plan';
    protected $fillable = [
        'title_id',
        'envelope_id',
        'type',  // enum
        'status', // enum
    ];

    protected $casts = [
        'status' => RevenuePlanStatus::class,
    ];

    public function getTitle() {
        return $this->hasOne(Title::class, 'id', 'title_id');
    }

}
