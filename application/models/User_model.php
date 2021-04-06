<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model {

    public function getUserAkun()
    {
        return $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    }
    
    public function getQueryMenu()
    {
        $role_id = $this->session->userdata('role_id');

        $queryMenu = "SELECT user_menu.id, menu
                        FROM user_menu JOIN user_access_menu
                          ON user_menu.id = user_access_menu.menu_id
                        WHERE user_access_menu.role_id = $role_id
                        ORDER BY user_access_menu.menu_id ASC         
                    ";

        return $this->db->query($queryMenu)->result_array();
    }
    
    public function getQuerySubMenu($menuId)
    {
        $querySubMenu = "SELECT * FROM `user_sub_menu`
                      WHERE `menu_id` = $menuId
                      AND `is_active` = 1     
                    ";

        return $this->db->query($querySubMenu)->result_array();
    }

    public function getEditUser($foto)
    {
        $name = $this->input->post('name');
        $email = $this->input->post('email');

        if ($foto != NULL) {
            $this->db->set('image', $foto['file_name']);
        }

        $this->db->set('name', $name);
        $this->db->where('email', $email);
        $this->db->update('user');
    }

    public function uploadFoto()
    {
        $namaFoto = $this->input->post('id') . '_' . $this->input->post('name');
        $config['file_name'] = $namaFoto;
        $config['upload_path'] = './assets/img/profile/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 30000;
        $config['overwrite'] = TRUE;
        $config['remove_spaces'] = FALSE;

        $this->load->library('upload', $config);
    }

    public function changePasswordUser($password_hash)
    {
        $this->db->set('password', $password_hash);
        $this->db->where('email', $this->session->userdata('email'));
        $this->db->update('user');
    }


}