<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** Module_Permission */
class Permission extends AdminController
{
	protected $mods;
	protected $roles;
	protected $admin_role_id = 0;

	public function __construct()
	{
		parent::__construct();
		$this->logged_in();
        // $this->smarty_acl->authorized(); // Even do authorize check.
        //$tmp = $this->smarty_acl->module_authorized('roles'); //  在 SmartACL 是 roles (roles是管理 role 及 Permission)
		$this->smarty_acl->authorized('permission'); // Even do authorize check. 最新的檢查

		$this->load->model('smarty_acl_model', 'smartyacl_model');
		$this->load->model('permission_model');
		$this->mods = $this->smartyacl_model->modules(TRUE);
		$this->roles = $this->smartyacl_model->roles(true);
		$_admins = $this->smarty_acl->admins(false);
        $this->admin_role_id = $_admins[0]['role_id'];
	}

	public function index()
	{
		$_all_permission = [];
		foreach($this->roles as $role) {
			$_all_permission[$role->id] = [];
			$role_all_permission = $this->smartyacl_model->get_group_permissions_by_role($role->id);
			foreach($this->mods as $key => $mod) {
				$_module_methods = ($this->mods[$key]->permissions) ? $_module_methods = json_decode ($this->mods[$key]->permissions) : [];
				$permissions = [];
				foreach($_module_methods as $method) {					
					if ( $role->id == $this->admin_role_id && $mod->id == 1) {
						$permissions[$method] = 'disabled checked';
					} else {
						$permissions[$method] = ($role_all_permission[$mod->controller]) ? (in_array($method, $role_all_permission[$mod->controller]) ? 'checked' : '') : '';
					}
				}
				$_all_permission[$role->id][$mod->id]['permissions'] = $permissions;
			}
		}
		$data = [
			'title' => 'Permission Page',
			'roles' => $this->roles,
			'modules' => $this->mods,
			'permission' => $_all_permission
		];
		$this->load->view('_layout/general/head', $data);
		$this->load->view('core/js', $data);
		$this->load->view('index', $data);
	}

	public function aksi()
	{
		$data = $this->permission_model->aksi();
		/*$roleID = htmlspecialchars($_POST['role_id']);
        $menuId = htmlspecialchars($_POST['menu_id']);
        $menuMethod = htmlspecialchars($_POST['menu_method']);
		$this->smartyacl_model->update_role($roleID, $data)*/
		echo json_encode($data);
	}
}
