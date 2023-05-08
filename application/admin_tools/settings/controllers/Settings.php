<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->logged_in();
        // $this->smarty_acl->authorized(); // Even do authorize check. 
        //$this->smarty_acl->module_authorized('menu'); // 為了要檢查, 在 SmartACL 增加了 menu (module)
        $this->smarty_acl->authorized('menu'); // Even do authorize check. 最新的檢查
        $this->load->model('settings_model', 'model');
    }
    public function index()
    {
        $data = [
            'title' => 'Profile',

        ];
        $this->load->view('_layout/general/head', $data);
        $this->load->view('core/js', $data);
        $this->load->view('core/modals', $data);
        $this->load->view('index', $data);
    }
    public function getProfile()
    {
        $data = $this->model->getProfile();
        echo json_encode($data);
    }
    public function getMedsos()
    {
        $data = $this->model->getMedsos();
        echo json_encode($data);
    }
    public function update()
    {
        $data = $this->model->update();
        echo json_encode($data);
    }
}
