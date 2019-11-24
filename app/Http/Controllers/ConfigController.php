<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ConfigController extends Controller
{
	public function index()
	{
        $config['nama_opd'] = Config::where('parameter','nama_opd')->first();
        $config['alamat_jaringan'] = Config::where('parameter','alamat_jaringan')->first();
        $config['alamat_pos'] = Config::where('parameter','alamat_pos')->first();
        $config['email'] = Config::where('parameter','email')->first();

    	return view('config.index',['config'=>$config]);
	}

    public function create()
    {
        return view('config.create');
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
        // $data = $request->all();

        Config::create(['parameter'=>$request->parameter,'value'=>$request->value,'descriptions'=>$request->descriptions]);
        toast('Data berhasil ditambahkan','success');
        return redirect()->back();
    }

    public function edit()
    {
        $config['nama_opd'] = Config::where('parameter','nama_opd')->first();
        $config['alamat_jaringan'] = Config::where('parameter','alamat_jaringan')->first();
        $config['alamat_pos'] = Config::where('parameter','alamat_pos')->first();
        $config['email'] = Config::where('parameter','email')->first();
        return view('config.edit',['config'=>$config]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        Config::where('parameter', 'nama_opd')->update(['value' => $request->nama_opd]);
        Config::where('parameter', 'alamat_jaringan')->update(['value' => $request->alamat_jaringan]);
        Config::where('parameter', 'alamat_pos')->update(['value' => $request->alamat_pos]);
        Config::where('parameter', 'email')->update(['value' => $request->email]);
        toast('Data berhasil diedit','success');
        return redirect()->route('config');
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
