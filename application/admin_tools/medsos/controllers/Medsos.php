<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Medsos extends AdminController
{

	public function __construct()
	{
		parent::__construct();
		$this->logged_in();
        // $this->smarty_acl->authorized(); // Even do authorize check. 
        $this->smarty_acl->module_authorized('menu'); // 為了要檢查, 在 SmartACL 增加了 menu (module)
		$this->load->model('medsos_model', 'model');
	}
	public function index()
	{
		$this->load->view('index');
	}
	function getLists()
	{
		$data = array();
		$medsos = $this->model->getRows($_POST);

		$i = $_POST['start'];
		foreach ($medsos as $d) {
			$i++ + 1;
			$icon = '<a class="btn btn-social-icon ' . $d->warna . '"><i class="' . $d->icon . '"></i></a>';
			$btn_edit = '<button type="button" class="btn btn-warning btn-xs edit" data-icon="' . $d->icon . '" data-link="' . $d->link . '" data-warna="' . $d->warna . '" data-id="' . $d->id . '"><i class="fa fa-fw fa-edit"></i> Edit</button>';
			$btn_hapus = '<button type="button" class="btn btn-danger btn-xs hapus"  data-id="' . $d->id . '"><i class="fa fa-fw fa-trash"></i> Hapus</button>';
			$data[] = array($i, $d->link, $icon, $btn_edit . ' ' . $btn_hapus);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->model->countAll(),
			"recordsFiltered" => $this->model->countFiltered($_POST),
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
}
