<?php

namespace App\Http\Controllers;

use App\Degree;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Degree::class)
            ->allowedIncludes(['institution'])
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
        return QueryBuilder::for(Degree::where('id', $id))
            ->allowedIncludes(['institution'])
            ->firstOrFail();
    }
}
