<?php

	/**
	 * Created by PhpStorm
	 * User:    Ruo
	 * Date:    2014/6/20
	 * Time:    22:14
	 */
	class M_Captcha extends CI_Model {

		private $vals = array ();
		private $cap  = NULL;

		public function __construct () {
			parent::__construct ();
			$this->vals = array (
				'img_path'   => './captcha/',
				'img_url'    => base_url () . 'captcha/',
				'img_width'  => 60,
				'img_height' => 25,
				'expiration' => 7200
			);
		}

//		public function create_captcha () {
//			$this->refresh ();
//
//			return $this->cap['image'];
//		}

		public function get_word () {
			return $this->session->userdata('captcha');
		}

		public function get_image () {
			if (!isset($cap)) {
				create_captcha ();
			}

			return $this->cap['image'];
		}

		/**
		 * refresh
		 */
		public function refresh () {
			$this->cap = create_captcha ($this->vals);
			$this->set_word_to_sesstion ();
			return $this->cap['path'];
		}

		private function set_word_to_sesstion () {
			$this->session->set_userdata ('captcha', strtolower($this->cap['word']));
		}
	}



	/* End of file jm_captcha.php */