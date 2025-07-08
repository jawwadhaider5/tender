<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FutureClientRespond extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = "future_client_responds";
 
    public function future_client()
    {
        return $this->belongsTo(FutureClient::class,'future_client_id');
    }

    public function responds_by()
    {
        return $this->belongsTo(User::class,'responded_by');
    }

    public function assigned_to()
    {
        return $this->belongsTo(User::class,'assigned_user_id');
    }

    public function getAssignedUserNamesAttribute()
    {
        return collect($this->assigned_user_id)->map(function ($uid) {
            $user = User::find($uid);
            return $user ? $user->name : null;
        })->filter()->implode(', ');
    }

    protected $casts = [
        'assigned_user_id' => 'array',
    ];
}
