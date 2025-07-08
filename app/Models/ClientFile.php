<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = "client_files";
 
    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }

    public function uploaded_by()
    {
        return $this->belongsTo(User::class,'uploaded_by');
    }
}
