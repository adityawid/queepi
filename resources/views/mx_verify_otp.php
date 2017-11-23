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
    $generate=json_decode($result, true);//access_token
  }
  //end of generate

$key_request = $request->key_request;
$strOTP = $request->strOTP;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.mainapi.net/smsotp/1.0.1/otp/".$key_request."/verifications",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"otpstr\": \"".$strOTP."\",  \"digit\": 4}",
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "authorization: Bearer ". $generate['access_token'],
    "cache-control: no-cache",
    "content-type: application/json"
  ),
));
  $array_respons = array();
  $response = curl_exec($curl);
  $status_response = json_decode($response,true);
  $err = curl_error($curl);
  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $code = json_decode($httpcode, true);//200
  curl_close($curl);

  if($status_response['status']==false){
      array_push($array_respons, ["sukses" => false]);
  }
  else{
      array_push($array_respons, ["sukses" => true]);
  }

  return json_decode(json_encode($array_respons),true);

?>
