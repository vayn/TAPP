<?php
/**
 * Author:
 *    Vayn a.k.a. VT <vayn@vayn.de>
 *    http://elnode.com
 *
 *    File:             Setting_model.php
 *    Create Date:      2011年03月22日 星期二 18时08分29秒
 */
class Setting_model extends CI_Model {
    public function __construct() {
        parent::__construct();
   }

    public function get_setting($uid='') {
        if (!empty($uid)) {
            $this->db->from('setting');
            $this->db->where('uid', $uid);
            $query = $this->db->get()->result();
            if (empty($query)) {
                $this->db->from('setting');
                $this->db->where('uid', 1);
                $query = $this->db->get()->result();
            }
            $query = $query[0];
        }
        else {
            $query = $this->db->get('setting')->result();
        }
        return $query;
    }

    public function update_setting($setting) {
        if ($this->db->get_where('setting', array('uid' => $setting['uid']))->result()) {
            $this->db->where('uid', $setting['uid']);
            $this->db->update('setting', $setting);
        }
        else {
            $this->db->insert('setting', $setting);
        }
    }

    public function get_latest() {
        $this->db->select('latest');
        $query = $this->db->get_where('setting', array('uid' => $this->session->userdata('uid')))->result();
        if ( ! empty($query)) {
            $query = $query[0];
            $latest = $query->latest;
        }
        else {
            $latest = '';
        }
        return $latest;
    }

    public function reset($uid) {
        $this->db->where('uid', $uid);
        $this->db->update('setting', array('latest' => '', 'update_time' => 0));
    }

    public function get_user($id) {
        $this->db->select('user');
        $query = $this->db->get_where('users', array('id' => $id))->result();
        return $query[0]->user;
    }
}

/* End of file Setting_model.php */
