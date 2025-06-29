<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class DashboardController extends Controller
{
    /**
     * 
     * Display the dashboard page 
     * 
     * @param Request $request
     * 
     * @return Response
     * 
     * @throws Exception
     * @throws ResponseException
     * 
     */
    public function index()
    {
        return Inertia::render('Dashboard');
    }
}
