<?php

//require_once __DIR__.'/../api/src/resources/config/prod.php';

function getWebhookEndPointSecret(){
  //$webhook_endpoint_secret = $app['gocardless_env']['webhook_endpoint_secret'];
  //return $webhook_endpoint_secret;
  return "HYANE-HTB-12002";
}

function getAPIBaseUrl(){
  $string = file_get_contents("../frontend/src/config/dev.json");
  $config = json_decode($string, true);
  return $config["apiUrl"];
}

function getRequestHeaders(){
  $request_headers = array();
  foreach (getallheaders() as $name => $value) {
       $request_headers[$name] = $value;
  }
  return $request_headers;
}

function getRequestBody(){
  $body = '';
  $fh   = @fopen('php://input', 'r');
  if ($fh)
  {
    while (!feof($fh))
    {
      $s = fread($fh, 1024);
      if (is_string($s))
      {
        $body .= $s;
      }
    }
    fclose($fh);
  }
  //print("-------------- PHP Input Stream ----------------\n$body\n\n");

  return json_decode($body, true);
}

function handleResponse($response){
  $res = json_decode($response, true);
  if ($res){
    if (array_key_exists('statusCode', $res) && $res['statusCode'] != 200){
      http_response_code($res['statusCode']);
      print_r($res['message']);
    } else{
      http_response_code(204);
    }
  }
}
