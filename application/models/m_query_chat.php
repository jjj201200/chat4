<?php

/**
 * Created by PhpStorm
 * User:    Ruo
 * Date:    2014/6/21
 * Time:    18:53
 */
class M_Query_chat extends CI_Model {
    protected $result = [];

    public function __construct() {
        parent::__construct();
        $this->load->database();

    }

    public function query_content($year, $month, $day) {
        if (isset($year) && isset($month) && isset($day)) {
            $this->db->select('user.uid,user.username,chat.*');
            $this->db->from('chat');
            $this->db->where('date', $year . "-" . $month . "-" . $day);
            $this->db->join('user', 'chat.publisher = user.uid');
            $result = $this->db->get()->result();
            $array = [];
            foreach ($result as $key => $value) {
                $value->tucao = stripslashes($value->tucao);
                $value->imgJson = explode('-', $value->imgJson);
                $value->soundJson = explode('-', $value->soundJson);
                array_push($array, $value);
            }
            echo json_encode($array);
        } else echo 1;
    }

    public function query_menu() {
        $this->db->select('date');
        $this->db->order_by('date', 'asc');
        $row = $this->db->get('chat')->result_array();
        $year_array = [];
        foreach ($row as $key => $value) {
            $date = explode('-', $value['date']);
            if (!isset($year_array[$date[0]][$date[1]]))
                $year_array[$date[0]][$date[1]] = [];
            if (!in_array($date[2], $year_array[$date[0]][$date[1]]))
                $year_array[$date[0]][$date[1]][] = $date[2];
        }

        return $year_array;
    }

}




/* End of file jm_account.php */