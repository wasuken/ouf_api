
<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\EventModel;

class Event extends BaseApiController
{
  protected $db;


  // TODO: そこそこの検索機能を追加する。
  // 別関数にしても良い。
  // イベント一覧
  public function index()
  {
    $response = [
      'status' => 200,
      'msg' => '',
    ];

    $event_model = new EventModel();

    $authHeader = $this->request->getHeader("Authorization");
    $authHeader = $authHeader->getValue();
    $token = $authHeader;

    $userdata = $this->_auth($token);

    $events = $event_model
        ->where('begin >=', 'NOW()', false);

    if($userdata){
      // 主催者のレコードは除外
      $events = $events
        ->where('organizer_user_id <>', $userdata['id'])
    }
    $events = $events->orderBy('begin');
    $response['events'] = $events;
    return $this->respondCreated($response);

  }
  // イベント作成
  public function create()
  {
    $authHeader = $this->request->getHeader("Authorization");
    $authHeader = $authHeader->getValue();
    $token = $authHeader;

    $rules = [
      "name" => "required",
      "title" => "required",
      "description" => "required",
      "begin" => "required|valid_date[Y/m/d H:i:s]",
      "end" => "required|valid_date[Y/m/d H:i:s",
    ];

    $userdata = $this->_auth($token);

    // 認証チェック
    if(!$userdata){
      $response = [
        'status' => 900,
        'error' => true,
        'msg' => 'failed auth.'
      ];
      return $this->respondCreated($response);
    }

    // パラメータチェック
    if(!$validate($rules)){
      $response = [
        'status' => 901,
        'error' => true,
        'msg' => implode("\n", $validation->getErrors())
      ];
      return $this->respondCreated($response);
    }
    $params = [
      'title' => $this->request->getVar('title'),
      'description' => $this->request->getVar('description'),
      'begin' => $this->request->getVar('begin'),
      'end' => $this->request->getVar('end'),
    ];
    $event->insert($params);

  }
}
