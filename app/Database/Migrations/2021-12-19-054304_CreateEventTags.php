<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventTags extends Migration
{
  public function up()
  {
    $this->forge->addField(
      [
        'event_id' => [
          'type' => 'VARCHAR',
          'constraint' => '255',
          'null' => false,
        ],
        'name' => [
          'type' => 'VARCHAR',
          'constraint' => '255',
          'null' => false,
        ],
        'created_at datetime default current_timestamp',
        'updated_at datetime default current_timestamp',
      ]
    );
    $this->forge->addPrimaryKey(['event_id', 'name']);
    $this->forge->createTable('event_tags');
  }

  public function down()
  {
    //
    $this->forge->dropTable('event_tags');
  }
}
