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
        $data = array();
        $user = $this->session->userdata('user');
        $site_url = $this->config->site_url();

        $this->load->config('custom');
        $user_dir = $this->config->item('users_dir').$user.'/';

        $shows = glob("{$user_dir}show.*");
        $mtime = 0;
        $format = '';
        foreach($shows as $show) {
            if (filemtime($show) > $mtime) {
                $mtime = filemtime($show);
                $format = pathinfo($show, PATHINFO_EXTENSION);
            }
        }

        $this->load->model('Setting_model', '', True);
        $setting = $this->Setting_model->get_setting();
        $cache_time = $setting->cache_time;

        if (time() - $mtime > $cache_time) {
            $this->load->driver('retriever');
            $format = $this->retriever->twitter->retrieve_headimg();
        }
        $src = "{$site_url}users/{$user}/show.{$format}";
        $data['src'] = $src;

        $this->load->view('template/header');
        $this->load->view('show', $data);
        $this->load->view('template/footer');
    }
}

/* End of file show.php */
/* Location: /tapp/application/controllers/show.php */
