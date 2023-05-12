<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usermenu_model extends CI_Model
{
    protected $bs_database = 'dcsdphy';
    //$db = $this->load->database($bs_database, TRUE);
    protected $table = 'BS_menu';
    protected $id = 'id';

    function __construct()
    {
        $this->db = $this->load->database($this->bs_database, TRUE);
        $this->column_order = array(null, 'name', 'icon', null, 'enable', null, null);
        $this->column_search = array('name');
        $this->order = array('sort_order' => 'asc');
    }

    public function getRows($postData)
    {
        $this->_get_datatables_query($postData);
        if ($postData['length'] != -1) {
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function countAll()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function countFiltered($postData)
    {
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query($postData)
    {
        //SELECT * FROM BS_menu WHERE parent_id=0 AND ENABLE=1 AND action_id=0 ORDER BY sort_order
        $this->db->from($this->table)
            ->group_start()
                    ->where('parent_id', 0)
                    ->where('action_id', 0)
            ->group_end();
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($postData['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                } else {
                    $this->db->or_like($item, $postData['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if (isset($postData['order'])) {
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function tambah()
    {
        $title = htmlspecialchars($_POST['title']);
        $icon = htmlspecialchars($_POST['icon']);
        $hitung = count($this->db->get('user_menu')->result()) + 1;
        $data = [
            'title' => $title,
            'icon' => $icon,
            'is_active' => 1,
            'no_order' => $hitung
        ];
        $this->db->insert($this->table, $data);

        $data = [
            'status' => true,
            'pesan' => "Data Menu Berhasil Ditambah"
        ];

        return $data;
    }

    public function edit()
    {
        $id = htmlspecialchars($_POST['id']);
        $title = htmlspecialchars($_POST['title']);
        $icon = htmlspecialchars($_POST['icon']);

        $data = [
            'title' => $title,
            'icon' => $icon
        ];

        $this->db->where($this->id, $id);

        $this->db->update($this->table, $data);

        $data = [
            'status' => true,
            'pesan' => "Data Menu Berhasil diubah"
        ];

        return $data;
    }


    public function hapus()
    {
        $id = htmlspecialchars($_POST['id']);
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);

        $data = [
            'status' => true,
            'pesan' => "Data Menu Berhasil Dihapus"
        ];

        return $data;
    }


    public function active()
    {
        $id = $this->input->post('id');
        $active = $this->input->post('active');
        if ($active == 1) {
            $this->db->set('is_active', 0);
            $data['active'] = 'false';
        } else {
            $this->db->set('is_active', 1);
            $data['active'] = 'true';
        }
        $this->db->where($this->id, $id);
        $data['data'] = $this->db->update($this->table);
        return $data;
    }


    public function down()
    {
        $order = $this->input->post('no_order');
        $id_menu = $this->input->post('id_menu');
        $hitung = count($this->db->get($this->table)->result());
        if ($order < $hitung) {
            $up = $this->db->get_where($this->table, ['no_order' => $order + 1])->row();
            $this->db->set('no_order', $order + 1);
            $this->db->where($this->id, $id_menu);
            $this->db->update($this->table);
            if ($up) {
                $this->db->set('no_order', $up->no_order - 1);
                $this->db->where($this->id, $up->id_menu);
                $this->db->update($this->table);
            }
            return true;
        }
        return false;
    }


    public function up()
    {
        $order = $this->input->post('no_order');
        $id_menu = $this->input->post('id_menu');
        if ($order > 1) {
            $up = $this->db->get_where($this->table, ['no_order' => $order - 1])->row();
            $this->db->set('no_order', $order - 1);
            $this->db->where($this->id, $id_menu);
            $this->db->update($this->table);
            if ($up) {
                $this->db->set('no_order', $up->no_order + 1);
                $this->db->where($this->id, $up->id_menu);
                $this->db->update($this->table);
            }
            return true;
        }
        return false;
    }

}
