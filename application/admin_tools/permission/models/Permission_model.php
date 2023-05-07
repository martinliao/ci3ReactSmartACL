<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permission_model extends CI_Model
{
    protected $table = 'acl_module_permissions';
    protected $id = 'id';

    public function aksi()
    {
        $roleId = htmlspecialchars($_POST['role_id']);
        $moduleId = htmlspecialchars($_POST['module_id']);
        $moduleMethod = htmlspecialchars($_POST['module_method']);
        //$cek = $this->db->get_where($this->table, ['id_role' => $id_role, 'id_menu' => $id_menu])->row();
        $cek = $this->db->get_where($this->table, ['role_id' => $roleId, 'module_id' => $moduleId, 'permission' => $moduleMethod])->row();
        if ($cek) {
            $accessId = $cek->id;
            $this->db->where($this->id, $accessId);
            $this->db->delete($this->table);
            return false;
        }
        $data = [
            'role_id' => $roleId,
            'module_id' => $moduleId,
            'permission' => $moduleMethod,
        ];
        $this->db->insert($this->table, $data);
        return true;
    }
}
