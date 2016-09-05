<?php

require __DIR__ . '/utils.php';
require __DIR__ . '/Client.php';

$secret = getWebhookEndPointSecret();

$apiBaseUrl = getAPIBaseUrl();

$request_headers = getRequestHeaders();

$webhook_signature = $request_headers['Webhook-Signature'];

$request_body = getRequestBody();

$request_body_array = json_decode($request_body, true);

$events = $request_body_array['events'];

$signature_correct = (boolean) isSignatureCorrect($webhook_signature, $request_body, $secret);

if ($signature_correct){
  foreach ($events as $event) {
    $event_id = $event['id'];
    $url = $apiBaseUrl . "/payments/events/process/" . $event_id;
    $result = callAPI("POST", $url);
    handleResponse($result);
  }
} else{
  http_response_code(401);
}
