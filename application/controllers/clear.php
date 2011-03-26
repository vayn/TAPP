<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Author:
 *    Vayn a.k.a. VT <vayn@vayn.de>
 *    http://elnode.com
 *
 *    File:             clear.php
 *    Create Date:      2011年03月24日 星期四 04时13分40秒
 */

class Clear extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ( ! $this->session->userdata('uid')) {
            redirect($url);
        }

        $url = $this->config->item('base_url');
        $this->load->config('custom');
        $user_dir = $this->config->item('users_dir').$this->session->userdata('user').'/';
        $reset = file_get_contents($user_dir.'../latest');
        $types = array('html' => '.html', 'json' => '.json', 'rss' => '.rss');

        foreach ($types as $key => $value) {
            $cache = $user_dir.'cache'.$value;
            if (file_exists($cache)) {
                file_put_contents($cache, $reset);
            }
        }

        $this->load->model('Setting_model', '', True);
        $this->Setting_model->reset($this->session->userdata('uid'));

        $this->load->driver('retriever');
        $this->retriever->twitter->retrieve_showimg($this->session->userdata('user'), $this->session->userdata('uid'));

        redirect($url);
    }
}

/* End of file clear.php */
/* Location: /tapp/application/controllers/clear.php */
