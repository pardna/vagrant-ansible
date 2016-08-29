<?php

require __DIR__ . '/utils.php';
require __DIR__ . '/Client.php';

$secret = getWebhookEndPointSecret();

$apiBaseUrl = getAPIBaseUrl();

$request_headers = getRequestHeaders();

$webhook_signature = $request_headers['Webhook-Signature'];

$request_body = getRequestBody();

$request_body_string = json_encode($request_body);

$events = $request_body['events'];

$signature_correct = (boolean) exec("ruby ./signature.rb $webhook_signature $request_body_string $secret");

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
