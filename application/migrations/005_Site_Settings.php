<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Site_Settings extends CI_Migration
{
    /**
     * Config settings
     * @var array
     */
    private $settings;

    private function get_settings()
    {
        // Load configs(Example)
        $this->config->load('smarty_acl', TRUE);
        //Get tables array
        $tables = $this->config->item('tables', 'smarty_acl');
        //Tables prefix
        $this->settings['prefix'] = $tables['prefix'] ? $tables['prefix'].'_' : '';
        // Table names
        #$this->settings['roles'] = $this->settings['prefix'].$tables['roles'];
        $this->settings['settings'] = 'system_settings';
        $this->settings['medsos'] = 'medsos';
    }

    public function up()
    {
        $this->get_settings();
        /**************** Start Create Tables ****************/
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array('type' => 'VARCHAR', 'constraint' => '50', 'unsigned' => TRUE,),
            'phone' => array('type' => 'VARCHAR', 'constraint' => '15', 'unsigned' => TRUE,),
            'email' => array('type' => 'VARCHAR', 'constraint' => '50', 'unsigned' => TRUE,),
            'address' => array('type' => 'TEXT', 'unsigned' => TRUE,),
            'logo' => array('type' => 'VARCHAR', 'constraint' => '50', 'unsigned' => TRUE,),
            'footer_right' => array('type' => 'VARCHAR', 'constraint' => '254', 'unsigned' => TRUE,),
            'footer_left' => array('type' => 'VARCHAR', 'constraint' => '254', 'unsigned' => TRUE,),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->settings['settings']);
        /**************** End Create Tables ****************/
        /**************** Start Set Foreign Keys ****************/
        /**************** End Set Foreign Keys ****************/
        /**************** Start Insert Data ****************/
        $this->db->insert($this->settings['settings'],[
            'name' => 'ClickAP', 
            'phone' => '886-2-36080088', 
            'email' => 'service@click-ap.com', 
            'address' => 'No. 131, Daxue Rd., Sanxia Dist., New Taipei City , Taiwan', 
            'logo' => 'noimage1.png', 
            'footer_right' => 'Version 1.0',
            'footer_left' => 'Copyright © 2023 ClickAP - All Rights Reserved.',
        ]);
        /**************** End Insert Data ****************/
        $this->medsos();
    }

    public function down()
    {
        //Load settings
        $this->get_settings();
        //Drop tables
        $this->dbforge->drop_table($this->settings['settings']);
    }

    function medsos()
    {
        $this->get_settings();
        /**************** Start Create Tables ****************/
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'icon' => array('type' => 'VARCHAR', 'constraint' => '50', 'unsigned' => TRUE,),
            'warna' => array('type' => 'VARCHAR', 'constraint' => '50', 'unsigned' => TRUE,),
            'link' => array('type' => 'VARCHAR', 'constraint' => '254', 'unsigned' => TRUE,),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->settings['medsos']);
        /**************** End Create Tables ****************/
        /**************** Start Set Foreign Keys ****************/
        /**************** End Set Foreign Keys ****************/
        /**************** Start Insert Data ****************/
        $this->db->insert($this->settings['medsos'],['id' => 1, 'icon' => 'fa fa-fw fa-facebook', 'warna' => 'btn-primary', 'link' => '#']);
        $this->db->insert($this->settings['medsos'],['id' => 2, 'icon' => 'fa fa-fw fa-instagram', 'warna' => 'btn-warning', 'link' => '#']);
        $this->db->insert($this->settings['medsos'],['id' => 3, 'icon' => 'fa fa-fw fa-youtube-play', 'warna' => 'btn-danger', 'link' => '#']);
        $this->db->insert($this->settings['medsos'],['id' => 5, 'icon' => 'fa fa-fw fa-twitter', 'warna' => 'btn-info', 'link' => '#']);
        /**************** End Insert Data ****************/
    }
}