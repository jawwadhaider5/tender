<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderRespond extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = "tender_responds";
 
    public function tender()
    {
        return $this->belongsTo(Tender::class,'tender_id');
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
