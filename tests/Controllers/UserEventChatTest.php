<?php

namespace CodeIgniter;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class UserEventChatTest extends CIUnitTestCase
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
      'index',
      'create',
    ];
    foreach($methods as $m){
      $result = $this->controller(\App\Controllers\UserEventChat::class)
                     ->execute($m);

      $this->assertTrue($result->isOK());
    }
  }
}
