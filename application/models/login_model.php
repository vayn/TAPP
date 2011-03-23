<?php
/**
 * Author:
 *    Vayn a.k.a. VT <vayn@vayn.de>
 *    http://elnode.com
 *
 *    File:             login_model.php
 *    Create Date:      2011年03月22日 星期二 17时55分45秒
 */
class Login_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_userinfo($user, $password) {
        $this->db->select('id, user');
        $query = $this->db->get_where('users', array('user' => $user, 'password'=>$password));
        return $query;
    }
}
/* End of file login_model.php */
