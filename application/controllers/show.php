<?php
/**
 * Author:
 *    Vayn a.k.a. VT <vayn@vayn.de>
 *    http://elnode.com
 *
 *    File:             show.php
 *    Create Date:      2011年03月24日 星期四 04时55分41秒
 */

class Show extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $user = $this->session->userdata('user');
        $site_url = $this->config->site_url();
        $data = array();

        $this->load->driver('retriever');
        $format = $this->retriever->twitter->retrieve_headimg();

        $src = "{$site_url}users/{$user}/show.{$format}";
        $data['src'] = $src;

        $this->load->view('template/header');
        $this->load->view('show', $data);
        $this->load->view('template/footer');
    }
}

/* End of file show.php */
/* Location: /tapp/application/controllers/show.php */
