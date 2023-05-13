<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserSubmenu extends AdminController
{
	protected $parentId;
	public function __construct()
	{
		parent::__construct();
		$this->smarty_acl->authorized('modules'); // Even do authorize check. 最新的檢查
		$this->load->model('usersubmenu_model', 'model');
	}

	public function index($id)
	{
		$this->parentId = $id;
		//$cek = $this->db->get_where('BS_menu', ['id' => $id])->row();
		$cek = $this->model->getParentMenu($this->parentId);
		$data = [
			'judul' => 'Submenu',
			'title' =>	$cek->name,
			'id' => $this->parentId
		];
		$this->load->view('_layout/general/head', $data);
		$this->load->view('core/js', $data);
		$this->load->view('core/modals', $data);
		$this->load->view('index', $data);
	}
	function getLists($id)
	{
		$this->parentId = $id;
		$data = array();
		$submenu = $this->model->getRows($_POST, $this->parentId);

		$i = $_POST['start'];
		foreach ($submenu as $d) {
			$i++;
			$disabled = '';
			$menuName = htmlspecialchars($d->name);
			if ($d->id == 1) {
				$disabled = 'disabled';
			}
			if ($d->enable == 1) {
				$active = '<input type="checkbox" ' . $disabled . ' name="active" class="form-control-sm " data-id_submenu="' . $d->id . '" data-active="' . $d->enable . '" form-control-sm" id="active" checked>';
			} else {
				$active = '<input type="checkbox" ' . $disabled . ' name="active" class="form-control-sm " data-id_submenu="' . $d->id . '" data-active="' . $d->enable . '" form-control-sm" id="active" >';
			}
			$order = '<button data-order="' . $d->no_urut . '" data-id_menu="' . $this->parentId . '"  data-id_submenu="' . $d->id . '" class="btn btn-danger btn-xs down"><i class="fas fa-fw fa-arrow-down"></i></button> <button data-id_submenu="' . $d->id . '" data-order="' . $d->no_urut . '" data-id_menu="' . $this->parentId . '" class="btn btn-success btn-xs up"><i class="fas fa-fw fa-arrow-up"></i></button>';
			$icon = '<i class="' . $d->icon . '"></i>';
			$btn_edit = '<button type="button" class="btn btn-warning btn-xs edit" data-icon="' . $d->icon . '" data-title="' . $menuName . '" data-url="' . $d->url . '" data-id_submenu="' . $d->id . '"><i class="fas fa-fw fa-pen"></i> Edit</button>';
			$btn_hapus = '<button ' . $disabled . ' type="button" class="btn btn-danger btn-xs hapus"  data-id_submenu="' . $d->id . '"><i class="fas fa-fw fa-trash"></i> Hapus</button>';
			$data[] = array($i, $d->name, $icon, $d->url, $order, $active, $btn_edit . ' ' . $btn_hapus);
		}
//debugBreak();
		$recordsTotal = $this->model->countAll($this->parentId);
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $this->model->countFiltered($_POST, $this->parentId),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function aksi()
	{
		if ($_POST['aksi'] == 'tambah') {
			$data = $this->model->tambah();
			echo json_encode($data);
		} else if ($_POST['aksi'] == 'edit') {
			$data = $this->model->edit();
			echo json_encode($data);
		} else if ($_POST['aksi'] == 'hapus') {
			$data = $this->model->hapus();
			echo json_encode($data);
		}
	}
	public function active()
	{
		$data = $this->model->active();
		echo json_encode($data);
	}
	public function submenu($id)
	{
		$data = [
			'judul' => 'Submenu'
		];
		$this->load->view('submenu', $data);
	}
	public function down()
	{
		$data = $this->model->down();
		echo json_encode($data);
	}
	public function up()
	{
		$data = $this->model->up();
		echo json_encode($data);
	}
}
