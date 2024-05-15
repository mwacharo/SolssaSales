<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'action', 'user_id', 'model', 'model_id', 'is_read', 'old_data', 'new_data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}