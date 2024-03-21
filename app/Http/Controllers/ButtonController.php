<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Exports\B2BExport;
use App\Exports\B2CSExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ButtonController extends Controller
{
    public function index($filename)
    {
        $file = Tax::where('file_name', $filename)->first();

        if (!$file) {
            return redirect()->route('upload.excel')->with('error', 'File uploaded not found');
        }

        $data = $file->data;

        return view('button', compact('file', 'data'));
    }

    public function exportB2B($filename)
    {
        $file = Tax::where('file_name', $filename)->first();

        if (!$file) {
            return redirect()->back()->with('error', 'File uploaded not found');
        }

        $data = $file->data;

        $formattedData = json_decode($data, true);
        $formattedData = array_filter($formattedData, function ($item) {
            return !empty($item['RecipientGSTIN']) && $item['DocumentValue'] > 0;
        });

        return Excel::download(new B2BExport($formattedData), 'b2b.xlsx');
    }
    public function exportB2CS($filename)
    {
        $file = Tax::where('file_name', $filename)->first();

        if (!$file) {
            return redirect()->route('upload.excel')->with('error', 'File uploaded not found');
        }

        $data = $file->data;

        $formattedData = json_decode($data, true);

        $formattedData = array_filter($formattedData, function ($item) {
            return !empty($item['RecipientGSTIN']) && $item['DocumentValue'] > 0;
        });

        return Excel::download(new B2CSExport($formattedData), 'b2cs.xlsx');
    }
}
