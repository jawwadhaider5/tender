<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = "client_comments";
 
    public function client()
    {
        return $this->belongsTo(Client::class,'user_id');
    }

    public function comment_by()
    {
        return $this->belongsTo(User::class,'commented_by');
    }
}
