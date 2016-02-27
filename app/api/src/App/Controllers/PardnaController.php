<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use App\utils\exceptions\PardnaApiException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PardnaController
{
  protected $pardnaSetupService;

  protected $pardnaManageService;

  protected $user;

  public function __construct($pardnaSetupService, $pardnaManageService)
  {
      $this->pardnaSetupService = $pardnaSetupService;
      $this->pardnaManageService = $pardnaManageService;
  }

  public function setUser($user) {
    $this->user = $user;
  }

  public function getUser() {
    return $this->user;
  }

  public function setUpPardna(Request $request){
    $pardnagroupid = $request->request->get("pardnagroup_id");
    $pardna = $this->getPardnaDetailsDataFromRequest($request);
    return new JsonResponse(array("pardna_id" => $this->pardnaSetupService->setUpPardna($pardnagroupid, $pardna)));
  }

  private function getPardnaDetailsDataFromRequest(Request $request)
  {
      return $request->request->get("pardna");
  }

}
