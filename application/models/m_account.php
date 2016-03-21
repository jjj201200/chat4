<?php

	/**
	 * Created by PhpStorm
	 * User:    Ruo
	 * Date:    2014/6/21
	 * Time:    18:53
	 */
	class M_Account extends CI_Model {
		protected $result           = array ();
		protected $group_power      = '';
		protected $navigation_array = array ();

		public function __construct () {
			parent::__construct ();
			$this->load->helper ('date');
			$this->load->database ();

		}

		/**
		 * login
		 * 添加用户session数据
		 * 设置用户在线状态
		 *
		 * @param string $username
		 *
		 */
		public function sign_in ($username) {
            $user_data = array ('username' => $username, 'log_in' => TRUE);
            $data      = array ('last_time' => mdate ('%Y-%m-%d'), 'last_ip_v4' => $this->GetIP ());
            $this->db->where ('username', $username);
            $this->db->update ('user', $data);
            $this->session->set_userdata ($user_data); //添加session数据
            $this->db->select('uid');
            $this->db->where('username',$username);
            $result = $this->db->get('user')->result()[0];
            $this->session->set_userdata (array('uid'=>$result->uid)); //添加session数据
		}

		public function sign_up ($username, $password, $sign_up_date) {
			$data = array (
				'username'    => $username,
				'password'    => sha1 ($password),
				'signup_date' => $sign_up_date,
				'last_time'   => $sign_up_date . ' ' . mdate ('%H-%i-%a'),
				'last_ip_v4'  => $this->GetIP (),
			);
			$this->db->insert ('user', $data);
		}

		function GetIP () {
			$ip = NULL;
			if (!empty($_SERVER["HTTP_CLIENT_IP"]))
				$ip = $_SERVER["HTTP_CLIENT_IP"];
			else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			else if (!empty($_SERVER["REMOTE_ADDR"]))
				$ip = $_SERVER["REMOTE_ADDR"];
			else
				//无法获取IP地址
				$_SESSION['error'] = 5;

			return $ip;
		}

		/**
		 * get_by_username
		 * 通过用户名获得用户记录
		 *
		 * @param string $username
		 * 用户名
		 *
		 * @return bool
		 */
		public function get_by_username ($username) {
			$this->db->where ('username', $username);
			$query = $this->db->get ('user');
			//return $query->row();                            //不判断获得什么直接返回
			if ($query->num_rows () == 1) {
				return $query->row ();
			} else {
				return FALSE;
			}
		}

		public function get_email ($email) {
			$this->db->where ('e_mail', $email);
			$query = $this->db->get ('user');
			if ($query->num_rows () == 1) {
				return TRUE;
			} else {
				return FALSE;
			}
		}

		/**
		 * password_check
		 * 检查登录是否成功
		 *
		 * @param $username
		 * 用户名
		 * @param $password
		 * 密码
		 *
		 * @return bool
		 */
		public function password_check ($username, $password) {
			if ($user = $this->get_by_username ($username)) {
				return $user->password == sha1($password) ? TRUE : FALSE;
			}

			return FALSE; //当用户名不存在时
		}

		/**
		 * init_user
		 * 登陆成功时，初始化用户对象
		 *
		 * @param $username
		 */
		public function init_user ($username) {
			$this->db->select ('user_id, username, group_name,user.group_id, group_power');
			$this->db->join ('dbgroup', 'user.group_id = dbgroup.group_id');
			$this->db->where ('username', $username);
			$result            = $this->db->get ('user')->row ();
			$this->group_power = $result->group_power;
			$this->group_id    = $result->group_id;
			$this->user_id    = $result->user_id;
			$this->session->set_userdata ('group_power', $this->group_power); /*设置用户权限8位16进制数*/
			$this->session->set_userdata ('group_id', $this->group_id); /*设置用户所在组别编号*/
			$this->session->set_userdata ('user_id', $this->user_id); /*设置用户编号*/
		}

		public function search_user ($data) {
			$this->db->select ('user_id, username,e_mail,tel,sex,last_time,last_ip_v4, group_name,user.group_id');
			$this->db->from ('user');
			$this->db->where ($data);
			$this->db->join ('dbgroup', 'user.group_id = dbgroup.group_id');
			$query = $this->db->get ();

			return $query->result_array ();
		}

		/**
		 * init_navigation
		 * 初始化后台访问导航
		 * @return array
		 */
		public function init_navigation () {
			$query    = $this->db->get ('dbnavigation');
			$user_acl = str_pad (decbin (hexdec ($this->group_power)), 32, "0", STR_PAD_LEFT);
			foreach ($query->result () as $row) {
				$n2 = str_pad (decbin (hexdec ($row->acl)), 32, "0", STR_PAD_LEFT);
				for ($i = 0; $i < 32; ++$i) {
					if ($user_acl{$i} == $n2{$i} && $n2{$i} == 1) {
						array_push ($this->navigation_array, $row->nname);
						break;
					}
				}
			}

			return $this->navigation_array;
		}

		public function hasRight ($rightCode) {
			$query = str_pad (decbin (hexdec ($this->session->userdata ('group_power'))), 32, "0", STR_PAD_LEFT);
			if (is_array ($rightCode)) {
				$a = TRUE;
				foreach ($rightCode as $key => $value) {
					$a = $a & $this->hasRight ($value);
				}

				return $a;
			} else {
				$right_acl = str_pad (decbin (decbin ($rightCode)), 32, "0", STR_PAD_LEFT);
				for ($i = 0; $i < 32; ++$i) {
					if ($query{$i} == $right_acl{$i} && $right_acl{$i} == 1) {
						return TRUE;
					}
				}
			}

			return FALSE;
		}

		public function getGroup () {
			$query = $this->db->get ('dbgroup');
			$array = array ();
			foreach ($query->result () as $key => $row) {
				array_push ($array, array ($row->group_id, urlencode ($row->group_name), $row->group_power));
			}

			return $array;
		}

		public function del_user ($user_id) {
			$this->db->where ('user_id', $user_id);
			$query = $this->db->get ('user');
			$query = $query->row ();
			if ($query->group_id < 2 && !$this->hasRight (2)) {
				return FALSE;
			} elseif (!$this->hasRight (5)) {
				return FALSE;
			}
			$this->db->delete ('user', array ('user_id' => $user_id));

			return TRUE;
		}

		public function edit_user_group ($user_id, $group_id) {
			$data = array (
				'group_id' => $group_id
			);
			$this->db->where ('user_id', $user_id);
			$this->db->update ('user', $data);

			return TRUE;
		}
	}




	/* End of file jm_account.php */