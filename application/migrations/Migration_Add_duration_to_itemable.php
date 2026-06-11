<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_duration_to_itemable extends CI_Migration
{
    public function up()
    {
        // Add duration column to itemable table for storing item duration
        if (!$this->db->field_exists('duration', db_prefix() . 'itemable')) {
            $this->dbforge->add_column(db_prefix() . 'itemable', [
                'duration' => [
                    'type' => 'varchar(100)',
                    'null' => true,
                    'after' => 'unit',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('duration', db_prefix() . 'itemable')) {
            $this->dbforge->drop_column(db_prefix() . 'itemable', 'duration');
        }
    }
}
