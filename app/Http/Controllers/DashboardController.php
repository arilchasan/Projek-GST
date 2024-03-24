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
        return view('admin.dashboard', ['activity' => $activity, 'user' => $user]);
    }

    public function user()
    {
        if (request()->ajax()) {
            $data = User::orderBy('expired_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '';

                    if ($data->status == 'active') {
                        // Jika expired, tombol "Active" berwarna merah
                        $actionBtn = '<a href="/dashboard/user-file/' . $data->id . '" class="btn btn-secondary">Lihat File</a> <a class="btn btn-danger" href="/dashboard/nonactive-user/' . $data->id . '">Nonactive</a>';
                    } else {
                        // Jika tidak expired, tombol "Active" berwarna hijau
                        $actionBtn = '<a href="/dashboard/user-file/' . $data->id . '" class="btn btn-secondary">Lihat File</a> <a class="btn btn-success" href="/dashboard/active-user/' . $data->id . '" role="button">Active</a>';
                    }

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
        return view('admin.users.file', ['user' => $user, 'tax' => $tax]);
    }

    public function activeUser($id)
    {
        $user = User::find($id);
        $user->update([
            'status' => 'active'
        ]);
        return redirect()->back()->with('success', 'User successfully activated');
    }

    public function nonActiveUser($id)
    {
        $user = User::find($id);
        $user->update([
            'status' => 'nonactive'
        ]);
        return redirect()->back()->with('success', 'User successfully nonactivated');
    }
}
