<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Company::class)
            ->allowedIncludes(['jobs'])
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
        return QueryBuilder::for(Company::where('id', $id))
            ->allowedIncludes(['jobs'])
            ->firstOrFail();
    }
}
