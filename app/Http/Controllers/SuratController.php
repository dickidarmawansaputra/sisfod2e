<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Crypt;

class SuratController extends Controller
{
	public function index()
	{
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
            // Fingerprint
            $fingerprint = md5_file($request->gambar);

            // Create fingerprint text file
            Storage::disk('local')->put('public/surat/'.$fingerprint.'/'.$fingerprint.'.txt', $fingerprint);

            // Simpan gambar di storage
            $fileName = $request->gambar->getClientOriginalName();
            $path = $request->file('gambar')->storeAs('public/surat/'.$fingerprint.'', $fileName);
            $data['gambar'] = $path;
        }

        // RSA
        $rsa = new \phpseclib\Crypt\RSA();
        $key = $rsa->createKey();

        // .pem File
        $private = Storage::disk('local')->put('public/surat/'.$fingerprint.'/'.'private_key.pem', $key['privatekey']);
        $public = Storage::disk('local')->put('public/surat/'.$fingerprint.'/'.'public_key.pem', $key['publickey']);

        // Get Key .pem
        $private_key = Storage::disk('local')->get('public/surat/'.$fingerprint.'/private_key.pem');
        $public_key = Storage::disk('local')->get('public/surat/'.$fingerprint.'/public_key.pem');

        // Enkripsi RSA dengan private key dan fingerprint
        $rsa->loadKey($private_key);
        $enkripsi_rsa = $rsa->encrypt($fingerprint);

        // UNTUK DEKRIP
        // $rsa->loadKey($public_key);
        // return$rsa->decrypt($enkripsi_rsa);


        // AES
        $aes = new \phpseclib\Crypt\AES();
        $enkripsi_aes = $aes->encrypt($fingerprint);

        // Proses zip file
        $zipper = new \Chumper\Zipper\Zipper;
        $files = glob(storage_path('app/public/surat/'.$fingerprint.'/*'));
        $hasil = $zipper->make(storage_path('app/public/surat/'.$fingerprint.'/'.$fingerprint.'.zip'))->folder($fingerprint.'/')->add($files);
        $zipper->close();

        config(['filesystems.disks.sftp.host' => '36.91.27.226']);
        config(['filesystems.disks.sftp.username' => 'suandiftp']);
        config(['filesystems.disks.sftp.password' => 'osahnakberagambah']);
        config(['filesystems.disks.sftp.root' => '/ftp/filerepo']);
        config(['filesystems.disks.sftp.port' => '21']);
        $host = config('filesystems.disks.sftp.host');
        $username = config('filesystems.disks.sftp.username');
        $password = config('filesystems.disks.sftp.password');
        $root = config('filesystems.disks.sftp.root');

        // Dapatkan zip file baru dikirim

        $filezip = Storage::url('public/surat/'.$fingerprint.'/'.$fingerprint.'.zip');

        if ($filezip) {
            $path = Storage::disk('sftp')->put(basename($filezip), fopen('../storage/app/public/surat/'.$fingerprint.'/'.$fingerprint.'.zip', 'r+'));
        }

