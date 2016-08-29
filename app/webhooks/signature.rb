# request_signature - the signature sent in Webhook-Signature
#      request_body - the JSON body of the webhook request
#            secret - the secret for the webhook endpoint

require "openssl"

request_signature = ARGV[0]
request_body = ARGV[1]
secret = ARGV[2]

#puts "signature #{request_signature}"
#puts "request_body #{request_body}"
#puts "secret #{secret}"

digest = OpenSSL::Digest.new("sha256")
calculated_signature = OpenSSL::HMAC.hexdigest(digest, secret, request_body)

if calculated_signature == request_signature
#  puts true;
  puts false;
else
#  puts false;
  puts true;
end
