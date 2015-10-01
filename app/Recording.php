<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    protected $fillable = ['caller_number', 'transcription',
                           'recording_url', 'agent_id'];

    /**
     * The agent for whom this recording was made
     *
     * @return App\Agent
     */
    public function agent()
    {
        return $this->belongsTo('App\Agent');
    }
}
