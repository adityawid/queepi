<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use JWTException;
use App\User;
use Ixudra\Curl\Facades\Curl;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class APIAuthController extends Controller
{
  protected $JWTAuth;
  public function __construct(JWTAuth $JWTAuth)
  {
      $this->jwtAuth = $JWTAuth;
  }

  public function authenticate(Request $request)
  {
      // grab credentials from the request
      $credentials = $request->only('no_hp','password');

      try {
          // attempt to verify the credentials and create a token for the user
          if (! $token = JWTAuth::attempt($credentials)) {
              return response()->json(['error' => 'invalid_credentials'], 401);
          }
      } catch (JWTException $e) {
          // something went wrong whilst attempting to encode the token
          return response()->json(['error' => 'could_not_create_token'], 500);
      } catch(\TokenExpiredException $e){
          $refreshedToken = jwtAuth::refresh($token);
          return response()->json(['sukses'=>true,'pesan'=>'Berhasil Refresh Token','token'=>$refreshedToken]);
      }

      // all good so return the token
      return response()->json(['sukses'=>true,'pesan'=>'Berhasil Login','token'=>$token]);
  }
  public function register(Request $request){
      $credentials = $request->only('nama','email','no_hp','tgl_lahir','password');
      $pasien = User::where('email','=',$request->email)->where('no_hp','=',$request->no_hp)->first();
      if(empty($pasien)){
            $pasien = new User();
            $pasien->nama = $request->nama;
            $pasien->email = $request->email;
            $pasien->no_hp = $request->no_hp;
            $pasien->tgl_lahir = \Carbon\Carbon::parse($request->tgl_lahir)->format('Y-m-d');
            $pasien->password = bcrypt($request->password);
            $pasien->save();
            return response()->json(['sukses'=>true, 'pesan'=>'Berhasil Create User']);
      }
      else{
        return response()->json(['sukses'=>true, 'pesan'=>'Berhasil']);
      }
  }

  public function test(){
    try {

        if (! $user = JWTAuth::parseToken()->authenticate()) {
          return response()->json(['user_not_found'], 404);
        }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

        return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

        return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

        return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
  }

    public function send(Request $request){
        return require_once base_path('resources/views').'/mx_send_otp.php';
    }

    public function verify(Request $request){
        return require_once base_path('resources/views').'/mx_verify_otp.php';
    }

    public function generate(Request $request){
        return require_once base_path('resources/views').'/generate_token.php';
    }
}
