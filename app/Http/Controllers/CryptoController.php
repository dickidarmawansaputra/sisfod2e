<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpseclib\Crypt\RSA;

class CryptoController extends Controller
{

    public function index()
    {
        // return view('crypto.index');
        echo storage_path('test');
    }

    public function createKey()
    {
        $rsa = new RSA();
        $key = $rsa->createKey();

        $config['id_opd'] = Config::where('parameter', 'id_opd')->first();

        $randomstring = md5($config['id_opd']->value + 3) . Str::random(32);

        $private = Storage::disk('local')->put('private_key.pem', $key['privatekey']);
        $public  = Storage::disk('local')->put('public_key.pem', $key['publickey']);
        $path    = Storage::disk('ftp')->put($randomstring . '.pem', Storage::disk('local')->get('public_key.pem'));

        $config['nama_opd']        = Config::where('parameter', 'nama_opd')->first();
        $config['alamat_jaringan'] = Config::where('parameter', 'alamat_jaringan')->first();
        $config['alamat_pos']      = Config::where('parameter', 'alamat_pos')->first();
        $config['email']           = Config::where('parameter', 'email')->first();

        $data = [
            'nama_opd'          => $config['nama_opd']->value,
            'alamat_jaringan'   => $config['alamat_jaringan']->value,
            'alamat_pos'        => $config['alamat_pos']->value,
            'deskripsi'         => 'dari server',
            'nama_kunci_public' => $randomstring . '.pem',
        ];

        # Create a connection
        $ch = curl_init(config('custom.suandi_server') . 'opd/' . $config['id_opd']->value);
        # Form data string
        $postString = http_build_query($data, '', '&');
        # Setting our options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Get the response
        $response = curl_exec($ch);
        curl_close($ch);

        $config['versi_kunci'] = Config::where('parameter', 'versi_kunci')->first();

        if ($config['versi_kunci']) {
            $config['versi_kunci']->value = $randomstring;
            $config['versi_kunci']->save();
        } else {
            Config::create(['parameter' => 'versi_kunci', 'value' => $randomstring, 'descriptions' => 'Berisikan identias versi kunci']);
        }

        $json_response = json_decode($response);
        toast('Kunci Berhasil dibuat ', 'success');
        return redirect()->back();
    }

    public function encrypt(Request $request)
    {
        $rsa = new RSA();
        // Get Key .pem
        $public_key = Storage::disk('local')->get('public_key.pem');
        $rsa->loadKey($private_key);
        $enkripsi_rsa = $rsa->encrypt($fingerprint);
    }

    public function decrypt(Request $request)
    {
        $rsa = new RSA();
        // Get Key .pem
        $private_key = Storage::disk('local')->get('public/surat/' . $fingerprint . '/private_key.pem');
        $public_key  = Storage::disk('local')->get('public/surat/' . $fingerprint . '/public_key.pem');
        $rsa->loadKey($private_key);
        $enkripsi_rsa = $rsa->encrypt($fingerprint);
    }

}
