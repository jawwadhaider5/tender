<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = "tender_comments";
 
    public function tender()
    {
        return $this->belongsTo(Tender::class,'tender_id');
    }

    public function comment_by()
    {
        return $this->belongsTo(User::class,'commented_by');
    }
}
