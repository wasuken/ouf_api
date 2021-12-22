<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserEventChatModel;
use \Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;

class UserEventChat extends BaseApiController{
  // チャット一覧取得
  public function index()
  {
    $response = [
      'status' => 200,
      "error" => false,
      'messages' => 'Successfully',
      'data' => []
    ];
    $event_model = new UserEventChatModel();

    $authHeader = $this->request->getHeader("Authorization");
    $token = "";
    if(!empty($authHeader)){
      $authHeader = $authHeader->getValue();
      $token = $authHeader;
    }

    $userdata = $this->_auth($token);

    if(empty($userdata)){
      $response = [
        'status' => 500,
        'error' => true,
        'message' => "not auth.",
        'data' => []
      ];
      return $this->respondCreated($response);
    }

    $rules = [
      'event_id' => 'required|is_not_unique[events.id]',
      'contents' => 'required|min_length[3]',
    ];
    if(!$this->validate($rules)){
      $response = [
        'status' => 500,
        'error' => true,
        'message' => $this->validator->getErrors(),
        'data' => []
      ];
      return $this->respondCreated($response);
    }

    if(empty($exists)){
      $response = [
        'status' => 500,
        'error' => true,
        'message' => $this->validator->getErrors(),
        'data' => []
      ];
      return $this->respondCreated($response);
    }
    // 一覧取得
    $chat_list = $event_model
      ->where('event_id', $event_id)
      ->findAll();
    $response['data'] = $chat_list;
    return $this->respondCreated($response);
  }
  /**
   * create 
   * チャット登録
   * @access public
   * @return void
   */
  public function create()
  {
    $response = [
      'status' => 200,
      "error" => false,
      'messages' => 'Successfully',
      'data' => []
    ];
    $event_model = new UserEventChatModel();

    $authHeader = $this->request->getHeader("Authorization");
    $token = $authHeader;
    $token = "";
    if(!empty($authHeader)){
      $authHeader = $authHeader->getValue();
      $token = $authHeader;
    }

    $userdata = $this->_auth($token);

    if(empty($userdata)){
      $response = [
        'status' => 500,
        'error' => true,
        'message' => "not auth.",
        'data' => []
      ];
      return $this->respondCreated($response);
    }

    $rules = [
      'event_id' => 'required|is_not_unique[events.id]',
      'contents' => 'required|min_length[3]',
    ];
    if(!$this->validate($rules)){
      $response = [
        'status' => 500,
        'error' => true,
        'message' => $this->validator->getErrors(),
        'data' => []
      ];
      return $this->respondCreated($response);
    }

    if(empty($exists)){
      $response = [
        'status' => 500,
        'error' => true,
        'message' => $this->validator->getErrors(),
        'data' => []
      ];
      return $this->respondCreated($response);
    }
    $params = [
      'user_id' => $userdata['id'],
      'event_id' => $event_id,
      'contents' => $this->request->getVar('contents'),
    ];
    $event_model->insert($params);
    return $this->respondCreated($response);
  }
  // TODO: チャット削除
}
