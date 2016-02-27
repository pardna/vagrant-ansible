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

class PardnaGroupController extends AppController
{

    public function save(Request $request)
    {

        try {
          $data = $request->request->all();
          $user = $this->getUser();
          $this->service->saveGroupAndEmails($data, $user);
          return new JsonResponse(array("message" => "Group saved"));
        } catch(\Exception $e) {
          throw new HttpException(409,"Cannot save group account : " . $e->getMessage());
        }

    }

    public function read(Request $request)
    {

        try {
          $data = $request->request->all();
          $user = $this->getUser();
          $groups = $this->service->findByMemberId($user->getId());
          return new JsonResponse($groups);
        } catch(\Exception $e) {
          throw new HttpException(409,"Error getting list : " . $e->getMessage());
        }

    }

    public function details($id)
    {

        try {
          // $data = $request->request->all();
          $user = $this->getUser();
          $group = $this->service->details($id);
          return new JsonResponse($group);
        } catch(\Exception $e) {
          throw new HttpException(409,"Error getting list : " . $e->getMessage());
        }

    }

    public function addMember(Request $request)
    {

        try {
          $data = $request->request->all();
          $user = $this->getUser();
          $this->service->saveGroupAndEmails($data, $user);
          return new JsonResponse(array("message" => "Group saved"));
        } catch(\Exception $e) {
          throw new HttpException(409,"Cannot save group account : " . $e->getMessage());
        }

    }

}
