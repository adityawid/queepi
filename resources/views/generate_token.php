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
      json_decode($result, true);
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      return json_decode($httpcode, true);
    }
 ?>
