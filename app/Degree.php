<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    protected $casts = [
        'graduation' => 'date',
        'institution_id' => 'integer',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
