<?php

namespace App\Http\Controllers;

use App\Dokters;
use Illuminate\Http\Request;
use App\Dokter;
use App\Jadwal;
use App\Poli;
use Tymon\JWTAuth\JWTAuth;
use JWTException;
use DB;

class APIDoktersController extends Controller
{
  protected $JWTAuth;
    public function __construct(JWTAuth $JWTAuth)
    {
        $this->jwtAuth = $JWTAuth;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
          $cek = Dokter::where('email','=',$request->email)
                        ->where('no_hp','=',$request->no_hp)
                        ->first();
          if(empty($cek)){
              $dokter = new Dokter();
              $dokter->nama = $request->nama;
              $dokter->id_poli = $request->id_poli;
              $dokter->email = $request->email;
              $dokter->no_hp = $request->no_hp;
              $dokter->save();
          }
          else{
            Dokter::where('email','=',$request->email)
                  ->where('no_hp','=',$request->no_hp)
                  ->update(['email'=>$request->email,'no_hp'=>$request->no_hp,'
                          id_poli'=>$request->id_poli]);
          }

          DB::commit();
          return response()->json(['sukses'=>true,'pesan'=>'Berhasil Menambahkan Data Dokter']);

        }catch(\Exception $e){
          return response()->json(['sukses'=>false,'pesan'=> $e]);
        }
    }

    public function jadwal(Request $request){
        DB::beginTransaction();
        try{
          $jadwal = new Jadwal();
          $jadwal->hari = $request->hari;
          $jadwal->jam  = $request->jam;
          $jadwal->dokter = $request->id_dokter;
          $jadwal->save();
          DB::commit();
          return response()->json(['sukses'=>true,'pesan'=>'Berhasil Menambahkan Data Jadwal']);
        }
        catch(\Exception $e){
          return response()->json(['sukses'=>false,'pesan'=> $e]);
        }
    }


    public function getData(){
      $pasien = $this->jwtAuth->parseToken()->authenticate();

      try{
          $polis = Poli::all();
          $i=0;
          foreach($polis as $poli){
              $dokters[$i] = Dokter::where('id_poli','=',$poli->id)->get();
              if(count($dokters)>0){
                $j = 0;
                foreach($dokters[$i] as $dokter){
                      $jadwals[$j] = Jadwal::where('dokter','=', $dokter->id)->get();
                      $z = 0;
                      if(count($jadwals)>0){
                          foreach($jadwals[$j] as $jadwal){
                            $data_jadwals[$z] = array(
                                'id'=>$jadwal->id,
                                'hari'=> $jadwal->hari,
                                'jam' => $jadwal->jam,
                            );
                            $z++;
                          }
                      }
                      else{
                          $data_jadwals = array();
                      }
                      $data_dokters[$j] = array(
                          'id'=>$dokter->id,
                          'nama'=>$dokter->nama,
                          'email'=>$dokter->email,
                          'no_hp'=>$dokter->no_hp,
                          'jadwal'=> $data_jadwals
                      );
                      $j++;
                }
              }
              else{
                  $data_dokters = array();
              }

              $data_poli[$i] = array(
                  'id'=> $poli->id,
                  'nama_poli'=> $poli->nama_poli,
                  'dokters'=>$data_dokters
              );
              $i++;
          }
          $data = array(
              'id'=>$pasien->id,
              'nama'=>$pasien->nama,
              'data'=>$data_poli
          );

          return response()->json(['sukses'=>true,'pesan'=>'Berhasil Mendapatkan Data','data'=>$data]);
      }
      catch(\Exception $e){
          dd($e);
          return response()->json(['sukses'=>false,'pesan'=> $e]);
      }
    }
}
