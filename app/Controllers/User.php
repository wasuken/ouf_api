
<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;

class User extends BaseController
{
  public function __construct()
  {
    $validation =  \Config\Services::validation();
  }
  private function detail($id)
  {
    $data = [];
    $this->respond($data, 200);
  }
  private function create()
  {
    $data = [];
    $this->respond($data, 200);
  }
  private function update()
  {
    $data = [];
    $this->respond($data, 200);
  }
  private function del($id)
  {
    $data = [];
    $this->respond($data, 200);
  }
  public function _remap($method, $params)
  {
    if($method === 'index'){
      switch($method){
      case 'index':
        switch($this->request->getMethod()){
        case 'GET':
          $this->detail($params[0]);
          break;
        case 'POST':
          $this->create();
          break;
        case 'PUT':
          $this->create();
          break;
        case 'DELETE':
          $this->del($params[0]);
          break;
        default:
        }
        break;
      default:
      }
    }
  }
}
