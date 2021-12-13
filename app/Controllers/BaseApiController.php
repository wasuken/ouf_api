<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class BaseApiController extends ResourceController
{
  protected $db;
  protected $format = 'json';
  public function __construct()
  {
    $this->db = \Config\Database::connect();
  }
  protected function getKey()
  {
    return getenv('app.jwt.key');
  }
  protected function _auth($token)
  {
    $key = $this->getKey();

    $rst = false;

    try {
      $rst = JWT::decode($token, $key, array("HS256"));

    } catch (Exception $ex) {
      $rst = false;
    }
    return $rst;
  }
  protected function _login($email, $password)
  {
    $userModel = new UserModel();

    $userdata = $userModel
      ->where("email", $email)
      ->first();

    if(!empty($userdata) &&
      (password_verify($password, $userdata['password_hash']))){
      return $userdata;
    }else{
      return false;
    }
  }
}
