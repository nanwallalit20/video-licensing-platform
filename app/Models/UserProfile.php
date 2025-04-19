<?php

namespace App\Models;

use App\Enums\Roles;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model {
    use HasFactory;

    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'role_id',
        'phone',
        'movies_count',
        'project_role',
        'country_code',
        'whatsapp_number',
        'Whatsapp_country_code',
        'is_subscribed',
        'subscription_link',
        'link_expires_at'
    ];

    protected $casts = [
        'link_expires_at' => 'datetime',
        'is_subscribed' => SubscriptionStatus::class,
        'role_id' => Roles::class,
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    // Function to count titles for the user
    public function getTitleCountAttribute() {
        return $this->user->getTitles()->count();
    }

}
