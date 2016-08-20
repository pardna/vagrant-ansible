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
    protected $pardnaGroupStatusService;

    public function setPardnaGroupStatusService($pardnaGroupStatusService)
    {
      $this->pardnaGroupStatusService = $pardnaGroupStatusService;
    }

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
          foreach ($groups as $key => $value) {
            $statusAndReason = $this->pardnaGroupStatusService->getUserRelatedGroupStatus($user, $groups[$key]["id"]);
            $groups[$key]["status"] = $statusAndReason["status"];
            $groups[$key]["reason"] = $statusAndReason["reason"];
          }
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

    public function slots($id)
    {

        try {
          $slots = $this->service->getGroupSlots($id);
          return new JsonResponse($slots);
        } catch(\Exception $e) {
          throw new HttpException(409,"Error getting slots : " . $e->getMessage());
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
