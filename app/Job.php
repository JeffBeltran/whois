<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $casts = [
        'start' => 'date',
        'end' => 'date',
        'company_id' => 'integer',
        'project' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function highlights()
    {
        return $this->hasMany(Highlight::class);
    }
}
