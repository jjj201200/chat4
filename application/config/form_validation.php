<?php
/**
 * Created by PhpStorm
 * User:    Ruo
 * Date:    2014/6/21
 * Time:    20:44
 */
$config = [ //登录表单的规则
            'login'   => [
                [
                    'field' => 'username',
                    'label' => 'lang:username',
                    'rules' => 'trim|required|xss_clean|min_length[3]|max_length[20]|alpha_dash|callback_username_check'
                ],
                [
                    'field' => 'password',
                    'label' => 'lang:password',
                    'rules' => 'trim|required|xss_clean|min_length[6]|max_length[20]|callback_password_check'
                ],
                [
                    'field' => 'code',
                    'label' => 'lang:code',
                    'rules' => 'trim|required|xss_clean|exact_length[5]|callback_captcha_check'
                ]
            ],
            'sign_up' => [
                [
                    'field' => 'username',
                    'label' => 'lang:username',
                    'rules' => 'trim|required|xss_clean|min_length[3]|max_length[20]|alpha_dash|callback_has_username_check'
                ],
                [
                    'field' => 'password',
                    'label' => 'lang:password',
                    'rules' => 'trim|required|xss_clean|min_length[6]|max_length[20]'
                ],
                [
                    'field' => 'password_confirm',
                    'label' => 'lang:password_confirm',
                    'rules' => 'trim|required|xss_clean|min_length[6]|max_length[20]|callback_password_confirm_check[password]'
                ],
                [
                    'field' => 'code',
                    'label' => 'lang:code',
                    'rules' => 'trim|required|xss_clean|exact_length[5]|callback_captcha_check'
                ]
            ],
            'upload'  => [
            ]
];


/* End of file form_validation.php */