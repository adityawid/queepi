<?php

    $request = new HttpRequest();
    $request->setUrl('https://api.mainapi.net/smsotp/1.0.1/otp/123abc');
    $request->setMethod(HTTP_METH_PUT);

    $request->setHeaders(array(
      'postman-token' => 'f17f968b-fe44-a17f-ad4c-bd777bbf6cd2',
      'cache-control' => 'no-cache',
      'authorization' => 'Bearer b8380c09990545ae5872d67325cc2854',
      'accept' => 'application/json',
      'content-type' => 'application/json'
    ));

    $request->setBody('{
      "phoneNum": "085712342382",
      "digit": 4
    }');

    try {
      $response = $request->send();

      return json_encode($response->getBody());
    } catch (HttpException $ex) {
      return json_encode($ex);
    }
}
