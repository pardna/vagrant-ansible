<?php
/**
* Pardna groups
*
*/
namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use App\utils\exceptions\PardnaApiException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GroupsController
{
    protected $groupsSetupService;

    protected $groupsManageService;

    protected $user;

    protected $userService;

    protected $pardnaSetupService;

    public function __construct($setUpService, $manageService)
    {
        $this->groupsSetupService = $setUpService;
        $this->groupsManageService = $manageService;
    }

    public function setUserService($userService){
      $this->userService = $userService;
    }

    public function getUserService(){
      return $this->userService;
    }

    public function setPardnaSetupService($pardnaSetupService){
      $this->pardnaSetupService = $pardnaSetupService;
    }

    public function getPardnaSetupService(){
      return $this->pardnaSetupService;
    }

    public function setUser($user) {
      $this->user = $user;
    }

    public function getUser() {
      return $this->user;
    }

    public function getAll()
    {
        return new JsonResponse($this->groupsService->getAll());
    }

    public function setupGroupAndPardna(Request $request){
      $pardnagroup = $this->setupGroup($request);
      if ($pardnagroup){
        $pardnagroupid = (array) json_decode($pardnagroup->getContent());
        $pardna = $this->getPardnaDetailsDataFromRequest($request);
        $pardna_id = $this->pardnaSetupService->setUpPardna($pardnagroupid["id"], $pardna);
        return new JsonResponse(
          array(
            "group_id" => $pardnagroupid["id"],
            "pardna_id" => $pardna_id
          )
        );
      }
    }

    public function setupGroup(Request $request)
    {
      //Get data from request
      $dataFromReq = $this->getGroupDetailsDataFromRequest($request);
      $pardnagroup = array(
        "name" => $dataFromReq["name"]
      );

      try{
        //Step 1. Create an entry for the group in Pardna groups Table
        $pardnagroup_id = $this->groupsSetupService->createPardnaGroup($pardnagroup);

        //Step 2. For every Pardna.com member as well as creator added to the group, add to the Pardna groups member Table
        $pardnagroup["members"] = $dataFromReq["subscriber_invites"];
        $pardnagroup["created_by"] = $this->user->getMembershipNumber();
        $this->groupsSetupService->addMembersToPardnaGroup($pardnagroup_id, $pardnagroup);

        //Step 3. For every email invitees, add to pardnagroup_members_invites
        $pardnagroup_emailinvites = $dataFromReq["email_invites"];
        $this->groupsSetupService->addEmailInviteesToTable($pardnagroup_id, $pardnagroup_emailinvites);

        //Step 4. Send Response back to client
        return new JsonResponse(array("id" => $pardnagroup_id));
      } catch (PardnaApiException $paexp){
        $httpStatusErrorCode = $paexp->getHttpResponseStatusEquivalentCode();
        if ($httpStatusErrorCode){
          throw new HttpException($httpStatusErrorCode, $paexp->getMessage());
        }
      }
    }

    public function addMembers($id, Request $request)
    {
      //Get Data from request
      $dataFromReq = $this->getGroupDetailsDataFromRequest($request);

      try {
        //Step 1. Validate the id of the pardna group by getting the group details. This will throw excpetion if group is not found
        $pardnagroup = $this->groupsManageService->getPardnaGroup($id);

        //Step 2. If group details exist, For every Pardna.com member as well as creator added to the group, add to the Pardna groups member Table
        if ($pardnagroup){
          $groupMembersToAdd["members"] = $dataFromReq["subscriber_invites"];
          $this->groupsSetupService->addMembersToPardnaGroup($pardnagroup["id"], $groupMembersToAdd);

          //Step 3. For every email invitees, add to pardnagroup_members_invites
          $pardnagroup_emailinvites = $dataFromReq["email_invites"];
          $this->groupsSetupService->addEmailInviteesToTable($pardnagroup["id"], $pardnagroup_emailinvites);

          //Step 4. Send Response back to client
          return new JsonResponse(array("id" => $pardnagroup["id"]));
        }
      } catch (PardnaApiException $paexp){
        $httpStatusErrorCode = $paexp->getHttpResponseStatusEquivalentCode();
        if ($httpStatusErrorCode){
          throw new HttpException($httpStatusErrorCode, $paexp->getMessage());
        }
      }
    }

    public function getUserPardnaGroups(){
      $user_membernumber = $this->user->getMembershipNumber();
      $pardnagroups = $this->groupsManageService->getPardnagroupsAssocWithUser($user_membernumber);
      $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
      $jsonObject = $serializer->serialize($pardnagroups, 'json');
      return new JsonResponse(json_decode($jsonObject));
    }

    public function getPardnagroupDetails($id, Request $request){
      try {
        //Step 1. Validate the id of the pardna group by getting the group details. This will throw excpetion if group is not found
        $pardnagroup = $this->groupsManageService->getPardnaGroup($id);

        if ($pardnagroup){
          //Step 2. If group exist, getPardnaGroupDetails
          $pardnagroupdetails = $this->groupsManageService->getPardnaGroupDetails($pardnagroup);
          //Step 3. Get user details for Pardna group Members
          $this->userService->getUserDetailsForMembers($pardnagroupdetails);
          //Step 4. Send Response back to client
          $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
          $jsonObject = $serializer->serialize($pardnagroupdetails, 'json');
          return new JsonResponse(json_decode($jsonObject));
        }
      } catch (PardnaApiException $paexp){
        $httpStatusErrorCode = $paexp->getHttpResponseStatusEquivalentCode();
        if ($httpStatusErrorCode){
          throw new HttpException($httpStatusErrorCode, $paexp->getMessage());
        }
      }
    }

    private function getPardnaDetailsDataFromRequest(Request $request)
    {
        return $request->request->get("pardna");
    }

    private function getGroupDetailsDataFromRequest(Request $request)
    {
        return $request->request->get("group_details");
    }
}
