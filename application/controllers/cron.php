<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Author:
 *    Vayn a.k.a. VT <vayn@vayn.de>
 *    http://elnode.com
 *
 *    File:             cron.php
 *    Create Date:      2011年03月24日 星期四 22时23分42秒
 */

class Cron extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index($password='') {
        $this->load->config('custom');
        $cron_auth = $this->config->item('pw_cron');
        $dir = $this->config->item('users_dir');

        if ($cron_auth == $password) {
            ignore_user_abort(True);

            $this->load->helper('function');
            $this->load->model('Setting_model', '', True);
            $settings = $this->Setting_model->get_setting();

            foreach ($settings as $setting) {
                if ($setting->cron == 1 &&
                    time() - $setting->update_time > $setting->cache_time) {

                    $setting = new ArrayObject($setting);
                    $user = $this->Setting_model->get_user($setting['uid']);
                    $user_dir = $dir.$user.'/';

                    $this->load->driver('retriever');
                    $msg = $this->retriever->twitter->retrieve_msg($setting);
                    $setting['latest'] = $msg ? $msg['latest'] : '!!Error!!';
                    $setting['update_time'] = time();

                    $this->Setting_model->update_setting($setting);
                    save_cache($msg['tweets'], $setting['type'], $user_dir);

                    $this->retriever->twitter->retrieve_showimg($user, $uid);
                }
            }
        }
        else {
            echo '<title>HAL 9000</title>';
            echo "I'm sorry Dave, I'm afraid I can't do that.";
        }
    }
}
/* End of file cron.php */
/* Location: /tapp/application/controllers/cron.php */
