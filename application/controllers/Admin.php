<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'User');
        $this->load->model('Content_model');
        // $this->load->model('Admin_model');
        is_logged_in(); // function ini di buat di dalam librari halpers
    }

    public function index()
    {
        $data['user'] = $this->User->getUserAkun();
        $data['title'] = 'Dashboard';
        $data['admin'] = $this->Content_model->getRowsUser(1);
        $data['teller'] = $this->Content_model->getRowsUser(2);
        $data['member'] = $this->Content_model->getRowsUser(3);
        $data['total'] = $data['admin'] + $data['teller'] + $data['member'];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }

    public function role()
    {
        $role_id = NULL;
        $data['user'] = $this->User->getUserAkun();
        $data['role'] = $this->Content_model->getUserRole($role_id);
        $data['title'] = 'Role';

        $this->form_validation->set_rules('role', 'Role', 'required|trim');
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/role', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Content_model->addUserRole();
            $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">Role Added Succes</div>');
            redirect('admin/role');
        }
    }

    public function roleAccess($role_id)
    {
        $data['user'] = $this->User->getUserAkun();
        $data['role'] = $this->Content_model->getUserRole($role_id);
        $this->db->where('id !=', 1);
        $data['menu'] = $this->Content_model->getUserMenu();
        $data['title'] = 'Role Access';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }

    public function changeaccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);
        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">Access Changed!</div>');
    }


    public function user()
    {
        $data['user'] = $this->User->getUserAkun();
        $data['rowuser'] = $this->Content_model->getUserManagement();
        $data['title'] = 'User Management';
        $data['total'] = $this->Content_model->getRowsUser(0);
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/user', $data);
        $this->load->view('templates/footer');
    }


}
