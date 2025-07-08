<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FutureClientComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = "future_client_comments";
 
    public function future_client()
    {
        return $this->belongsTo(FutureClient::class,'future_client_id');
    }

    public function comment_by()
    {
        return $this->belongsTo(User::class,'commented_by');
    }
}
