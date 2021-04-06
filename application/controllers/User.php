<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'User');
        is_logged_in(); // function ini di buat di dalam librari halpers
    }

    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->User->getUserAkun();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->User->getUserAkun();

        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $upload_foto = $_FILES['image']['name'];
            // $foto = $this->upload->data();
            // var_dump($foto);
            // die();
            if ($upload_foto) {
                $this->User->uploadFoto();
                if ($this->upload->do_upload('image')) {  
                    $old_image = $data['user']['image'];
                    // Menghapus foto lama bukan default.jpg
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile' . $old_image);
                    }
                    $foto = $this->upload->data();
                    $this->User->getEditUser($foto);
                    $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">Your Profile Has Updated!</div>');
                    redirect('user');
                } else {
                    $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">'. $this->upload->display_errors() .'</div>');
                    redirect('user/edit');
                }
            }
            $this->User->getEditUser($foto);
            $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">Your Profile Has Updated!</div>');
            redirect('user');
        }
    }

    public function changepassword()
    {
        $data['title'] = 'Chang Password';
        $data['user'] = $this->User->getUserAkun();

        $this->form_validation->set_rules('old_password', 'Old Password', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|min_length[3]|matches[new_password1]');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/footer');
        } else {
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password1');
            if (!password_verify($old_password, $data['user']['password'])) {
                $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Worng Old Password!</div>');
                redirect('user/changepassword');
            } else {
                if ($old_password == $new_password) {
                    $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">New Password cannot be the same as Old Password!</div>');
                    redirect('user/changepassword');
                } else {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $this->User->changePasswordUser($password_hash);
                    $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">Password Change!</div>');
                    redirect('user');
                }
            }

            // var_dump($this->User->changePasswordUser());
        }
    }

}