<?php

namespace CodeIgniter;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class EventTest extends CIUnitTestCase
{
  use ControllerTestTrait, DatabaseTestTrait;
  /**
   * testEventList 
   * イベント一覧取得
   * @access public
   * @return void
   */
  public function testEventList()
  {
    $result = $this->controller(\App\Controllers\Event::class)
                   ->execute('index');

    $this->assertTrue($result->isOK());
  }
  /**
   * testEventList 
   * イベント詳細取得
   * @access public
   * @return void
   */
  public function testEventDetail()
  {
    $
    $result = $this->controller(\App\Controllers\Event::class)
                   ->execute('detail');

    $this->assertTrue($result->isOK());
  }
}
