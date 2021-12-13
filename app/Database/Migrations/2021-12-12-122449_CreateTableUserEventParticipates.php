<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUserEventParticipates extends Migration
{
  public function up()
  {
    $this->forge->addField(
      [
        'user_id' => [
          'type' => 'VARCHAR',
          'constraint' => '255',
          'null' => false,
        ],
        'group_id' => [
          'type' => 'VARCHAR',
          'constraint' => '255',
          'null' => false,
        ],
        'created_at datetime default current_timestamp',
        'updated_at datetime default current_timestamp',
      ]
    );
    $this->forge->addPrimaryKey(['user_id', 'group_id']);
    $this->forge->createTable('user_event_participates');
  }

  public function down()
  {
    //
    $this->forge->dropTable('user_event_participates');
  }
}
