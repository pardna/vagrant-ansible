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

class AccountController extends AppController
{

    public function deposit(Request $request)
    {

        try {
          $data = $request->request->all();
          $user = $this->getUser();
          $this->service->deposit($user, $data);
          return new JsonResponse(array("message" => "Amount deposited"));
        } catch(\Exception $e) {
          throw new HttpException(409,"Cannot save group account : " . $e->getMessage());
        }

    }


}
