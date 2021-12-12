<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUsers extends Migration
{
    public function up()
    {
        //
      $this->forge->addField(
        [
          'id' => [
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => false,
          ],
          'display_name' => [
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => false,
          ],
          'password_hash' => [
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => false,
          ],
          'email' => [
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => false,
          ],
          'created_at' => [
            'type' => 'datetime',
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
          ],
          'updated_at' => [
            'type' => 'datetime',
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
          ],
        ]
      );
      $this->forge->addPrimaryKey('id');
      $this->forge->createTable('users');
    }

    public function down()
    {
      //
      $this->forge->dropTable('users');
    }
}
