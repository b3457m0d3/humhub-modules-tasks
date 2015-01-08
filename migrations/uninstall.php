<?php

class uninstall extends ZDbMigration {

    public function up() {

        $this->dropTable('gmftask');
        $this->dropTable('gmftask_list');
        $this->dropTable('gmftask_user');
    }

    public function down() {
        echo "uninstall does not support migration down.\n";
        return false;
    }

}
