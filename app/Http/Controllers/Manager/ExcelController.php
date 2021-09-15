<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Imports\StocksImport;
use Illuminate\Http\Request;

class ExcelController extends Controller
{

    public function create()
    {
        return view('manager.excel.create');
    }

    public function excel(Request $request)
    {
        $data = (new StocksImport())->toArray(request()->file('excel'));
        dd($data);
        foreach ($data[0] as $row) {
            $fsc = $row[0];
            $qnt = $row[1];
            $this->products->importStockFromExcel($fsc, $qnt);
        }
        return 'done';
    }

}
