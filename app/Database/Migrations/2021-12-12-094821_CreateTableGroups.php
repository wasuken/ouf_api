<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableGroups extends Migration
{
    public function up()
    {
        //
      $this->forge->addField(
        [
          'id' => [
            'type' => 'VARCHAR',
            'constraint' => '255',
          ],
          'display_name' => [
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => false,
          ],
        ]
      );
      $this->forge->addPrimaryKey('id');
      $this->forge->createTable('groups');
    }

    public function down()
    {
      //
      $this->forge->dropTable('groups');
    }
}
