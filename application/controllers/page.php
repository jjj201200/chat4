<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page extends CI_Controller {
    public function __construct() {
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
    public function index($index = 'index', $data = []) {
        //log in?
        if (isset($this->session->userdata['log_in']) && $this->session->userdata['log_in'] == TRUE) {
            $data['log_in'] = $this->session->userdata['log_in'];
            $data['username'] = $this->session->userdata['username'];
        }
        $data['year_array'] = $this->m_query_chat->query_menu();

        $this->load->view($index, $data);
    }

    public function get_captcha() {
        $this->load->model('m_captcha');
        echo $this->m_captcha->refresh();
    }

    public function query_chat() {
        $this->load->model('m_query_chat');
        $year = $this->input->get('year');
        $month = str_pad($this->input->get('month'), 2, '0');
        $day = str_pad($this->input->get('day'), 2, '0');
        echo $this->m_query_chat->query_content($year, $month, $day);
    }
}

/* End of file page.php */
/* Location: ./application/controllers/page.php */