<?php
namespace App\utils;
use App\Services\common\BaseService;

class GoCardlessProAPIUtils
{

  public function getAPIResponse($response){
    return $response['api_response'];
  }

  public function getResponseBaseContents($response, $content){
    $api_response = $this->getAPIResponse($response);
    return $api_response[$content];
  }

  public function isValidResponseStatusCode($status_code){
    if (is_int($status_code)){
      if ($status_code >= 200 && $status_code < 400){
        return true;
      } else if ($status_code >= 500){
        return false;
      } else{
        return false;
      }
    }
  }

}
