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
