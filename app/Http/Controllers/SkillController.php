<?php

namespace App\Http\Controllers;

use App\Skill;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Skill::class)
            ->allowedIncludes(['highlights', 'parent', 'children'])
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
        return QueryBuilder::for(Skill::where('id', $id))
            ->allowedIncludes(['highlights', 'parent', 'children'])
            ->firstOrFail();
    }
}
