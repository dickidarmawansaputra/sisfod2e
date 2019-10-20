<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;

class SuratController extends Controller
{
	public function index()
	{
        // $data = Surat::all();
        // foreach ($data as $key => $value) {
        //     $gambar = explode(",", $value->gambar);
        // }
		return view('surat.index');
	}

    public function store(Request $request)
    {
        $this->validate($request, 
            ['gambar' => 'required|mimes:jpeg,png,jpg'],
            ['gambar.mimes' => 'Surat harus dalam format jpeg,png, atau jpg!']
        );
        
    	$data = $request->all();
    	if ($request->hasFile('gambar')) {
            for ($i=0; $i < count($request->gambar); $i++) { 
    			$fileName[$i] = $request->gambar[$i]->getClientOriginalName();
    			$path[$i] = $request->gambar[$i]->storeAs('public/surat', $fileName[$i]);
                $data['gambar'] = implode(",", $path);
            }
    	}
    	Surat::create($data);
        toast('Data berhasil ditambahkan','success');
        return redirect()->back();
    }

    public function moveFileToServer(Request $request)
    {
        $host = putenv('SFTP_HOST=127.0.0.1'); // Contoh
        $username = putenv('SFTP_USERNAME=root'); // Contoh
        $password = putenv('SFTP_PASSWORD=secret'); // Contoh
        $dotenv = Dotenv::create('..');
        $dotenv->load();

        $data = $request->all();
        if ($request->hasFile('gambar')) {
            $fileName = $request->gambar->getClientOriginalName();
            $path = Storage::disk('sftp')->put($fileName, fopen($request->gambar, 'r+'));
        }
        return 'berhasil';
    }

    public function kirimSurat()
    {
        $config = Config::all();
        return view('surat.kirim', compact('config'));
    }

    public function dataKirim()
    {
        $model = Surat::all();

        return Datatables::of($model)
            ->addColumn('aksi', function($model) {
                return '
                <button class="btn btn-icon btn-success btn-sm" data-toggle="modal" data-target="#kirim" data-id="'.$model->id.'"><i class="fas fa-paper-plane"></i></button>
                <button class="btn btn-icon btn-primary btn-sm" data-toggle="modal" data-target="#update" data-id="'.$model->id.'" data-nama_config="'.$model->nama_config.'" data-username="'.$model->username.'" data-password="'.$model->password.'" data-root_path="'.$model->root_path.'"><i class="far fa-edit"></i></button>
                <button class="btn btn-icon btn-danger btn-sm delete" data-id="'.$model->id.'"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function kirim(Request $request)
    {
        $sftp = Config::find($request->kirim)->first();
        config(['filesystems.disks.sftp.host' => $sftp->host]);
        config(['filesystems.disks.sftp.username' => $sftp->username]);
        config(['filesystems.disks.sftp.password' => $sftp->password]);
        config(['filesystems.disks.sftp.root' => $sftp->root_path]);
        $host = config('filesystems.disks.sftp.host');
        $username = config('filesystems.disks.sftp.username');
        $password = config('filesystems.disks.sftp.password');
        $root = config('filesystems.disks.sftp.root');

        // Dapatkan surat baru dikirim
        // $surat = Storage::get();

        $data = $request->all();
        if ($request->hasFile('gambar')) {
            $fileName = $request->gambar->getClientOriginalName();
            $path = Storage::disk('sftp')->put($fileName, fopen($request->gambar, 'r+'));
        }
        toast('Surat berhasil dikirim','success');
        return redirect()->back();

    }
}
