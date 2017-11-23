<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poli;
use DB;

class APIPoliController extends Controller
{
    public function store(Request $request){
      DB::beginTransaction();
      try{
          $poli = new Poli();
          $poli->nama_poli = $request->nama_poli;
          $poli->save();

          DB::commit();
          return response()->json(['sukses'=>true,'pesan'=>'Berhasil Menambahkan Poli']);
      }
      catch(\Exception $e){
          return response()->json(['sukses'=>false,'pesan'=>$e]);
      }
    }
}
