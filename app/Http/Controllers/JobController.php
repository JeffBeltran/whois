<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Job::class)
            ->allowedIncludes(['company', 'highlights.skills'])
            ->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return QueryBuilder::for(Job::where('id', $id))
            ->allowedIncludes(['company', 'highlights.skills'])
            ->firstOrFail();
    }
}
