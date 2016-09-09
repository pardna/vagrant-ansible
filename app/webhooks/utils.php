<?php

//require_once __DIR__.'/../api/src/resources/config/prod.php';

function getWebhookEndPointSecret(){
  //$webhook_endpoint_secret = $app['gocardless_env']['webhook_endpoint_secret'];
  //return $webhook_endpoint_secret;
  return "HOOK-SIGNATURE-001";
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
  return file_get_contents('php://input');
}

function isSignatureCorrect($request_signature, $request_body, $secret){
  $generated_signature = hash_hmac('sha256', $request_body, $secret);
  if($generated_signature == $request_signature){
    return true;
  } else{
    return false;
  }
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
