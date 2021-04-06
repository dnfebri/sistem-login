<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    public function getUser($email)
    {
        return $this->db->get_where('user', ['email' => $email])->row_array();
    }
    
    public function getRegisterAkun()
    {
        $email = $this->input->post('email', true);
        $data = [
            'name' => htmlspecialchars($this->input->post('name', true)),
            'email' => htmlspecialchars($email),
            'image' => 'default.jpg',
            'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
            'role_id' => 2,
            'is_active' => 0,
            'date_created' => time()
        ];

        $token = base64_encode( random_bytes(34) );
        $user_token = [
            'email' => $email,
            'token' => $token,
            'date_created' => time()
        ];

        $this->db->insert('user', $data);
        $this->db->insert('user_token', $user_token);

        $this->_sendEmail($token, 'verify');
    }

    private function _sendEmail($token, $type)
    {
        $email = $this->input->post('email');
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'dnfebri.site@gmail.com',
            'smtp_pass' => 'kentungtok',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->load->library('email', $config);
        $this->email->initialize($config);

        $this->email->from('dnfebri.site@gmail.com', 'DN Febri Aktivation');
        $this->email->to($email);

        if ($type == 'verify') {
            $this->email->subject('Accuount Verifikation');
            $this->email->message('Click this link to verify your account : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Acctivete</a>');
        } else if ($type == 'forgotPassword') {
            $this->email->subject('Reset Password Verifikation');
            $this->email->message('Click this link to Reset your Password : <a href="' . base_url() . 'auth/forgotpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Acctivete</a>');
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function forgotPassword($email)
    {
        $token = base64_encode(random_bytes(34));
        $user_token = [
            'email' => $email,
            'token' => $token,
            'date_created' => time()
        ];
        $this->db->insert('user_token', $user_token);

        $this->_sendEmail($token, 'forgotPassword');
    }

    public function verfyForgotPassword()
    {
        $password = password_hash($this->input->post('new_password1'), PASSWORD_DEFAULT);
        $email = $this->session->userdata('reset_email');

        $this->db->set('password', $password);
        $this->db->where('email', $email);
        $this->db->update('user');

        $this->db->delete('user_token', ['email' => $email]);

        $this->session->unset_userdata('reset_email');
    }

}