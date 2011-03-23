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
        $url = $this->config->item('base_url');
        $dir = $this->config->item('users_dir').$this->session->userdata('user').'/';
        $types = array('html' => '', 'json' => '.json', 'rss' => '.rss');

        if ($this->session->userdata('uid')) {
            foreach ($types as $key => $value) {
                $cache = $dir.'cache'.$value;
                if (file_exists($cache)) {
                    file_put_contents($cache, '');
                }
            }
            $this->load->model('Setting_model', '', True);
            $this->Setting_model->reset_latest($this->session->userdata('uid'));
            redirect($url);
        }
        else {
            redirect($url);
        }
    }
}

/* End of file clear.php */
/* Location: /tapp/application/controllers/clear.php */
