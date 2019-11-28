<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function data(Request $request)
    {
        $model = User::with('role')->get();

        return Datatables::of($model)
            ->addColumn('peran', function ($model) {
                if ($model->role->role == 'admin') {
                    return '<h6><span class="badge badge-danger">Admin</span></h6>';
                } else if ($model->role->role == 'operator') {
                    return '<h6><span class="badge badge-success">Operator</span></h6>';
                } else {
                    return '<h6><span class="badge badge-success">OPD</span></h6>';
                }
            })
            ->addColumn('aksi', function ($model) {
                return '
                <button class="btn btn-icon btn-primary btn-sm" data-toggle="modal" data-target="#update" data-id="' . $model->id . '" data-name="' . $model->name . '" data-email="' . $model->email . '" data-role="' . $model->role->role . '" data-password="' . $model->password . '"><i class="far fa-edit"></i></button>
                <button class="btn btn-icon btn-danger btn-sm delete" data-id="' . $model->id . '"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['peran', 'aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        $data             = $request->all();
        $data['password'] = Hash::make($request->password);
        $result           = User::create($data);

        $data['user_id'] = $result->id;
        Role::create($data);

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $data             = $request->all();
        $data['password'] = Hash::make($request->password);
        $result           = User::find($request->id)->update($data);

        // $user_id = $result->id;
        // $data['user_id'] = $user_id;
        // Role::where('user_id', $user_id)->update($data);

        $role = Role::where('user_id', '=', $request->id)->first();
        $role->update([
            'role' => request('role'),
        ]);

        toast('Data berhasil diedit', 'success');
        return redirect()->back();
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->back();
    }
}
