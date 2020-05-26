<?php

namespace App\Http\Controllers;

use App\Highlight;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class HighlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Highlight::class)
            ->allowedIncludes(['job', 'skills'])
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
        return QueryBuilder::for(Highlight::where('id', $id))
            ->allowedIncludes(['job', 'skills'])
            ->firstOrFail();
    }
}
