<?php

/**
 * Created by PhpStorm
 * User:    Ruo
 * Date:    2014/6/21
 * Time:    19:22
 */
class C_Account extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        //			$this->load->model (array ('jm_captcha', 'jm_messager', 'jm_account','jm_system'));
        $this->load->model(['m_account', 'm_captcha']);
        /*定义验证错误时返回的消息格式*/
        $this->form_validation->set_message ('required', '%s' . $this->lang->line ('not_set'));
        $this->form_validation->set_message ('min_length', '%s' . $this->lang->line ('unformed_min_length_6'));
        $this->form_validation->set_message ('max_length', '%s' . $this->lang->line ('unformed_max_length_6'));
        $this->form_validation->set_message ('exact_length', '%s' . $this->lang->line ('unformed_length_5'));
        $this->form_validation->set_message ('alpha_dash', '%s' . $this->lang->line ('illegal_char_alpha_dash'));
        $this->form_validation->set_message ('valid_email', '%s' . $this->lang->line ('invalid_email'));
        $this->form_validation->set_message ('xss_clean', '%s' . $this->lang->line ('xss_clean'));
    }

    /**
     * login
     */
    public function sign_in()
    {
        $this->_username = $this->input->post('username'); /*用户名*/
        if ($this->form_validation->run('login') == FALSE) {
        } else { /*登陆成功*/
            $this->m_account->sign_in($this->_username); /*设定为登录*/
        }
        redirect(base_url(), 'location');
    }

    /**
     * del_user
     * 删除用户
     */
    public function del_user()
    {
        $this->jm_system->hasLogin();
        $user_id = $this->input->post('user_id');
        if (!$this->jm_account->del_user($user_id)) {
            $this->jm_messager->set_right_error(3);
        }
        $this->jm_messager->set_message(102);
        redirect('/admin/view/user', 'location'); //删除成功重定向
    }

    /**
     * search_user
     * 查询用户
     */
    public function search_user()
    {
        $this->jm_system->hasLogin();

        $this->form_validation->set_error_delimiters('<span class="warning">', '</span>'); /*定义错误输出格式*/
        if ($this->form_validation->run('search_user') == FALSE) {
            $this->session->set_flashdata('form_error', validation_errors());
        } else {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $group = $this->input->post('group');
            if (empty($username) && empty($email) && empty($group)) {
                $this->jm_messager->set_right_error(2);
                redirect('/admin/user', 'location'); //搜索失败重定向
            }
            $data = [];
            if (!empty($username)) {
                $data['username'] = $username;
            }
            if (!empty($email)) {
                $data['e_mail'] = $email;
            }
            if (!empty($group)) {
                $data['dbuser.group_id'] = $group;
            }
            $query = $this->jm_account->search_user($data);
            $data['search_user'] = $query;
        }
        $data['url'] = base_url();
        $data['page'] = 'user';
        $this->load->view('admin/header', $data);
        if ($this->session->userdata('logged_in') === TRUE) {
            $data['navigation'] = $this->session->userdata('navigation');
            $data['username'] = $this->session->userdata('username');
            $data['index'] = 'user';
            $this->load->view('admin/navigation', $data);
            /*获取用户添加时的可添加分组*/
            $group_list = $this->jm_account->getGroup();

            foreach ($group_list as $key => $value) {
                if ($value[0] == '0') {
                    unset($group_list[$key]);
                }
            }
            /*
             * TODO
             * 精细筛选可添加组别
             * */
            $this->session->set_userdata('add_group_list', $group_list);
            $data['group_list'] = $group_list;

        }
        $this->load->model('jm_system');
        $data['connect_email'] = $this->jm_system->get_connect_email()['value'];
        $this->session->set_userdata('in_admin', TRUE);
        $this->load->view('admin/user', $data);
        $this->load->view('admin/footer', $data);
        //				redirect ('/admin/user', 'location'); //搜索成功重定向
    }

    public function eidt_user()
    {
        $this->jm_system->hasLogin();

        $this->form_validation->set_error_delimiters('<span class="warning">', '</span>'); /*定义错误输出格式*/
        if ($this->form_validation->run('edit_user') == FALSE) {
            $this->session->set_flashdata('form_error', validation_errors());
        } else {
            $this->jm_account->edit_user_group($this->input->post('user_id'), $this->input->post('group'));
            $this->jm_messager->set_message(103);
        }
        redirect('/admin/view/user', 'location'); //搜索失败重定向
    }

    /**
     * sign_up
     * 注册用户
     */
    public function sign_up()
    {
        if ($this->form_validation->run('sign_up') == FALSE) {
            $this->session->set_flashdata ('form_error', validation_errors ());
        } else {
            $_username = $this->input->post('username'); /*用户名*/
            $_password = $this->input->post('password'); /*密码*/
            $sign_up_date = mdate('%Y-%m-%d'); /*注册时间*/
            $this->m_account->sign_up($_username, $_password, $sign_up_date); /*将注册信息写入数据库*/
            $this->m_account->sign_in($_username); /*设定为登录*/
        }
        redirect(base_url(), 'refresh');
    }

    /**
     * logout
     * 登出函数
     */
    public function sign_out()
    {

        $this->session->unset_userdata('log_in');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('uid');
        redirect(base_url(), 'refresh');
    }

    /**
     * username_check
     * 检查用户名是否存在
     *
     * @param $username
     *
     * @return bool
     */
    function username_check($username)
    {
        if ($this->m_account->get_by_username($username)) {

            return TRUE;
        }
        $this->form_validation->set_message('username_check', $this->lang->line('username_check')); //设定错误消息格式

        return FALSE;

    }

    /**
     * has_username_check
     * 检查是否已经注册了该用户名
     *
     * @param $username
     *
     * @return bool
     */
    public function has_username_check($username)
    {
        if (!$this->m_account->get_by_username($username))
            return TRUE;

        $this->form_validation->set_message('has_username_check', $this->lang->line('has_username_check')); //设定错误消息格式
        return FALSE;
    }

    /**
     * password_confirm_check
     * 检查密码和确认密码是否相等
     *
     * @param $password_confirm
     * @param $password
     *
     * @return bool
     */
    public function password_confirm_check($password_confirm, $password)
    {
        if ($password_confirm == $this->input->post($password))
            return TRUE;

        $this->form_validation->set_message('password_confirm_check', $this->lang->line('password_confirm_check')); //设定错误消息格式
        return FALSE;
    }

    /**
     * sex_check
     * 检查性别编号是否合法
     *
     * @param $sex
     *
     * @return bool
     */
    public function sex_check($sex)
    {
        if ($sex != '0' || $sex != '1')
            return FALSE;
        $this->form_validation->set_message('sex_check', $this->lang->line('sex_check')); //设定错误消息格式
        return TRUE;
    }

    /**
     * email_check
     * 检查注册用邮箱是否已被使用
     *
     * @param $email
     *
     * @return bool
     */
    public function email_check($email)
    {
        if ($this->jm_account->get_email($email)) {
            $this->form_validation->set_message('email_check', $this->lang->line('email_check')); //设定错误消息格式
            return FALSE;
        }

        return TRUE;
    }

    /**
     * group_check
     * 检查当前用户是否被允许添加指定分组的用户
     *
     * @param $group
     *
     * @return bool
     */
    public function group_check($group)
    {
        foreach ($this->session->userdata('add_group_list') as $key => $value) {
            if ($value[0] == $group) {
                return TRUE;
            }
        }
        $this->form_validation->set_message('group_check', $this->lang->line('group_check')); //设定错误消息格式
        return FALSE;
    }

    /**
     * tel_check
     * 检查手机号是否合法
     *
     * @param $mobilephone
     *
     * @return bool
     */
    public function tel_check($mobilephone)
    {
        if (preg_match("/^13[0-9]{1}[0-9]{8}$|15[012356789]{1}[0-9]{8}$|18[0256789]{1}[0-9]{8}$/", $mobilephone))
            return TRUE;

        $this->form_validation->set_message('tel_check', $this->lang->line('tel_check')); //设定错误消息格式
        return FALSE;
    }

    /**
     * password_check
     * 检查用户名和密码是否都正确
     *
     * @param $password
     *
     * @return bool
     */
    function password_check($password)
    {
        $this->load->model('m_account');
        if ($this->m_account->password_check($this->_username, $password)) {
            return TRUE;
        } else {

            return FALSE;
        }
    }


    /**
     * captcha_check
     * 验证码校验
     *
     * @param $capcha
     *
     * @return bool
     */
    function captcha_check($capcha)
    {
        $s_capcha = $this->m_captcha->get_word();
        if (strtolower($capcha) === strtolower($s_capcha)) {
            $this->session->unset_userdata('captcha');

            return TRUE;
        } else {

            $this->session->unset_userdata('captcha');
            $this->form_validation->set_message('captcha_check', 'captcha_check');

            return FALSE;
        }
    }
}



/* End of file c_account.php */