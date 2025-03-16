<?php

namespace Modules\UiScheduler\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UiSchedulerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('uischeduler::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        return view('uischeduler::create');

    }


    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show(int $id): Renderable
    {
        return view('uischeduler::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit(int $id): Renderable
    {
        return view('uischeduler::edit');
    }
}
