<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $activity = Activity::latest()->take(5)->get();
        $user = count(User::all());
        return view('admin.dashboard',['activity' => $activity, 'user' => $user]);
    }

    public function user()
    {
        if (request()->ajax()) {
            $data = User::orderBy('expired_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('expired', function ($row) {
                    $expiredAt = Carbon::parse($row->expired_at)->toDateTimeString();
                    $now = Carbon::now('Asia/Jakarta')->toDateTimeString();
                    if ($expiredAt < $now) {
                        return 'Expired';
                    } else {
                        return 'Active';
                    }
                })
                ->addColumn('action', function ($data) {
                    $actionBtn = '  <a href="/dashboard/user-file/' . $data->id . '" class="text-xs text-gray-500 hover:underline">Lihat File</a>';

                    return $actionBtn;
                })
                ->rawColumns(['expired', 'action'])
                ->make(true);
        }
        return view('admin.users.index');
    }

    public function fileUser($id)
    {
        $user = User::find($id);
        $tax = Tax::where('user_id', $id)->get();
        return view('admin.users.file', ['user' => $user , 'tax' => $tax]);
    }
}
