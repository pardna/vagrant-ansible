<?php
$secret = 'HYANE-HTB-12002';
$string = '{
  "events": [
    {
      "id": "EV0009YM3JNZG0",
      "created_at": "2016-09-02T10:01:36.732Z",
      "resource_type": "payments",
      "action": "confirmed",
      "links": {
        "payment": "PM00026SZVSP7F"
      },
      "details": {
        "origin": "gocardless",
        "cause": "payment_confirmed",
        "description": "Enough time has passed since the payment was submitted for the banks to return an error, so this payment is now confirmed."
      },
      "metadata": {}
    }
  ]
}';
$expected_signature = 'bc24672ddb67073666d0f40cf5f0f71ccedc20c9afbe819e39112a5ec5e8ad3b';

$signature = trim(hash_hmac('sha256', $string, $secret));

echo $expected_signature . "\n";
echo $signature . "\n";

