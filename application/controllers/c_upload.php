<?php

/**
 * Created by PhpStorm.
 * User: Ruo
 * Date: 2014/12/19
 * Time: 13:38
 */
class C_Upload extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'm_captcha',
            'm_upload'
        ]);
        $this->load->library('upload');
    }

    public function upload()
    {
        echo $this->form_validation->run('upload');
        if ($this->form_validation->run('upload') == FALSE) {
            echo 1;
            return false;
        } else {
            $tucao = $this->input->post('tucao');
            $data = [
                'date' => date("Y-m-d"),
                'time' => date("H:i:s"),
                'publisher' => $this->session->userdata('uid'),
                'tucao' => addslashes($tucao)
            ];
            echo $this->m_upload->upload($data, $_FILES);
        }
    }

    function captcha_check($capcha)
    {
        $s_capcha = $this->m_captcha->get_word();
            $this->session->unset_userdata('captcha');
        if (strtolower($capcha) === strtolower($s_capcha)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('captcha_check', 'captcha_check');
            return FALSE;
        }
    }
}