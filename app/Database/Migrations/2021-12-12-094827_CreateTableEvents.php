<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEvents extends Migration
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
          'organizer_user_id' => [
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => false,
          ],
          'title' => [
            'type' => 'text',
            'null' => false,
          ],
          'description' => [
            'type' => 'text',
            'null' => false,
          ],
          'begin' => [
            'type' => 'datetime',
            'null' => false,
          ],
          'end' => [
            'type' => 'datetime',
            'null' => false,
          ],
          'created_at datetime default current_timestamp',
          'updated_at datetime default current_timestamp',
        ]
      );
      $this->forge->addPrimaryKey('id');
      $this->forge->createTable('events');
    }

    public function down()
    {
      //
      $this->forge->dropTable('events');
    }
}
