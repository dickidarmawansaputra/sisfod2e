<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ConfigController extends Controller
{
	public function index()
	{
		return view('config.index');
	}

    public function data()
    {
        $model = Config::all();

        return Datatables::of($model)
            ->addColumn('aksi', function($model) {
                return '
                <button class="btn btn-icon btn-primary btn-sm" data-toggle="modal" data-target="#update" data-id="'.$model->id.'" data-nama_config="'.$model->nama_config.'" data-username="'.$model->username.'" data-password="'.$model->password.'" data-root_path="'.$model->root_path.'" data-host="'.$model->host.'"><i class="far fa-edit"></i></button>
                <button class="btn btn-icon btn-danger btn-sm delete" data-id="'.$model->id.'"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        Config::create($data);
        toast('Data berhasil ditambahkan','success');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $data = $request->all();
        Config::find($request->id)->update($data);
        toast('Data berhasil diedit','success');
        return redirect()->back();
    }

    public function destroy($id)
    {
        Config::find($id)->delete();
        return redirect()->back();
    }



    public function config(Request $request)
    {
        config(['filesystems.disks.sftp.host' => '127.0.0.1']);
        config(['filesystems.disks.sftp.username' => 'root']);
        config(['filesystems.disks.sftp.password' => 'secret']);
        $host = config('filesystems.disks.sftp.host');
        $username = config('filesystems.disks.sftp.username');
        $password = config('filesystems.disks.sftp.password');
        
        return config('filesystems.disks.sftp');
        dd($host);
        config(['app.timezone' => 'America/Chicago']);

        $value = config('app.timezone');

        dd($value);
        // $host = putenv('SFTP_HOST=127.0.0.1'); // Contoh
        // $username = putenv('SFTP_USERNAME=root'); // Contoh
        // $password = putenv('SFTP_PASSWORD=secret'); // Contoh
        // $dotenv = Dotenv::create(__DIR__, '../../../.env');
        // $dotenv->load();
        // dd(config(['app.timezone' => 'America/Chicago']));
    }
}
