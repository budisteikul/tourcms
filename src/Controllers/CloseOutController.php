<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCloseOutRequest;
use App\Http\Requests\UpdateCloseOutRequest;
use App\Models\CloseOut;

class CloseOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCloseOutRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCloseOutRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CloseOut  $closeOut
     * @return \Illuminate\Http\Response
     */
    public function show(CloseOut $closeOut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CloseOut  $closeOut
     * @return \Illuminate\Http\Response
     */
    public function edit(CloseOut $closeOut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCloseOutRequest  $request
     * @param  \App\Models\CloseOut  $closeOut
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCloseOutRequest $request, CloseOut $closeOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CloseOut  $closeOut
     * @return \Illuminate\Http\Response
     */
    public function destroy(CloseOut $closeOut)
    {
        //
    }
}
