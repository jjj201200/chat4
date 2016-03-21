<?php

/**
 * Created by PhpStorm
 * User:    Ruo
 * Date:    2014/6/21
 * Time:    18:53
 */
class M_Upload extends CI_Model {
    protected $result = [];
    protected $group_power = '';
    protected $navigation_array = [];

    public function __construct() {
        parent::__construct();
        $this->load->helper('date');
        $this->load->database();

    }

    public function upload($data, $file) {
        if (!empty($file)) {
            $images_sql = "";
            $sounds_sql = "";
            $imageType = [
                'jpg',
                'jpeg',
                'gif',
                'bmp',
                'svg',
                'png'
            ];
            $soundType = [
                'mp3',
                'wma',
                'wav',
                'mod',
                'ogg',
                'm4a'
            ];
            foreach ($_FILES["file_upload"]['name'] as $key => $name) {
                $timestamp = date("Y_m_d") . " " . date("H_i_s") . " " . rand(0, 9) . rand(0, 9) . rand(0, 9);
                echo 'name:' . $name . '<br />';
                $explode = explode('.', $name);
                $type = strtolower(end($explode));
                echo in_array($type, $imageType);
                echo 'type:' . $type . '<br />';
                if (in_array($type, $imageType)) {
                    move_uploaded_file($_FILES["file_upload"]["tmp_name"][ $key ], "img/" . $timestamp . "." . $type);
                    $images_sql .= $timestamp . '.' . $type . '-';
                } else if (in_array($type, $soundType)) {
                    move_uploaded_file($_FILES["file_upload"]["tmp_name"][ $key ], "sound/" . $timestamp . "." . $type);
                    $sounds_sql .= $timestamp . '.' . $type . '-';
                } else {
                    return FALSE;
                }
            }
            if ($images_sql != "") {
                $images_sql = substr($images_sql, 0, strlen($images_sql) - 1);
            }
            $data['imgJson'] = $images_sql;

            if ($sounds_sql != "") {
                $sounds_sql = substr($sounds_sql, 0, strlen($sounds_sql) - 1);
            }
            $data['soundJson'] = $sounds_sql;

        }
        $this->db->insert('chat', $data);
        return true;
    }

}




/* End of file jm_account.php */