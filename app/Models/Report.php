<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'explanation',
        'user_id',
        'post_id',
        'discussion_id',
        'reporter_id'
    ];
}
