<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use JWTException;
use App\User;
use App\Check_up;
use App\Dokter;
use App\Poli;
use App\Jadwal;
use DB;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class APICheckUpController extends Controller
{
  protected $JWTAuth;
  public function __construct(JWTAuth $JWTAuth)
  {
      $this->jwtAuth = $JWTAuth;
  }

  public function store(Request $request){
      $pasien = $this->jwtAuth->parseToken()->authenticate();
      DB::beginTransaction();
      try{
          $tgl_check_up = \Carbon\Carbon::parse($request->tgl_check_up)->format('Y-m-d');
          $data = new Check_up();
          $data->tgl_check_up = $tgl_check_up;
          $data->pasien = $pasien->id;
          $data->dokter = $request->id_dokter;
          $data->jadwal = $request->id_jadwal;
          $data->no_antrian = $this->getAntrian($tgl_check_up, $request->id_dokter, $request->id_jadwal);
          $data->save();

          DB::commit();
          return response()->json(['sukses'=>true,'pesan'=>'Berhasil Daftar Check Up']);
      }
      catch(\Exception $e){
          DB::rollBack();
          return response()->json(['sukses'=>false,'pesan'=>$e]);
      }

  }

  public function getAntrian($tgl_check_up, $id_dokter, $id_jadwal){
        $check_no_antrian = Check_up::where('tgl_check_up','=',$tgl_check_up)
                              ->where('dokter','=',$id_dokter)
                              ->get();
        if(count($check_no_antrian)>0){
          $check = Check_up::where('tgl_check_up','=',$tgl_check_up)
                                ->where('dokter','=',$id_dokter)
                                ->where('jadwal','=',$id_jadwal)
                                ->orderBy('created_at')
                                ->max('no_antrian');
          $no_antrian = $check + 1;
        }
        else{
          $no_antrian = 1;
        }

        return $no_antrian;
  }

  public function getCheckUp(){
        $pasien = $this->jwtAuth->parseToken()->authenticate();
        $getCheckUp = Check_up::where('pasien','=',$pasien->id)
                                ->orderBy('tgl_check_up')
                                ->get();
        if(count($getCheckUp)>0){
            $i = 0;
            foreach($getCheckUp as $checkUp){
                  $getDokter[$i] = Dokter::where('id','=',$checkUp->dokter)->first();
                  $getPoli[$i] = Poli::where('id','=',$getDokter[$i]->id_poli)->first();
                  $getJadwal[$i] = Jadwal::where('id','=',$checkUp->jadwal)
                                    ->first();
                  $data_check_up[$i]=array(
                      'id'=> $checkUp->id,
                      'tgl_check_up'=>$checkUp->tgl_check_up,
                      'dokter'=>array(
                          'id'=>$getDokter[$i]->id,
                          'nama'=>$getDokter[$i]->nama,
                      ),
                      'poli'=>array(
                          'id'=>$getPoli[$i]->id,
                          'nama'=>$getPoli[$i]->nama_poli,
                      ),
                      'jadwal'=>array(
                          'id'=>$getJadwal[$i]->id,
                          'hari'=>$getJadwal[$i]->hari,
                          'jam'=>$getJadwal[$i]->jam,
                      ),
                      'no_antrian'=>$checkUp->no_antrian,
                  );
                  $i++;
            }
        }
        else{
            $data_check_up = array();
        }

        $data = array(
            'id'=>$pasien->id,
            'nama'=>$pasien->nama,
            'check_up'=>$data_check_up
        );

        return response()->json(['sukses'=>true, 'pesan'=>'Berhasil Mendapatkan Data','data'=>$data]);
  }

}
