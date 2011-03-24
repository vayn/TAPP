<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Author:
 *    Vayn a.k.a. VT <vayn@vayn.de>
 *    http://elnode.com
 *
 *    File:             settings.php
 *    Create Date:      2011年03月22日 星期二 14时12分16秒
 */

class Setting extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ( ! $this->session->userdata('uid')) {
            redirect('login/');
        }
        $uid = $this->session->userdata('uid');
        $user = $this->session->userdata('user');

        $this->config->load('custom');
        $user_dir = $this->config->item('users_dir')."{$user}/";
        $data['user_dir'] = $user_dir;

        $data['url'] = $this->config->site_url();
        $data['cache'] = $data['url']."users/{$user}/";

        # Input and textarea field attributes
        $data['user'] = array(
            'name' => 'user',
            'id' => 'user',
            'tabindex' => 1,
            );

        for ($i = 1; $i < 11; ++$i) {
            $options[$i] = $i;
        }
        $data['amount'] = array(
            'options' => $options,
            );
        unset($options);

        $data['reply'] = array(
            array('name' => 'reply', 'id' => 'reply', 'value' => 'yes'),
            array('name' => 'reply', 'id' => 'reply', 'value' => 'no'),
            );

        for ($i = 300; $i < 3600; $i *= 2) {
            $options[$i] = $i/60 . 'min';
        }
        $options['3600'] = '1 hour'; 
        $data['cache_time'] = array(
            'options' => $options,
            );
        unset($options);

        $data['types'] = array(
            array('name' => 'type', 'id' => 'type', 'value' => 'json'),
            array('name' => 'type', 'id' => 'type', 'value' => 'html'),
            array('name' => 'type', 'id' => 'type', 'value' => 'rss'),
            );

        $data['submit'] = array(
            'name' => 'submit',
            'id' => 'save_submit',
            'value' => 'Save',
            'class' => 'clean-gray',
            'tabindex' => 3,
        );

        $this->load->library('form_validation');
        $rules = array(
                    array(
                        'field' => 'user',
                        'label' => 'twitter username',
                        'rules' => 'required',
                        ),
                    array(
                        'field' => 'amount',
                        'label' => 'amount',
                        'rules' => 'required|integer',
                        ),
                    array(
                        'field' => 'reply',
                        'label' => 'replies',
                        'rules' => 'required|alpha',
                        ),
                    array(
                        'field' => 'cache_time',
                        'label' => 'cache time',
                        'rules' => 'required|integer',
                        ),
                    array(
                        'field' => 'type',
                        'label' => 'output',
                        'rules' => 'required|alpha',
                        ),
                    );
        $this->form_validation->set_rules($rules);

        $this->load->model('Setting_model', '', True);
        $data['setting'] = $this->Setting_model->get_setting($uid);
        $data['latest'] = $this->Setting_model->get_latest();

        if ($this->form_validation->run() == True) {
            $twitter = $this->input->post('user');
            $amount = $this->input->post('amount');
            $reply = $this->input->post('reply');
            $cache_time = $this->input->post('cache_time');
            $type = $this->input->post('type');

            $setting = array(
                'uid' => $uid,
                'twitter' => $twitter,
                'amount' => $amount,
                'reply' => $reply,
                'cache_time' => $cache_time,
                'update_time' => time(),
                'type' => $type,
                );

            $this->load->driver('retriever');
            $msg = $this->retriever->twitter->retrieve_msg($setting);
            $setting['latest'] = $msg ? $msg['latest'] : '!!Error!!';

            $this->Setting_model->update_setting($setting);

            $this->load->helper('function');
            save_cache($msg['tweets'], $type, $user_dir);

            redirect('./');
        }
        else {
            $this->load->view('template/header');
            $this->load->view('setting', $data);
            $this->load->view('template/footer');
        }
    }
}

/* End of file settings.php */
