<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Telecaller extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        if ($this->session->userdata('Id') && $this->session->userdata('Type') == 'telecaller') {
            
        } else {
            if (!$this->session->userdata('redirected')) {
                $this->session->set_userdata('redirected', TRUE);
                redirect('welcome');
            }
        }
    }

    public function index() {

        $this->load->helper('url');
        $this->load->library('form_validation');
        
        
        $this->load->model('visitormodel');
        $data['totalusers'] = $this->visitormodel->getallvisitorcount();

        $this->load->model('visitormodel');
        $data['totaltodaysusers'] = $this->visitormodel->getalltodaysvisitorcount();

        
        $this->load->model('visitormodel');
        $data['totaltodayspendingcalls'] = $this->visitormodel->getpendingvisitorcount();

        
        $this->load->model('clientmodel');
        $data['totalclients'] = $this->clientmodel->getallclientcount();

        
        $this->load->view('telecaller/starter',$data);
    }

    function entry() {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->view('telecaller/registercustomer');
    }

    function reentry() {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('visitormodel');
        $data['visitor'] = $this->visitormodel->getallvisitor();
        $this->load->view('telecaller/updatecustomer', $data);
    }

    function usercallhistory() {
        $this->load->model('visitormodel');
        $data['users'] = $this->visitormodel->getallvisitor();
        $this->load->view('telecaller/usercallhistory', $data);
    }

    function newlead() {
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('clientmodel');
        $this->load->model('leadmodel');
        $data['client'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
        $data['templead'] = $this->leadmodel->getalltempLeadsbytelecaller($this->session->userdata('Id'));
        $this->load->view('telecaller/newlead', $data);
    }

    function updatelead() {
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('leadmodel');
        $data['leads'] = $this->leadmodel->getallLeadsbytelecaller($this->session->userdata('Id'));
        $this->load->view('telecaller/updatelead', $data);
    }

    function update() {
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('visitormodel');
        //$data['visitors']=$this->visitormodel->getconfirmedvisitorsbyemail($this->session->userdata('Id'));
        $this->load->view('telecaller/visitors');
    }

    function all_data() {
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('visitormodel');
        $data['visitors'] = $this->visitormodel->getconfirmedvisitorsbyemail($this->session->userdata('Id'));
        echo json_encode($data);
    }

    function updateclient() {
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('clientmodel');
        $data['client'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
        $this->load->view('telecaller/updateclient', $data);
    }

    function centry() {
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('clientmodel');
        $data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
        $this->load->view('telecaller/client', $data);
    }

    function clientDetails() {
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('clientmodel');
        $data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
        $this->load->view('telecaller/clientDetails', $data);
    }

    function clientpayment() {
        $this->load->model('clientmodel');
        $data['package'] = $this->clientmodel->distinctpackages();
        $data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
        $this->load->view('telecaller/clientpayment', $data);
    }

    function renew() {
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('clientmodel');
        $data['clients'] = $this->clientmodel->getclientsbyteleidandexpiredpackage($this->session->userdata('Id'));
        $this->load->view('telecaller/renew', $data);
    }

    function profile() {
        $this->load->view('telecaller/profile');
    }

    function changepassword() {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txtoldpassword', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('txtnewpassword', 'New', 'trim|required');
        $this->form_validation->set_rules('txtconfirmpassword', 'Password Confirmation', 'trim|required|matches[txtnewpassword]');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('telecaller/profile');
        } else {
            $this->load->model('telecallermodel');
            $oldpass = $this->input->post('txtoldpassword');
            $newpass = $this->input->post('txtnewpassword');
            $id = $this->session->userdata('Id');
            $result = $this->telecallermodel->updatepassword($id, $oldpass, $newpass);
            if ($result) {
                $this->session->set_flashdata('msg', 'Password Updated');
                redirect('telecaller/telecaller/profile');
            } else if ($result == false) {
                $this->session->set_flashdata('msg', 'Please check password!');
                redirect('telecaller/telecaller/profile');
            } else {
                $this->session->set_flashdata('msg', 'Some Error Ocurred while updating');
                redirect('telecaller/telecaller/profile');
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */