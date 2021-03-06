<?php

namespace App\Controllers;

use App\Models\UserModel;
use \Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
  private $db;
  protected $format = 'json';
  public function __construct()
  {
    $this->db = \Config\Database::connect();
  }
  public function register()
  {
    $rules = [
      "name" => "required",
      "email" => "required|valid_email|is_unique[users.email]|min_length[6]",
      "password" => "required",
    ];

    $messages = [
      "name" => [
        "required" => "Name is required"
      ],
      "email" => [
        "required" => "Email required",
        "valid_email" => "Email address is not in format"
      ],
      "password" => [
        "required" => "password is required"
      ],
    ];

    if (!$this->validate($rules, $messages)) {

      $response = [
        'status' => 500,
        'error' => true,
        'message' => $this->validator->getErrors(),
        'data' => []
      ];
    } else {

      $userModel = new UserModel();

      $data = [
        "id" => uniqid(),
        "display_name" => $this->request->getVar("name"),
        "email" => $this->request->getVar("email"),
        "password_hash" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
      ];

      $rst = $userModel->insert($data);
      log_message('debug', $rst);

      if($rst) {

        $response = [
          'status' => 200,
          "error" => false,
          'messages' => 'Successfully, user has been registered',
          'data' => []
        ];
      } else {

        $response = [
          'status' => 500,
          "error" => true,
          'messages' => 'Failed to create user',
          'data' => []
        ];
      }
    }

    return $this->respondCreated($response);
  }

  private function getKey()
  {
    return getenv('app.jwt.key');
  }

  public function login()
  {
    $rules = [
      "email" => "required|valid_email|min_length[6]",
      "password" => "required",
    ];

    $messages = [
      "email" => [
        "required" => "Email required",
        "valid_email" => "Email address is not in format"
      ],
      "password" => [
        "required" => "password is required"
      ],
    ];

    if (!$this->validate($rules, $messages)) {

      $response = [
        'status' => 500,
        'error' => true,
        'message' => $this->validator->getErrors(),
        'data' => []
      ];

      return $this->respondCreated($response);

    } else {
      $userModel = new UserModel();

      $userdata = $userModel->where("email", $this->request->getVar("email"))->first();

      if (!empty($userdata)) {

        if (password_verify($this->request->getVar("password"), $userdata['password_hash'])) {

          $key = $this->getKey();

          $iat = time(); // current timestamp value
          $nbf = $iat + 10;
          $exp = $iat + 3600;

          $payload = array(
            "iss" => "The_claim",
            "aud" => "The_Aud",
            "iat" => $iat, // issued at
            "nbf" => $nbf, //not before in seconds
            "exp" => $exp, // expire time in seconds
            "data" => $userdata,
          );

          $token = JWT::encode($payload, $key);

          $response = [
            'status' => 200,
            'error' => false,
            'messages' => 'User logged In successfully',
            'data' => [
              'token' => $token
            ]
          ];
          return $this->respondCreated($response);
        } else {

          $response = [
            'status' => 500,
            'error' => true,
            'messages' => 'Incorrect details',
            'data' => []
          ];
          return $this->respondCreated($response);
        }
      } else {
        $response = [
          'status' => 500,
          'error' => true,
          'messages' => 'User not found',
          'data' => []
        ];
        return $this->respondCreated($response);
      }
    }
  }

  private function _auth($token)
  {
    $key = $this->getKey();
    $authHeader = $this->request->getHeader("Authorization");
    $authHeader = $authHeader->getValue();
    $token = $authHeader;

    $rst = true;

    try {
      $decoded = JWT::decode($token, $key, array("HS256"));

    } catch (Exception $ex) {
      $rst = false;
    }
    return true;
  }

  public function details()
  {
    $key = $this->getKey();
    $authHeader = $this->request->getHeader("Authorization");
    $authHeader = $authHeader->getValue();
    $token = $authHeader;

    try {
      $decoded = JWT::decode($token, $key, array("HS256"));

      if ($decoded) {

        $response = [
          'status' => 200,
          'error' => false,
          'messages' => 'User details',
          'data' => [
            'profile' => $decoded
          ]
        ];
        return $this->respondCreated($response);
      }
    } catch (Exception $ex) {

      $response = [
        'status' => 401,
        'error' => true,
        'messages' => 'Access denied',
        'data' => []
      ];
      return $this->respondCreated($response);
    }
  }
  public function detail($id = null)
  {
    $data = [
      'status' => 200,
      'msg' => 'success.',
    ];
    try{
      $user_model = new UserModel();
      $user = $user_model->where('id', 'id')->first();
      if($user === null){
        $data = [
          'status' => 600,
          'msg' => "user not found id: ${id}",
        ];
        $this->respond($data, 200);
        return;
      }
    }catch(\Exception $e){
      log_message('error', $e);
    }catch(\Error $e){
      log_message('error', $e);
    }finally{
      $this->respond($data, 200);
    }
  }
  public function create()
  {
    $validation =  \Config\Services::validation();
    $rules = [
      'name' => [
        'label' => '?????????',
        'rules' => 'required|min_length[3]|max_length[40]',
      ],
      'email' => [
        'label' => '?????????',
        'rules' => 'required|valid_email|is_unique[users.email]',
      ],
      'password' => [
        'label' => '?????????',
        'rules' => 'required|min_length[8]'
      ],
    ];
    $data = [
      "status" => 200,
      "msg" => "",
    ];
    try{
      $this->db->transBegin();
      $pwd_hash = password_hash(
        $this->request->getVar('password'),
        PASSWORD_DEFAULT
      );
      if ($this->validate($rules)) {
        $user = new UserModel();
        $userdata = [
          "email" => $this->request->getVar("email"),
          "display_name" => $this->request->getVar('name'),
          "password_hash" => $pwd_hash,
        ];
        $user->save($userdata);
      } else {
        $data["validation"] = $validation->getErrors();
      }
      $this->db->transCommit();
    }catch(\Exception $e){
      log_message('error', $e);
      $this->db->transRollback();
    }catch(\Error $e){
      log_message('error', $e);
      $this->db->transRollback();
    }finally{
      $this->respond($data, 200);
    }
  }
  public function update($id = null)
  {
    $validation =  \Config\Services::validation();
    $rules = [
      'name' => [
        'label' => '?????????',
        'rules' => 'required|min_length[3]|max_length[40]',
      ],
      'email' => [
        'label' => '?????????',
        'rules' => 'required|valid_email|is_unique[users.email]',
      ],
      'password' => [
        'label' => '?????????',
        'rules' => 'required|min_length[8]'
      ],
    ];
    $data = [
      "status" => 200,
      "msg" => "",
    ];
    try{
      $this->db->transBegin();
      $pwd_hash = password_hash(
        $this->request->getVar('password'),
        PASSWORD_DEFAULT);
      if ($this->validate($rules)) {
        $user = new UserModel();
        $userdata = [
          "email" => $this->request->getVar("email"),
          "display_name" => $this->request->getVar('name'),
          "password_hash" => $pwd_hash,
        ];
        $user->save($userdata);
      } else {
        $data["validation"] = $validation->getErrors();
      }
      $this->db->transCommit();
    }catch(\Exception $e){
      log_message('error', $e);
      $this->db->transRollback();
    }catch(\Error $e){
      log_message('error', $e);
      $this->db->transRollback();
    }finally{
      $this->respond($data, 200);
    }
  }
  public function delete($id = null)
  {
    $data = [];
    $this->respond($data, 200);
  }
}
