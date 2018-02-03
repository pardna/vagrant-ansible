<?php
/**
* Pardna groups
*
*/
namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controllers\AppController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DirectDebitController extends AppController
{

    public function valid($data) {
      if(isset($data["account"]) && isset($data["sort_code"])) {
        return true;
      } return false;
    }

    public function save(Request $request)
    {
        $data = $request->request->all();
        $user = $this->getUser();
        if ($this->valid($data) == true){
          try {
            $this->service->create($data["account"], $data["sort_code"], $user);
            return new JsonResponse(array("message" => "Direct debit details saved"));
          } catch(\Exception $e) {
            throw new HttpException(409,"Cannot save direct debit details : " . $e->getMessage());
          }
        } else{
          throw new HttpException(403,"Direct debit input details invalid");
        }
    }



}
