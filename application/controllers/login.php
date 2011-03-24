<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Author:
 *    Vayn a.k.a. VT <vayn@vayn.de>
 *    http://elnode.com
 *
 *    File:             login.php
 *    Create Date:      2011年03月22日 星期二 12时18分12秒
 */

class Login extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index($error='') {
        # Input and textarea field attributes
        $data['user'] = array(
            'name' => 'user',
            'id' => 'user',
            'tabindex' => 1,
        );
        $data['password'] = array(
            'name' => 'password',
            'id' => 'userid',
            'class' => 'long',
            'tabindex' => 2,
        );
        $data['submit'] = array(
            'name' => 'submit',
            'id' => 'save_submit',
            'value' => 'Login',
            'class' => 'clean-gray',
            'tabindex' => 3,
        );
        $data['error'] = $error ? $error : '';

        if ( ! $this->session->userdata('uid')) {
            $this->load->view('template/header');
            $this->load->view('login', $data);
        }
        else {
            redirect('setting/');
        }
    }

    public function submit() {
        $user = $this->input->post('user');
        $password = sha1($this->input->post('password'));

        if (empty($user) or empty($password)) {
            redirect('login/index/error');
        }

        $this->load->model('Login_model', '', True);
        $query = $this->Login_model->get_userinfo($user, $password);
        $num = $query->num_rows();
        $res = $query->result();

        if ($num == 1) {
            $id = $res[0]->id;
            $user = $res[0]->user;
            $this->session->set_userdata('uid', $id);
            $this->session->set_userdata('user', $user);

            $user_dir = FCPATH.'/users/'.$user;
            if ( ! file_exists($user_dir)) {
                try {
                    if (mkdir($user_dir, 0777)) {
                        redirect('setting/', 'location', 302);
                    }
                    else {
                        throw new Exception('Cannot create directory, please check permission.');
                    }
                }
                catch (Exception $e) {
                    show_error($e->getmessage());
                }
            }
            else {
                redirect('setting/', 'location', 302);
            }
        }
        redirect('login/index/error');
    }
}

/* End of file login.php */
