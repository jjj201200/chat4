<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_query_chat');
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     * @param string $index
     * @param string $data
     */
    public function index($index = 'index', $data = [])
    {
        //log in?
        if (isset($this->session->userdata['log_in']) && $this->session->userdata['log_in'] == TRUE) {
            $data['log_in'] = $this->session->userdata['log_in'];
            $data['username'] = $this->session->userdata['username'];
        }
        $data['year_array'] = $this->m_query_chat->query_menu();

        $this->load->view($index, $data);
    }

    public function get_captcha()
    {
        $this->load->model('m_captcha');
        echo $this->m_captcha->refresh();
    }

    public function query_chat()
    {
        $data=[];
        $this->load->model('m_query_chat');
        $year = $this->input->get('year');
        $month = str_pad($this->input->get('month'), 2, '0');
        $day = str_pad($this->input->get('day'), 2, '0');
        $data['chat'] = $this->m_query_chat->query_content($year, $month, $day);
        $data['login'] = $this->session->userdata('log_in')?true:false;
        echo json_encode($data);
    }
    public function del_chat(){
        $this->load->model('m_query_chat');
        $caht_id = $this->input->post('chat_id');
        echo $this->m_query_chat->del_chat($caht_id);
    }
    public function check_code(){
        $this->load->model('m_captcha');
        $check_code = $this->input->post('check_code');
        if(strtolower($check_code)==$this->m_captcha->get_word()){
            echo 1;
        }else echo 0;
    }
    public function deploy(){
        $secret = '密钥';
        //获取http 头
        $headers = array();
        //Apache服务器才支持getallheaders函数
        if (!function_exists('getallheaders')) {
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        }else
        {
            $headers = getallheaders();
        }
        //github发送过来的签名
        $hubSignature = $headers['X-Hub-Signature'];
        list($algo, $hash) = explode('=', $hubSignature, 2);

        // 获取body内容
        $payload = file_get_contents('php://input');

        // 计算签名
        $payloadHash = hash_hmac($algo, $payload, $secret);

        // 判断签名是否匹配
        if ($hash === $payloadHash) {
            //调用shell
            echo exec("./deploy.sh");
        }
    }
}

/* End of file page.php */
/* Location: ./application/controllers/page.php */