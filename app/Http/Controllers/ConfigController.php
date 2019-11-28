<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ConfigController extends Controller
{
    public function index()
    {
        // komentar
        $config['nama_opd']             = Config::where('parameter', 'nama_opd')->first();
        $config['alamat_jaringan']      = Config::where('parameter', 'alamat_jaringan')->first();
        $config['alamat_pos']           = Config::where('parameter', 'alamat_pos')->first();
        $config['email']                = Config::where('parameter', 'email')->first();
        $config['server_config_status'] = Config::where('parameter', 'server_config_status')->first();
        $config['versi_kunci']          = Config::where('parameter', 'versi_kunci')->first();
        if ($config['nama_opd'] && $config['alamat_jaringan'] && $config['alamat_pos'] && $config['email']) {
            return view('config.index', ['config' => $config]);
        } elseif (!$config['nama_opd'] && !$config['alamat_jaringan'] && !$config['alamat_pos'] && !$config['email']) {
            toast('Anda belum melakukan konfigurasi Server', 'warning');
            return redirect()->route('config.create');
        } else {
            toast('Konfigurasi server anda belum lengkap', 'warning');
            return redirect()->route('config.edit');
        }

    }

    public function create()
    {
        return view('config.create');
    }

    public function data()
    {
        $model = Config::all();

        return Datatables::of($model)
            ->addColumn('aksi', function ($model) {
                return '
                <button class="btn btn-icon btn-primary btn-sm" data-toggle="modal" data-target="#update" data-id="' . $model->id . '" data-nama_config="' . $model->nama_config . '" data-username="' . $model->username . '" data-password="' . $model->password . '" data-root_path="' . $model->root_path . '" data-host="' . $model->host . '"><i class="far fa-edit"></i></button>
                <button class="btn btn-icon btn-danger btn-sm delete" data-id="' . $model->id . '"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama_opd'   => 'required',
            'alamat_pos' => 'required',
            'email'      => 'required',
        ]);
        Config::create(['parameter' => 'nama_opd', 'value' => $request->nama_opd, 'descriptions' => 'Berisikan nama opd atau instansi']);
        Config::create(['parameter' => 'alamat_jaringan', 'value' => explode('/', url()->current())[2], 'descriptions' => 'Alamat server container beserta dengan Portnya']);
        Config::create(['parameter' => 'alamat_pos', 'value' => $request->alamat_pos, 'descriptions' => 'Alamat instansi yang dapat dihubungi via Pos']);
        Config::create(['parameter' => 'email', 'value' => $request->email, 'descriptions' => 'Alamat email instansi']);
        Config::create(['parameter' => 'server_config_status', 'value' => 'pending', 'descriptions' => 'Status Konfigurasi Server OPD [0: pending, 1: tervalidasi, 2:tertolak]']);

        $data = [
            'nama_opd'        => $request->nama_opd,
            'alamat_jaringan' => explode('/', url()->current())[2],
            'alamat_pos'      => $request->alamat_pos,
            'deskripsi'       => 'dari server',
        ];
        # Create a connection
        $ch = curl_init(config('custom.suandi_server') . 'opd');
        # Form data string
        $postString = http_build_query($data, '', '&');
        # Setting our options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Get the response
        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        Config::create(['parameter' => 'id_opd', 'value' => $response->id, 'descriptions' => 'ID dari OPD']);

        toast('Data berhasil ditambahkan', 'success');
        return redirect()->route('config');

    }

    public function edit()
    {
        $config['nama_opd']   = Config::where('parameter', 'nama_opd')->first();
        $config['alamat_pos'] = Config::where('parameter', 'alamat_pos')->first();
        $config['email']      = Config::where('parameter', 'email')->first();
        return view('config.edit', ['config' => $config]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        Config::where('parameter', 'nama_opd')->update(['value' => $request->nama_opd]);
        Config::where('parameter', 'alamat_jaringan')->update(['value' => explode('/', url()->current())[2]]);
        Config::where('parameter', 'alamat_pos')->update(['value' => $request->alamat_pos]);
        Config::where('parameter', 'email')->update(['value' => $request->email]);
        $id_opd = Config::where('parameter', 'id_opd')->first();

        $data = [
            'nama_opd'        => $request->nama_opd,
            'alamat_jaringan' => explode('/', url()->current())[2],
            'alamat_pos'      => $request->alamat_pos,
            'deskripsi'       => 'dari server',
        ];
        # Create a connection
        $ch = curl_init(config('custom.suandi_server') . 'opd/' . $id_opd->value);
        # Form data string
        $postString = http_build_query($data, '', '&');
        # Setting our options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Get the response
        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        toast('Data berhasil diedit', 'success');
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
        $host     = config('filesystems.disks.sftp.host');
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
