<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TaxController extends Controller
{
    public function uploadExcel(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'file' => 'required|mimes:xls,xlsx',
            ]);

            $file = $request->file('file');
            $filePath = $file->store('data', 'public');
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            $jsonData = [];
            $columnMapping = [
                'A' => 'TransactionType',
                'B' => 'DocumentDate',
                'C' => 'DocumentNumber',
                'D' => 'DocumentValue',
                'E' => 'InvRoundOffValue',
                'F' => 'RecipientCode',
                'G' => 'RecipientName',
                'H' => 'RegistrationType',
                'I' => 'StateCode_Name',
                'J' => 'RecipientGSTIN',
                'K' => 'PANNo',
                'L' => 'HSNCode',
                'M' => 'HSNDescription',
                'N' => 'ItemCode',
                'O' => 'ItemDescription',
                'P' => 'ItemPrice',
                'Q' => 'ItemQuantity',
                'R' => 'GrossTotal',
                'S' => 'ItemTotalDiscount',
                'T' => 'SubDTMargin',
                'U' => 'TaxableAmount',
                'V' => 'GSTRate',
                'W' => 'CESSRate',
                'X' => 'CGST',
                'Y' => 'SGST',
                'Z' => 'UTGST',
                'AA' => 'IGST',
                'AB' => 'CESS',
                'AC' => 'KFCESS',
                'AD' => 'NetLineAmount',
            ];


            for ($row = 2; $row <= $highestRow; ++$row) {
                $rowData = [];
                foreach ($columnMapping as $column => $fieldName) {
                    $cellValue = $worksheet->getCell($column . $row)->getValue();
                    $rowData[$fieldName] = $cellValue;
                }
                $jsonData[] = $rowData;
            }
            $randomChars = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
            $randomChars = substr($randomChars, 0, 5);
            $uniqueFileName = $randomChars . '_' . $file->getClientOriginalName();

            $tax = new Tax();
            $tax->file_path = $filePath;
            $tax->file_name = $uniqueFileName;
            $tax->user_id = auth()->id();
            $tax->data = json_encode($jsonData);
            $tax->created_at = Carbon::now('Asia/Jakarta')->toDateTimeString();
            $tax->save();

            DB::commit();
            activity()
                ->withProperties(['url' => asset($filePath)])
                ->log(auth()->user()->name . ' uploaded a file.');
            return redirect()->route('button', ['filename' =>  $tax->file_name])->with('success', 'Success upload file');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function showTaxData($id)
    {
        try {
            $taxRecord = Tax::find($id);

            if (!$taxRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found.',
                ], 404);
            }


            $decodedData = json_decode($taxRecord->data, true);

            $perPage = 1000;
            $totalData = count($decodedData);
            $currentPage = request()->input('page', 1);

            $totalPages = ceil($totalData / $perPage);

            if ($currentPage > $totalPages) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found.',
                ], 404);
            }

            $startIndex = ($currentPage - 1) * $perPage;
            $slicedData = array_slice($decodedData, $startIndex, $perPage);

            return response()->json([
                'success' => true,
                'totalData' => $totalData,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'data' => $slicedData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
