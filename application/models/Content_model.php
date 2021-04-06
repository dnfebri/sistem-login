<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Content_model extends CI_Model {
  
    public function getUserMenu()
    {
        return $this->db->get('user_menu')->result_array();
    }

    public function getInsertMenu()
    {
        $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
    }

    public function getUserSubMenu($id)
    {
        if ($id) {
            return $this->db->get_where('user_sub_menu', ['id' => $id])->row_array();
        }
        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
                    FROM `user_sub_menu` JOIN `user_menu`
                      ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                      ORDER BY `user_menu`.`id` ASC
                ";
        return $this->db->query($query)->result_array();
    }

    public function getRowsUser($i)
    {
        if ($i > 0) {
            return $this->db->get_where('user', ['role_id' => $i])->num_rows();
        } else {
            return $this->db->get('user')->num_rows();
        }
    }

    public function insertSubMenu()
    {
        $query = [
                'menu_id' => $this->input->post('menu_id'),
                'title' => $this->input->post('title'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];
        $this->db->insert('user_sub_menu', $query);
    }

    public function getUserRole($role_id)
    {
        if ($role_id == NULL) {
            return $this->db->get('user_role')->result_array();
        }else {
            return $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        }
    }

    public function addUserRole()
    {
        $this->db->insert('user_role', ['role' => $this->input->post('role')]);
    }

    public function editUserSubMenu()
    {
        $data = [
            'menu_id' => $this->input->post('menu_id'),
            'title' => $this->input->post('title'),
            'url' => $this->input->post('url'),
            'icon' => $this->input->post('icon'),
            'is_active' => $this->input->post('is_active')
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('user_sub_menu', $data);
    }

    public function getUserManagement()
    {
        $query = "SELECT `user`.*, `user_role`.`role`
                    FROM `user` JOIN `user_role`
                    ON `user`.`role_id` = `user_role`.`id`
                    ORDER BY `user`.`id` ASC
                ";
        return $this->db->query($query)->result_array();
    }

}