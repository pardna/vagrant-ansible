<?php
namespace App\Entity;

class RedirectFlowEntity
{

  public $created_at;

  public $description;

  public $id;

  public $links;

  public $redirect_url;

  public $scheme;

  public $session_token;

  public $success_redirect_url;

  public function setCreated_at($created_at)
  {
    $this->created_at = $created_at;
  }

  public function getCreated_at()
  {
    return $this->created_at;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setLinks($links)
  {
    $this->links = $links;
  }

  public function getLinks()
  {
    return $this->links;
  }

  public function setRedirect_url($redirect_url)
  {
    $this->redirect_url = $redirect_url;
  }

  public function getRedirect_url()
  {
    return $this->redirect_url;
  }

  public function setScheme($scheme)
  {
    $this->scheme = $scheme;
  }

  public function getScheme()
  {
    return $this->scheme;
  }

  public function setSession_token($session_token)
  {
    $this->session_token = $session_token;
  }

  public function getSession_token()
  {
    return $this->session_token;
  }

  public function setSuccess_redirect_url($success_redirect_url)
  {
    $this->success_redirect_url = $success_redirect_url;
  }

  public function getSuccess_redirect_url()
  {
    return $this->success_redirect_url;
  }

}
