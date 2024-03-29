<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Crypt;
use phpseclib\Crypt\RSA;
use Illuminate\Support\Str;

class _SuratController extends Controller
{
	public function index()
	{
		$list_opd = json_decode(file_get_contents(config('custom.suandi_server').'opd'));

        return view('surat.index',['list_opd'=>$list_opd]);
	}

    public function store(Request $request)
    {
        $random_name = date('Ymdhis').Str::random(32);

        $this->validate($request, 
            ['gambar' => 'required|mimes:jpeg,png,jpg'],
            ['gambar.mimes' => 'Surat harus dalam format jpeg,png, atau jpg!']
        );
        
    	$data = $request->all();
        if ($request->hasFile('gambar')) {
            // Fingerprint
            $fingerprint = md5_file($request->gambar);

            // Create fingerprint text file
            // Storage::disk('local')->put('', $fingerprint);

            // Simpan gambar di storage
            $fileName = $request->gambar->getClientOriginalName();
            $path = $request->file('gambar')->storeAs('public/surat/'.$fingerprint.'', $fileName);
            $data['gambar'] = $path;
        }

        $server_tujuan = json_decode(file_get_contents(config('custom.suandi_server').'opd/'.$request->tujuan));

        $publickey = Storage::disk('ftp')->get($server_tujuan->nama_kunci_public);

        // RSA
        // $rsa = new \phpseclib\Crypt\RSA();
        // $key = $rsa->createKey();

        // // .pem File
        // $private = Storage::disk('local')->put('public/surat/'.$fingerprint.'/'.'private_key.pem', $key['privatekey']);
        // $public = Storage::disk('local')->put('public/surat/'.$fingerprint.'/'.'public_key.pem', $key['publickey']);

        // // Get Key .pem
        // $private_key = Storage::disk('local')->get('public/surat/'.$fingerprint.'/private_key.pem');
        // $public_key = Storage::disk('local')->get('public/surat/'.$fingerprint.'/public_key.pem');

        // // Enkripsi RSA dengan private key dan fingerprint
        $rsa->loadKey($publickey);
        $digital_signature = $rsa->encrypt($fingerprint);
        $file_digital_sign = Storage::disk('local')->put($random_name.'/maris.dig', $digital_signature);


        // UNTUK DEKRIP
        // $rsa->loadKey($public_key);
        // return$rsa->decrypt($enkripsi_rsa);


        // AES
        // $aes = new \phpseclib\Crypt\AES();
        // $enkripsi_aes = $aes->encrypt($fingerprint);

        // Proses zip file
        $zipper = new \Chumper\Zipper\Zipper;
        $files = glob(storage_path('app/public/surat/'.$fingerprint.'/*'));
        // $hasil = $zipper->make(storage_path('app/public/surat/'.$fingerprint.'/'.$fingerprint.'.zip'))->folder($fingerprint.'/')->add($files);
        // $zipper->close();

        // config(['filesystems.disks.sftp.host' => '']);
        // config(['filesystems.disks.sftp.username' => 'root']);
        // config(['filesystems.disks.sftp.password' => '']);
        // config(['filesystems.disks.sftp.root' => '/home/zethlabs.id/html/public']);
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

        // Dapatkan zip file baru dikirim

        // $filezip = Storage::url('public/surat/'.$fingerprint.'/'.$fingerprint.'.zip');

        // if ($filezip) {
        //     $path = Storage::disk('sftp')->put(basename($filezip), fopen('../storage/app/public/surat/'.$fingerprint.'/'.$fingerprint.'.zip', 'r+'));
        // }

    	Surat::create($data);
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
                <button class="btn btn-icon btn-success btn-sm" data-toggle="modal" data-target="#kirim" data-id="'.$model->id.'" data-surat="'.$model->gambar.'"><i class="fas fa-paper-plane"></i></button>
                <button class="btn btn-icon btn-primary btn-sm" data-toggle="modal" data-target="#update" data-id="'.$model->id.'" data-no_surat="'.$model->no_surat.'" data-perihal_surat="'.$model->perihal_surat.'" data-jenis_surat="'.$model->jenis_surat.'" data-tgl_surat="'.$model->tgl_surat.'" data-deskripsi="'.$model->deskripsi.'" data-gambar="'.$model->gambar.'" data-gambar_file="'.Storage::disk('local')->url($model->gambar).'"><i class="far fa-edit"></i></button>
                <button class="btn btn-icon btn-danger btn-sm delete" data-id="'.$model->id.'"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function kirim(Request $request)
    {
        $ftp = Config::find($request->tujuan)->first();
        config(['filesystems.disks.ftp.host' => $ftp->host]);
        config(['filesystems.disks.ftp.username' => $ftp->username]);
        config(['filesystems.disks.ftp.password' => $ftp->password]);
        config(['filesystems.disks.ftp.root' => $ftp->root_path]);
        $host = config('filesystems.disks.ftp.host');
        $username = config('filesystems.disks.ftp.username');
        $password = config('filesystems.disks.ftp.password');
        $root = config('filesystems.disks.ftp.root');
        $url = str_replace("/storage", "storage", Storage::disk('local')->url($request->surat));
        $path = Storage::disk('ftp')->put($request->surat, fopen($url, 'r+'));
        
        // config(['filesystems.disks.sftp.host' => '36.91.27.226']);
        // config(['filesystems.disks.sftp.username' => 'suandiftp']);
        // config(['filesystems.disks.sftp.password' => 'osahnakberagambah']);
        // config(['filesystems.disks.sftp.root' => '/ftp/filerepo']);
        // config(['filesystems.disks.sftp.port' => '21']);
        // $host = config('filesystems.disks.sftp.host');
        // $username = config('filesystems.disks.sftp.username');
        // $password = config('filesystems.disks.sftp.password');
        // $root = config('filesystems.disks.sftp.root');

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
    public function enkrip()
    {
        echo "proses enkripsi";
        $private_key = Storage::disk('local')->get('private_key.pem');
        $public_key = Storage::disk('local')->get('public_key.pem');

        $rsa = new RSA();
        //$rsa->setPassword('password');
        $rsa->loadKey($private_key); // private key

        $plaintext = 'Saya Dian Prawira';

        //$rsa->setSignatureMode(RSA::SIGNATURE_PSS);
        $signature = $rsa->sign($plaintext);
        echo $signature;
        $private = Storage::disk('local')->put('dian.sign', $signature);

        $signature_key = Storage::disk('local')->get('dian.sign');

        $rsa->loadKey($public_key); // private key


        // $rsa->loadKey('...'); // public key
        echo $rsa->verify($plaintext, $signature_key) ? 'verified' : 'unverified';
        // // Enkripsi RSA dengan private key dan fingerprint
        // $rsa->loadKey($private_key);
        // $enkripsi_rsa = $rsa->encrypt($fingerprint);
    }

    public function dekrip()
    {
        echo "proses dekripsi";
    }

    public function buatkunci()
    {
        echo "proses membuat kunci";
        $rsa = new RSA();
 
        //$rsa->setPrivateKeyFormat(RSA::PRIVATE_FORMAT_PKCS1);
        //$rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_PKCS1);

        //define('CRYPT_RSA_EXPONENT', 65537);
        //define('CRYPT_RSA_SMALLEST_PRIME', 64); // makes it so multi-prime RSA is used
        $key = $rsa->createKey(); // == $rsa->createKey(1024) where 1024 is the key size
        echo "<pre>";
        echo $key['privatekey'];
        echo "</pre>";
        $private = Storage::disk('local')->put('private_key_gue.pem', $key['privatekey']);


        echo "<pre>";
        echo $key['publickey'];
        echo "</pre>";
        $private = Storage::disk('local')->put('public_key_gue.pem', $key['publickey']);
    }
}
