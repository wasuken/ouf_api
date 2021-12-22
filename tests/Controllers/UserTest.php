<?php

namespace CodeIgniter;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class UserTest extends CIUnitTestCase
{
  use ControllerTestTrait, DatabaseTestTrait;
  /**
   * testBasicComm 
   * 疎通確認テスト。
   * @access public
   * @return void
   */
  public function testBasicComm()
  {
    $methods = [
      'detail',
      'details',
      'delete',
      'login',
      'register',
    ];
    foreach($methods as $m){
      $result = $this->controller(\App\Controllers\User::class)
                     ->execute($m);

      $this->assertTrue($result->isOK());
    }
  }
}
