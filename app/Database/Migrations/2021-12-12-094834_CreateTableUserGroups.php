<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUserGroups extends Migration
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
      $this->forge->createTable('user_groups');
    }

    public function down()
    {
      //
      $this->forge->dropTable('user_groups');
    }
}
