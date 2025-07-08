<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FutureClient extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    protected $table = "future_clients";

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    } 
      
    public function comments()
    {
        return $this->hasMany(FutureClientComment::class, 'future_client_id')->orderBy('created_at', 'desc');
    }
    public function responds()
    {
        return $this->hasMany(FutureClientRespond::class, 'future_client_id')->orderBy('created_at', 'desc');
    }
    public function files()
    {
        return $this->hasMany(FutureClientFile::class, 'future_client_id')->orderBy('created_at', 'desc');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function tender_type()
    {
        return $this->belongsTo(TenderType::class, 'tender_type_id');
    }
 
}
