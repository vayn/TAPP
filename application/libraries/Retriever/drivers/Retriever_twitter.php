<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Author:
 *    Vayn a.k.a. VT <vayn@vayn.de>
 *    http://elnode.com
 *
 *    File:             Retriever_twitter.php
 *    Create Date:      2011年03月23日 星期三 04时46分04秒
 */

class Retriever_twitter extends CI_Driver {
    protected $data = array();
    protected $api = '';
    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->helper('function');
    }

    public function retrieve($setting) {
        $this->api = "http://api.twitter.com/statuses/user_timeline/{$setting['twitter']}.json";
        // Get raw Twitter JSON data
        $ret = process($this->api);
        $primitive = json_decode($ret, true);

        // The latest tweet
        $this->data['latest'] = urlparse($primitive[0]['text']);

        // According to amount to filter tweets 
        $this->data['tweets'] = array();
        for ($i = 0; $i < $setting['amount']; $i++) {
            $this->data['tweets'][] = $primitive[$i];
        }

        // According to reply to filter tweets 
        if ($setting['reply'] == 'no') {
            $i = 0;
            foreach ($this->data['tweets'] as $key) {
                if ($key['in_reply_to_user_id'] != '') {
                    unset($this->data['tweets'][$i]);
                }
                else {
                    $tmp_arr[] = $this->data['tweets'][$i];
                }
                $i++;
            }
            $this->data['tweets'] = isset($tmp_arr) ? $tmp_arr : array();
        }
        return $this->data;
    }
}

/* End of file Retriever_twitter.php */
