<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StreamView extends Model
{
    protected $fillable = [
        'match_id', 'session_id', 'ip_address', 'duration_seconds', 'stream_source',
    ];
}