    	// Surat::create($data);
        toast('Data berhasil ditambahkan','success');
        return redirect()->back();
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
                <button class="btn btn-icon btn-primary btn-sm" data-toggle="modal" data-target="#update" data-id="'.$model->id.'" data-no_surat="'.$model->no_surat.'" data-perihal_surat="'.$model->perihal_surat.'" data-jenis_surat="'.$model->jenis_surat.'" data-deskripsi="'.$model->deskripsi.'"><i class="far fa-edit"></i></button>
                <button class="btn btn-icon btn-danger btn-sm delete" data-id="'.$model->id.'"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function kirim(Request $request)
    {
        // $sftp = Config::find($request->tujuan)->first();
        // config(['filesystems.disks.sftp.host' => $sftp->host]);
        // config(['filesystems.disks.sftp.username' => $sftp->username]);
        // config(['filesystems.disks.sftp.password' => $sftp->password]);
        // config(['filesystems.disks.sftp.root' => $sftp->root_path]);
        // $host = config('filesystems.disks.sftp.host');
        // $username = config('filesystems.disks.sftp.username');
        // $password = config('filesystems.disks.sftp.password');
        // $root = config('filesystems.disks.sftp.root');

        config(['filesystems.disks.sftp.host' => '36.91.27.226']);
        config(['filesystems.disks.sftp.username' => 'suandiftp']);
        config(['filesystems.disks.sftp.password' => 'osahnakberagambah']);
        config(['filesystems.disks.sftp.root' => '/ftp/filerepo']);
        config(['filesystems.disks.sftp.port' => '21']);
        $host = config('filesystems.disks.sftp.host');
        $username = config('filesystems.disks.sftp.username');
        $password = config('filesystems.disks.sftp.password');
        $root = config('filesystems.disks.sftp.root');

        // Dapatkan surat baru dikirim
        // $surat = Storage::get();

        $data = $request->all();
        // $data['jenis_surat'] = $sftp->id;
        $data['jenis_surat'] = 1;
        // return$request->gambar;
        if ($request->hasFile('gambar')) {
            $fileName = $request->gambar->getClientOriginalName();
            $path = Storage::disk('sftp')->put($fileName, fopen($request->gambar, 'r+'));
        }
        toast('Surat berhasil dikirim','success');
        return redirect()->back();

    }

    // surat masuk

    public function indexSuratMasuk()
    {
        $config = Config::all();
        return view('surat.cek', compact('config'));
    }

    public function dataSuratMasuk()
    {
        $model = Surat::all();

        return Datatables::of($model)
            ->addColumn('aksi', function($model) {
                return '
                <button class="btn btn-icon btn-info btn-sm" data-toggle="modal" data-target="#get-key" data-id="'.$model->id.'"><i class="fas fa-key"></i></button>

                <button class="btn btn-icon btn-success btn-sm" data-toggle="modal" data-target="#unduh" data-id="'.$model->id.'"><i class="fas fa-download" desabled></i></button>
                
                <button class="btn btn-icon btn-primary btn-sm" data-toggle="modal" data-target="#detail"  data-id="'.$model->id.'" data-no_surat="'.$model->no_surat.'" data-perihal_surat="'.$model->perihal_surat.'" data-jenis_surat="'.$model->jenis_surat.'" data-deskripsi="'.$model->deskripsi.'"><i class="fas fa-eye"></i></button>';
            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    // update keterangan surat
    public function update(Request $request)
    {
        $data = $request->all();
        $result = Surat::find($request->id)->update($data);
        toast('Data Keterangan Surat Berhasil diedit','success');
        return redirect()->back();
    }

    // hapus surat
    public function destroy($id)
    {
        Surat::find($id)->delete();
        return redirect()->back();
    }

    // public function dataKirim()
    // {
    //     $model = Surat::all();

    //     return Datatables::of($model)
    //         ->addColumn('aksi', function($model) {
    //             return '
    //             <button class="btn btn-icon btn-success btn-sm" data-toggle="modal" data-target="#kirim" data-id="'.$model->id.'"><i class="fas fa-paper-plane"></i></button>
    //             <button class="btn btn-icon btn-primary btn-sm" data-toggle="modal" data-target="#update" data-id="'.$model->id.'" data-nama_config="'.$model->nama_config.'" data-username="'.$model->username.'" data-password="'.$model->password.'" data-root_path="'.$model->root_path.'"><i class="far fa-edit"></i></button>
    //             <button class="btn btn-icon btn-danger btn-sm delete" data-id="'.$model->id.'"><i class="fas fa-trash"></i></button>';
    //         })
    //         ->rawColumns(['aksi'])
    //         ->addIndexColumn()
    //         ->make(true);
    // }
}
