<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        // var_dump($session['role_id']);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == false) {
            
            $data['title'] = 'Login Page';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');

        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->Auth_model->getUser($email);

        // Jika Ada User
        if( $user ){
            // Jika User Aktive
            if( $user['is_active'] == 1 ) {
                // Cek Password
                if( password_verify($password, $user['password']) ) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                        'name' => $user['name']
                    ];
                    $this->session->set_userdata($data);
                    if( $user['role_id'] == 1 ) {
                        redirect('admin');
                    } else {
                        redirect('user');
                    }
                } else {
                    $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Wrong password !</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">This email has not activated !</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Email is not registered !</div>');
            redirect('auth');
        }
    }
    
    public function registration()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'This Email has already registered !'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'Password dont match!',
            'min_length' => 'Password too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
        
        if( $this->form_validation->run() == false ) {
            $data['title'] = 'Registration User';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $this->Auth_model->getRegisterAkun();
            $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">Congratulation ! yuor account hass been created. Please chek email your to Acctivate account</div>');
            redirect('auth');
        }
    }

    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->Auth_model->getUser($email);

        if ( $user ) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ( $user_token ) {
                if ( time() - $user_token['date_created'] < (60*60*24) ) {
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');

                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert"> ' . $email . ' has been acctivated! Please Login.</div>');
                    redirect('auth');

                } else {
                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Account activation failed! Token Expired</div>');
                    redirect('auth');
                }

            } else {
                $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Account activation failed! Wrong Token infailed</div>');
                redirect('auth');
            }

        } else {
            $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Account activation failed! Wrong Email</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('name');
        $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">You have been Logged Out !</div>');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
    }

    public function forgot()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Forgot Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgot');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();

            if ( $user ) {
                $this->Auth_model->forgotPassword($email);
                $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">Forgot password success! Please check your email to Reset password</div>');
                redirect('auth');
            } else {
                $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Your Email not Register or activated!</div>');
                redirect('auth/forgot');
            }

        }
    }

    public function forgotpassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->Auth_model->getUser($email);

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->risetpassword();
                // $this->Auth_model->verfyForgotPassword($email);
            } else {
                $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Riset Password Failed, Wrong Token!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('massage', '<div class="alert alert-danger" role="alert">Riset Password Failed, Wrong Email!</div>');
            redirect('auth');
        }
        
    }
    
    public function risetpassword()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth');
        }

        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|min_length[3]|matches[new_password1]');
    
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Forgot Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgotpassword', $data);
            $this->load->view('templates/auth_footer');
        } else {
            $this->Auth_model->verfyForgotPassword();
            $this->session->set_flashdata('massage', '<div class="alert alert-success" role="alert">Change password success! Please your Login</div>');
            redirect('auth');
        }
        
    }

}