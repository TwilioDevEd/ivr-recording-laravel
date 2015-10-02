<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    protected $fillable = ['caller_number', 'transcription',
                           'recording_url', 'agent_id'];
}
