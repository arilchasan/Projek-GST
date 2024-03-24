<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Exports\B2BExport;
use App\Exports\HSNExport;
use App\Exports\B2CSExport;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function exportB2B($data)
    {
        $file = Tax::where('file_name', $data)->first();

        if (!$file) {
            return redirect()->back()->with('error', 'File uploaded not found');
        }

        $json = $file->data;

        $formattedData = json_decode($json, true);

        $formattedData = array_filter($formattedData, function ($item) {
            return !empty($item['RecipientGSTIN']) && $item['DocumentValue'] > 0;
        });

        return Excel::download(new B2BExport($formattedData), 'b2b.xlsx');
    }
    public function showB2B($filename , Request $request)
    {
        $file = Tax::where('file_name', $filename)->first();

        if (!$file) {
            return redirect()->back()->with('error', 'File uploaded not found');
        }

        $data = $file->data;
        $filename = $file->file_name;
        $formattedData = json_decode($data, true);

        $groupedData = collect($formattedData)->groupBy(function ($item) {
            return $item['DocumentNumber'] . '_' . $item['GSTRate'];
        })->map(function ($group) {
            $firstItem = $group->first();
            $taxableAmount = $group->sum('TaxableAmount');
            $cessAmount = $group->sum('CESS');
            $igstAmount = $group->sum('IGST');
            return [
                'GSTIN/UIN of Recipient' => $firstItem['RecipientGSTIN'],
                'Receiver Name' => $firstItem['RecipientName'] ?? '',
                'Invoice Number' => $firstItem['DocumentNumber'] ?? '',
                'Invoice Date' => isset($firstItem['DocumentDate']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($firstItem['DocumentDate'])->format('d/m/Y') : '',
                'Invoice Value' => $firstItem['DocumentValue'] >= 0 ? $firstItem['DocumentValue'] : null,
                'Place Of Supply' => $firstItem['StateCode_Name'] ?? '',
                'Reverse Charge' => 'N',
                'Applicable % of Tax Rate' => '' ?? '',
                'Invoice Type' => $igstAmount !== 0 ? 'Intra-State supplies attracting IGST' : 'Regular B2B',
                'E-Commerce GSTIN' => '',
                'IGST' => isset($igstAmount) ? ($igstAmount !== 0 ?  $igstAmount : '0') : '0',
                'Rate' =>  isset($firstItem['GSTRate']) ? ($firstItem['GSTRate'] !== 0 ? $firstItem['GSTRate'] : '0') : '0',
                'Taxable Value' => isset($taxableAmount) ? ($taxableAmount !== 0 ? $taxableAmount : '0') : '0',
                'Cess Amount' => isset($cessAmount) ? ($cessAmount !== 0 ? $cessAmount : '0') : '0',
            ];
        });
        $formattedData = $groupedData->filter(function ($item) {
            return !empty($item['GSTIN/UIN of Recipient']) && $item['Invoice Value'] > 0 && $item['Rate'] != 0;
        });

        $sortedData = $formattedData->sortBy('Invoice Date');
        $taxableAmount = $sortedData->sum('Taxable Value');
        $cessAmount = $sortedData->sum('Cess Amount');

        $groupedData = collect($formattedData)->groupBy(function ($item) {
            return $item['Invoice Number'] . '_' . $item['Invoice Value'];
        });
        $uniqueInvoiceValues = $groupedData->map(function ($group) {
            return $group->first();
        });
        $invoiceValue = $uniqueInvoiceValues->filter(function ($item) {
            return $item['Invoice Value'] > 0;
        })->sum('Invoice Value');

        if($request->ajax()){
            $data = $sortedData;
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);

        }
        return view('excel.b2b', compact('sortedData', 'filename', 'taxableAmount', 'cessAmount', 'invoiceValue'));
    }

    public function showB2CS($filename, Request $request)
    {
        $file = Tax::where('file_name', $filename)->first();

        if (!$file) {
            return redirect()->route('upload.excel')->with('error', 'File uploaded not found');
        }

        $json = $file->data;

        $formattedData = json_decode($json, true);

        $filteredData = collect($formattedData)->filter(function ($item) {
            return $item['RegistrationType'] === 'UnRegistered' && $item['DocumentValue']  && $item['GSTRate'] !== 0;
        });

        $dateFilter = $filteredData->sortBy(['StateCode_Name', 'GSTRate'], SORT_NATURAL | SORT_FLAG_CASE);

        $sortedData =  collect($filteredData)->groupBy(function ($item) {
            return $item['StateCode_Name'] . '_' . $item['GSTRate'];
        })->map(function ($group) {
            $firstItem = $group->first();
            $taxableAmount = $group->sum('TaxableAmount');
            $cessAmount = $group->sum('CESS');
            $igstAmount = $group->sum('IGST');
            return [
                'Type' => 'OE',
                'Place Of Supply' => $firstItem['StateCode_Name'] ?? '',
                'Applicable % of Tax Rate' => '',
                'Rate' => ($firstItem['GSTRate'] ?? '0'),
                //'IGST' => isset($igstAmount) ? ($igstAmount !== 0 ?  $igstAmount : '0') : '0',
                'Taxable Value' => isset($taxableAmount) ? ($taxableAmount !== 0 ? $taxableAmount : '0') : '0',
                'Cess Amount' => isset($cessAmount) ? ($cessAmount !== 0 ? $cessAmount : '0') : '0',
                'E-Commerce GSTIN' => '',
            ];
        });

        $taxableAmount = $sortedData->sum('Taxable Value');
        $cessAmount = $sortedData->sum('Cess Amount');

        if($request->ajax()){
            $data = $sortedData;
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);

        }

        return view('excel.b2cs', compact('sortedData', 'filename', 'taxableAmount', 'cessAmount'));
    }
    public function exportB2CS($data)
    {
        $file = Tax::where('file_name', $data)->first();

        if (!$file) {
            return redirect()->route('upload.excel')->with('error', 'File uploaded not found');
        }

        $json = $file->data;

        $formattedData = json_decode($json, true);

        $formattedData = array_filter($formattedData, function ($item) {
            return $item['RegistrationType'] === 'UnRegistered' && $item['DocumentValue'];
        });

        return Excel::download(new B2CSExport($formattedData), 'b2cs.xlsx');
    }

    public function showHSN($filename, Request $request)
    {
        $file = Tax::where('file_name', $filename)->first();

        if (!$file) {
            return redirect()->route('upload.excel')->with('error', 'File uploaded not found');
        }

        $json = $file->data;

        $formattedData = json_decode($json, true);

        $filteredData = collect($formattedData)->filter(function ($item) {
            return $item['HSNCode']  && $item['GSTRate'] !== 0;
        });
        $groupedData = $filteredData->groupBy(function ($item) {
            return $item['HSNCode'] . '_' . $item['GSTRate'];
        })->map(function ($group) {
            $firstItem = $group->first();
            $taxableAmount = $group->sum('TaxableAmount');
            $cessAmount = $group->sum('CESS');
            $igstAmount = $group->sum('IGST');
            $totalQty = $group->sum('ItemQuantity');
            $totalCGST = $group->sum('CGST');
            $totalSGST = $group->sum('SGST');
            return [
                'HSN Code' => $firstItem['HSNCode'],
                'Description' => $firstItem['HSNDescription'] ?? '',
                'UQC' => 'NOS-NUMBER',
                'Total Quantity' => $totalQty,
                'Total Value' =>  $taxableAmount + $igstAmount + $totalCGST + $totalSGST + $cessAmount,
                'Taxable Value' => isset($taxableAmount) ? ($taxableAmount !== 0 ? $taxableAmount : '0') : '0',
                'Integrated Tax Amount' => isset($igstAmount) ? ($igstAmount !== 0 ?  $igstAmount : '0') : '0',
                'Central Tax Amount' =>  isset($totalCGST) ? ($totalCGST !== 0 ? $totalCGST : '0') : '0',
                'State/UT Tax Amount' => isset($totalSGST) ? ($totalSGST !== 0 ? $totalSGST : '0') : '0',
                'Cess Amount' => isset($cessAmount) ? ($cessAmount !== 0 ? $cessAmount : '0') : '0',
                'Rate' => $firstItem['GSTRate'] ?? '0',
            ];
        });

        $totalValue = $groupedData->sum('Total Value');
        $taxableAmount = $groupedData->sum('Taxable Value');
        $integratedTaxAmount = $groupedData->sum('Integrated Tax Amount');
        $centralTaxAmount = $groupedData->sum('Central Tax Amount');
        $stateTaxAmount = $groupedData->sum('State/UT Tax Amount');
        $cessAmount = $groupedData->sum('Cess Amount');


        if($request->ajax()){
            $data = $groupedData;
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);

        }

        return view('excel.hsn', compact('groupedData', 'filename', 'taxableAmount', 'cessAmount', 'totalValue', 'integratedTaxAmount', 'centralTaxAmount', 'stateTaxAmount'));
    }

    public function exportHSN($data)
    {
        $file = Tax::where('file_name', $data)->first();

        if (!$file) {
            return redirect()->route('upload.excel')->with('error', 'File uploaded not found');
        }

        $json = $file->data;

        $formattedData = json_decode($json, true);

        $formattedData = array_filter($formattedData, function ($item) {
            return $item['HSNCode']  && $item['GSTRate'] !== 0;
        });

        return Excel::download(new HSNExport($formattedData), 'hsn.xlsx');
    }
}
