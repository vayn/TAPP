<?php
/**
 * Author:
 *    Vayn a.k.a. VT <vayn@vayn.de>
 *    http://elnode.com
 *
 *    File:             logout.php
 *    Create Date:      2011年03月23日 星期三 03时52分09秒
 */
class Logout extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $url = $this->config->item('base_url');

        if ($this->session->userdata('uid')) {
            $this->session->sess_destroy();
            redirect($url);
        }
        else {
            redirect($url);
        }
        
    }
}
/* End of file logout.php */
