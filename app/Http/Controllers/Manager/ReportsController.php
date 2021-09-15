<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    private $_model;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = t('Show Reports');
        return view('manager.reports.index', compact('title'));

    }

}
