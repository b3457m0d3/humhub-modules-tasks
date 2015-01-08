<?php

class m131023_165214_initial extends ZDbMigration {

    public function up() {
        $this->createTable('gmftask_list', array(
            'id' => 'pk',
            'user_id'    => 'int(11) NOT NULL',
            'title'      => 'text NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
        ), '');
        
        $this->createTable('gmftask', array(
            'id' => 'pk',
            'list_id' => 'int(11) NOT NULL',
            'title' => 'text NOT NULL',
            'deadline' => 'datetime DEFAULT NULL',
            'max_users' => 'int(11) NOT NULL',
            'status' => 'int(11) NOT NULL',
            'percent' => "smallint(6) NOT NULL DEFAULT 0",
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
                ), '');

        $this->createTable('gmftask_user', array(
            'id' => 'pk',
            'user_id' => 'int(11) NOT NULL',
            'task_id' => 'int(11) NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
                ), '');
    }

    public function down() {
        echo "m131023_165214_initial does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
