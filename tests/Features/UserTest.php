<?php

namespace Codegniter;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class UserTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;
    protected $refresh = true;
    protected $migrate = true;
    // protected $basePath = TESTPATH  .'../app/Database';
    protected $namespace = 'App';

    protected function setUp(): void
    {
        parent::setUp();
        $this->migrateDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
    public function testRegister()
    {
        $result = $this->call('post', 'user/register', [
            'display_name' => 'test',
            'email' => 'wevorence@gmail.com',
            'password' => 'testtest',
        ]);
        $json = json_decode($result->getJson(), true);
        if($json['status'] !== 200){
            var_dump($json);
        }
        $result->assertOK();
    }
    public function testLogin()
    {
        $result = $this->call('get', 'user/login', [
            'email' => 'wevorence@gmail.com',
            'password' => 'testtest',
        ]);
        $json = json_decode($result->getJson(), true);
        if($json['status'] !== 200){
            var_dump($json);
        }
        $result->assertOK();
    }
}
