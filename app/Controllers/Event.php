<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\EventModel;
use App\Models\UserEventParticipateModel;
use App\Models\UserEventChatModel;
use App\Models\EventTagModel;

/**
 * Event 
 * 
 * @uses BaseApiController
 * @package 
 * @version 
 * @copyright 
 * @author wasuken <wasuken1@gmail.com> 
 * @license MIT
 */
class Event extends BaseApiController
{
  protected $db;

  /**
   * index 
   * TODO: そこそこの検索機能を追加する。
   * 別関数にしても良い。
   * イベント一覧
   * @access public
   * @return void
   */
  public function index()
  {
    $response = [
      'status' => 200,
      'msg' => '',
    ];

    $event_model = new EventModel();

    $authHeader = $this->request->getHeader("Authorization");
    if(!empty($authHeader)){
      $authHeader = $authHeader->getValue();
    }
    $token = $authHeader;

    $userdata = $this->_auth($token);

    $events = $event_model
        ->where('begin >=', 'NOW()', false);

    if($userdata){
      // 主催者のレコードは除外
      $events = $events
        ->where('organizer_user_id <>', $userdata['id']);
    }
    $events = $events->orderBy('begin');
    $response['data'] = $events;
    return $this->respondCreated($response);

  }
  /**
   * detail 
   * イベント単体情報取得
   * @access public
   * @return void
   */
  public function detail()
  {
    $response = [
      'status' => 200,
      'msg' => '',
    ];

    $rules = [
      'event_id' => 'required|is_not_unique[events.id]',
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

    $event_model = new EventModel();

    $authHeader = $this->request->getHeader("Authorization");
    $authHeader = $authHeader->getValue();
    $token = $authHeader;

    $userdata = $this->_auth($token);

    $event = $event_model->find($event_id);
    $response['data'] = $event;
    return $this->respondCreated($response);

  }
  /**
   * create 
   * イベント作成
   *   "name" => "required",
   *   "title" => "required",
   *   "description" => "required",
   *   "begin" => "required|valid_date[Y/m/d H:i:s]",
   *   "end" => "required|valid_date[Y/m/d H:i:s",
   *   "tags" => 配列, 職人の手によるvalidate
   * @access public
   * @return void
   */
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
    $tags = [];

    if(!empty($_POST['tags'])){
      $tags = $_POST['tags'];
    }

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
    $event_id = $event->getInsertID();
    $event_tag = new EventTagModel();

    // insert tags
    foreach($tags as $tag){
      $params = [
        'event_id' => $event_id,
        'name' => $tag,
      ];
      $event_tag->insert($params);
    }

    return $this->respondCreated($response);
  }

  /**
   * participate 
   * イベント参加
   * @access public
   * @return void
   */
  public function participate()
  {
    $response = [
      'status' => 200,
      "error" => false,
      'messages' => 'Successfully',
      'data' => []
    ];
    $event_model = new UserEventParticipateModel();
    $em = new EventModel();

    $authHeader = $this->request->getHeader("Authorization");
    $authHeader = $authHeader->getValue();
    $token = $authHeader;

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
    // 存在確認
    $event_id = $this->request->getVar('event_id');
    $exists = $event_model
      ->where('user_id', $userdata['id'])
      ->where('event_id', $event_id)
      ->first();

    // 参加済
    if(!empty($exists)){
      $response = [
        'status' => 500,
        'error' => true,
        'message' => $this->validator->getErrors(),
        'data' => []
      ];
      return $this->respondCreated($response);
    }
    // 参加処理
    $params = [
      'user_id' => $userdata['id'],
      'event_id' => $event_id,
    ];

    $event_model->insert($params);

    $select = 'title, event_date_begin, event_date_end, partificate_date_begin, partificate_date_end, description';
    $ev = $em
      ->select($select)
      ->find($event_id);

    $response['data'] = $ev;

    return $this->respondCreated($response);
  }

}
