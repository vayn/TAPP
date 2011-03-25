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
        $uid = $this->session->userdata('uid');
        $site_url = $this->config->site_url();

        $this->load->config('custom');
        $user_dir = $this->config->item('users_dir').$user.'/';

        $avatars = glob("{$user_dir}avatar.*");
        $mtime = 0;
        $format = '';
        foreach($avatars as $avatar) {
            if (filemtime($avatar) > $mtime) {
                $mtime = filemtime($avatar);
                # $format = pathinfo($avatar, PATHINFO_EXTENSION);
            }
        }

        $this->load->model('Setting_model', '', True);
        $setting = $this->Setting_model->get_setting($uid);
        $cache_time = $setting->cache_time;

        if (time() - $mtime > $cache_time) {
            $this->load->driver('retriever');
            $this->retriever->twitter->retrieve_showimg($user, $uid);
        }
        $src = "{$site_url}users/{$user}/show.png";
        $data['src'] = $src;

        $this->load->view('template/header');
        $this->load->view('show', $data);
        $this->load->view('template/footer');
    }
}

/* End of file show.php */
/* Location: /tapp/application/controllers/show.php */
