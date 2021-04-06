<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'User');
        $this->load->model('Content_model');
        $this->load->library('form_validation');
        is_logged_in(); // function ini di buat di dalam librari halpers
    }

    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->User->getUserAkun();
        $data['menu'] = $this->Content_model->getUserMenu();
        
        $this->form_validation->set_rules('menu', 'Menu', 'required');
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Content_model->getInsertMenu();
            $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">New menu added</div>');
            redirect('menu');
        }

    }

    public function submenu()
    {
        $id = NULL;
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->User->getUserAkun();
        $data['menu'] = $this->Content_model->getUserMenu();
        $data['submenu'] = $this->Content_model->getUserSubMenu($id);

        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Content_model->insertSubMenu();
            $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">New Submenu added</div>');
            redirect('menu/submenu');
        }
    }

    public function submenudel($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_sub_menu');
        $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">Deleted Submenu success</div>');
        redirect('menu/submenu');        
    }

    public function submenuedit($id)
    {
        $data['title'] = 'Edit Submenu Management';
        $data['user'] = $this->User->getUserAkun();
        $data['menu'] = $this->Content_model->getUserMenu();
        $data['submenu'] = $this->Content_model->getUserSubMenu($id);

        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenuedit', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Content_model->editUserSubMenu();
            $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">Submenu Edited Success</div>');
            redirect('menu/submenu');
        }
    }

}