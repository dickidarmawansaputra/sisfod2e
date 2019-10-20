<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $data = $request->all();
        if ($request->hasFile('gambar')) {
            $fileName = $request->gambar->getClientOriginalName();
            $path = Storage::disk('sftp')->put($fileName, fopen($request->gambar, 'r+'));
        }
        return 'berhasil';
    }
}
