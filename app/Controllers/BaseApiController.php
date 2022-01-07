<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use \Firebase\JWT\JWT;

use CodeIgniter\API\ResponseTrait;

class BaseApiController extends BaseController
{
    use ResponseTrait;
    protected $format = 'json';
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

        } catch (\Exception $ex) {
            $rst = false;
        } catch (\Error $ex) {
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
