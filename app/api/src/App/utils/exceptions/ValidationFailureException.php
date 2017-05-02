<?php
namespace App\utils\exceptions;
/**
 * custom exception class used to throw exception that we want to handled at some point
 */
class ValidationFailureException extends \Exception
{
    protected $httpResponseStatusEquivalentCode;

    public function getHttpResponseStatusEquivalentCode(){
      return $this->httpResponseStatusEquivalentCode;
    }

    public function setHttpResponseStatusEquivalentCode($httpResponseStatusEquivalentCode){
      $this->httpResponseStatusEquivalentCode = $httpResponseStatusEquivalentCode;
    }

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, $httpRespStatusEquivCode) {
        $this->httpResponseStatusEquivalentCode = $httpRespStatusEquivCode;
        // make sure everything is assigned properly
        parent::__construct($message, $code);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}
