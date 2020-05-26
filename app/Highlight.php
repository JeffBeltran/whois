<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
}
