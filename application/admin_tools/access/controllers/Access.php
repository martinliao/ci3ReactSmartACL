<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** Module_Permission(Access) */
class Access extends AdminController
{

	public function __construct()
	{
		parent::__construct();
		$this->logged_in();
        // $this->smarty_acl->authorized(); // Even do authorize check. 
        $this->smarty_acl->module_authorized('roles'); //  在 SmartACL 是 roles (roles是管理 role 及 Permission)
		$this->load->model('access_model', 'model');
	}

	public function index()
	{
		$_admins = $this->smarty_acl->admins(false);
        $admin_role_id = $_admins[0]['role_id'];
		if ($admin_role_id != 1) {
			$roles = $this->db->query('SELECT id as id_role, name as role FROM acl_roles WHERE id != 1')->result();

			$modules = $this->db->query('SELECT * FROM acl_modules WHERE id != 1 ORDER BY no_order ASC')->result();
		} else {
			$roles = $this->db->query('SELECT id as id_role, name as role_name FROM acl_roles')->result();
			$modules = $this->db->get('acl_modules')->result();
		}

		foreach($roles as $role) {
			$tmp[$role->id_role] = [];
			foreach($modules as $module) {
				$_check_val= '';
				if ( $role->id_role == 1 && $module->id == 1) {
					$_check_val= 'disabled checked';
				} else {
					$cek = $this->db->get_where('acl_module_permissions', ['role_id' => $role->id_role, 'module_id' => $module->id])->row();
					$_check_val= ( $cek ) ? 'checked' : '';
				}
				$tmp[$role->id_role][$module->id] = $_check_val;
			}
		}

		$data = [
			'title' => 'Access Page',
			'roles' => $roles,
			'modules' => $modules,
			'permission' => $tmp
		];
		$this->load->view('_layout/general/head', $data);
		$this->load->view('core/js', $data);
		$this->load->view('index', $data);
	}

	public function aksi()
	{
		$data = $this->model->aksi();
		echo json_encode($data);
	}
}
