<?php

namespace CodeIgniter;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class EventTest extends CIUnitTestCase
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
      'detail',
      'create',
      'participate',
    ];
    foreach($methods as $m){
      $result = $this->controller(\App\Controllers\Event::class)
                     ->execute($m);

      $this->assertTrue($result->isOK());
    }
  }
}
