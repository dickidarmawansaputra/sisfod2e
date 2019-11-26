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

class SuratController extends Controller
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
            $info = pathinfo($request->gambar->getClientOriginalName());

            // Simpan gambar di storage
            $fileName = $request->gambar->getClientOriginalName();
            $path = $request->file('gambar')->storeAs($random_name, $random_name.'.'.$info['extension']);
            $data['gambar'] = $path;
        }
        
        $server_tujuan = json_decode(file_get_contents(config('custom.suandi_server').'opd/'.$request->tujuan));
        $data['tujuan'] = $server_tujuan->nama_opd;
        $data['pengirim'] = Config::where('parameter','nama_opd')->first()->value;


        $publickey = Storage::disk('ftp')->get($server_tujuan->nama_kunci_public);

        // RSA
        $rsa = new \phpseclib\Crypt\RSA();



        // // Enkripsi RSA dengan private key dan fingerprint
        $rsa->loadKey($publickey);
        $digital_signature = $rsa->encrypt($fingerprint);
        $file_digital_sign = Storage::disk('local')->put($random_name.'/maris.dig', $digital_signature);


        // echo storage_path();

        // Proses zip file
        $zipper = new \Chumper\Zipper\Zipper;
        $files = glob(storage_path('app/'.$random_name.'/*'));
        $hasil = $zipper->make(storage_path('app/surat/'.$random_name.'.letter'))->add($files);
        $zipper->close();

   

        // Dapatkan zip file baru dikirim

        $filezip = Storage::url('app/'.$random_name.'.letter');

        if ($filezip) {
            $path = Storage::disk('ftp')->put(basename($filezip), fopen(storage_path('app/surat/'.$random_name.'.letter'), 'r+'));
        }

        // masukkan ke database lokal
    	Surat::create($data);

        // kirim pesan ke database tujuan

        $ch = curl_init(config('custom.suandi_server').'surat/'.$request->tujuan);
        # Form data string
        $postString = http_build_query($data, '', '&');
        # Setting our options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Get the response
        $response = curl_exec($ch);
        curl_close($ch);
        
        toast('Data berhasil ditambahkan','success');
        return redirect()->route('surat-masuk');
        
        
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
                

                <button class="btn btn-icon btn-success btn-sm" data-toggle="modal" data-target="#unduh" data-id="'.$model->id.'"><i class="fas fa-download" desabled></i></button>
                
                <button class="btn btn-icon btn-primary btn-sm" data-toggle="modal" data-target="#detail"  data-id="'.$model->id.'" data-no_surat="'.$model->no_surat.'" data-perihal_surat="'.$model->perihal_surat.'" data-jenis_surat="'.$model->jenis_surat.'" data-deskripsi="'.$model->deskripsi.'" data-pengirim="'.$model->pengirim.'" data-tujuan="'.$model->tujuan.'"><i class="fas fa-eye"></i></button>';
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

    public function ambilSurat(Request $request)
    {
        // $message['header'] = "Apa kabarmu";
        // $message['body'] = "Ibu, semoga kelak kita bertemu kembali di surga Allah";
        $message['status'] = "Sukses";
        $message['no_surat'] = $request->no_surat;
        $message['perihal_surat'] = $request->perihal_surat;
        $message['tgl_surat'] = $request->tgl_surat;
        $message['pengirim'] = $request->pengirim;
        $message['tujuan'] = $request->tujuan;
        $message['jenis_surat'] = $request->jenis_surat;
        $message['deskripsi'] = $request->deskripsi;
        $message['gambar'] = $request->gambar;
        $data = $request->all();
        Surat::create($data);

        return response()->json($message);
    }



}
