<?php

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.mainapi.net/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_POST, 1);

    $headers = array();
    $headers[] = "Authorization: Basic OURXekJZUzN2d0dnbGZGUWR1ZlBwMW56N3FjYTpoaktnWW9hRWlYQU9UYW1TVmlyV3FleDV5UUFh";
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    } else{
      $generate=json_decode($result, true);
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $code = json_decode($httpcode, true);
    }
    //end of generate

    $milliseconds = round(microtime(true) * 1000);
    $key_request = md5($request->no_hp.$milliseconds);
    $digit = 4;
    $array_respons = array();

    if (!$code==200) {
      array_push($array_respons, ["status" => false]);
    }else{
      $curl = curl_init();
      $token = $generate['access_token'];
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mainapi.net/smsotp/1.0.1/otp/". $key_request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => "{ \"phoneNum\": \"".$request->no_hp."\",  \"digit\": ".$digit."}",
            CURLOPT_HTTPHEADER => array(
              "accept: application/json",
              "authorization: Bearer ".$token,
              "cache-control: no-cache",
              "content-type: application/json"
            ),
          ));

        $response = curl_exec($curl);
        $status_response = json_decode($response, true);
        // $cek = $status_response['net']['mainapi']['fault']['message'];
        $err = curl_error($curl);
        $httpcodesend = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($err) {
          array_push($array_respons, ["sukses" => false]);
          array_push($array_respons, ["key_request" => ""]);
        }else {
           array_push($array_respons, ["sukses" => true]);
           array_push($array_respons, ["key_request" => $key_request]);
        }
        return json_decode(json_encode($array_respons),true);
      }
?>
