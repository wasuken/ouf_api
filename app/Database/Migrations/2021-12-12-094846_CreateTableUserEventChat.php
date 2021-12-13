<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUserEventChat extends Migration
{
    public function up()
    {
        //
      $this->forge->addField(
        [
          'user_id' => [
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => false,
          ],
          'event_id' => [
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => false,
          ],
          'contents' => [
            'type' => 'text',
            'null' => false,
          ],
          'created_at datetime default current_timestamp',
          'updated_at datetime default current_timestamp',
        ]
      );
      $this->forge->addPrimaryKey(['user_id', 'group_id']);
      $this->forge->createTable('user_event_chats');
    }

    public function down()
    {
      //
      $this->forge->dropTable('user_event_chats');
    }
}
