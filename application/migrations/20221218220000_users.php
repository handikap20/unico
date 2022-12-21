<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Users extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                    'type' => 'INT4',
                    'unsigned' => TRUE,
                    'unique' => TRUE
            ),
            'first_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'default' => null,
            ),
            'last_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'default' => null,
            ),
            'email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'default' => null,
            ),
            'password' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'default' => null,
            ),
            'created_at' => array(
                    'type' => 'timestamp',
                    'default' => null,
            ),
            'created_by' => array(
                    'type' => 'INT4',
                    'default' => null,
            ),
            'updated_at' => array(
                    'type' => 'timestamp',
                    'default' => null,
            ),
            'updated_by' => array(
                    'type' => 'INT4',
                    'default' => null,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('users');
    }

    public function down()
    {
        $this->dbforge->drop_table('users');
    }
}
?>