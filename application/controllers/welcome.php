<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('url');
    $this->load->library('form_validation');
    if($this->session->userdata('Id')){
    }
    else
    {
    }
  }

	function index()
	{
    $this->session->unset_userdata('Id');
    $this->session->unset_userdata('Name');
    $this->session->unset_userdata('Email');
    $this->session->sess_destroy();
    $this->load->view('loginform');
	}
  
  function login()
  {
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->form_validation->set_rules('txtusername', 'Username', 'trim|required');
    $this->form_validation->set_rules('txtpassword', 'Password', 'trim|required');
    if ($this->form_validation->run() == FALSE)
    {
      $this->load->view('loginform');
    }
    else
    {
      $this->load->model('adminmodel');
      $admintype=$this->input->post('ddadmintype');
      $email=$this->input->post('txtusername');
      $pass=$this->input->post('txtpassword');
      $result=$this->adminmodel->login($email,$pass,$admintype);
      if($result==false)
      {
        echo "<script>alert('Invalid Username/Password !!!')</script>";
        $this->load->view('loginform');
      }
      else
      {
        if ($admintype=="Administrator") {
          $login = array(
            'Id' => $result[0]['Id'],
            'Name' => $result[0]['Name'],
            'Email'=>$result[0]['Email'],
            'Type'=>'admin'
          );
          $this->session->set_userdata($login);
          redirect('admin/pages');
        }
        else if($admintype=="Telecaller"){
          $login = array(
            'Id' => $result[0]['Id'],
            'Name' => $result[0]['Name'],
            'Type'=>'telecaller'
          );
          $this->session->set_userdata($login);
          redirect('telecaller/telecaller');   
        }
        else{
          $login = array(
            'Id' => $result[0]['Id'],
            'Name' => $result[0]['ClientName'],
            'Email'=>$result[0]['Email'],
            'Type'=>'client'
          );
          $this->session->set_userdata($login);
          redirect('ClientDashboardController');
        }    
      }
    }
  }
      
      
      function ctele()
      {
       
          $this->load->view('admin/registertelecaller');
      
      }
       function mclient()
      {
       
          $this->load->view('admin/managecustomer');
      
      }
       function cdetails()
      {
       
          $this->load->view('admin/clientDetails');
      
      }
       function callrecord()
      {
       
          $this->load->view('');
      
      }
       function tcclient()
      {
       
          $this->load->view('admin/teleconfirmedclient');
      
      }
       function mtele()
      {
       
          $this->load->view('admin/managetelecaller');
      
      }
         function upatetele()
      {
       
          $this->load->view('admin/updatetelecaller');
      
      }
          function cadmin()
      {
          
          $this->load->view('admin/registeradmin');
      
      }
          function upadmin()
      {
       
          $this->load->view('admin/updateadmin');
      
      }
          function madmin()
      {
       
          $this->load->view('admin/manageadmin');
      
      }
            function searchlead()
      {
       
          $this->load->view('admin/searchclient');
      
      }
             function leadby()
      {
       
          $this->load->view('admin/clientbytelecaller');
      
      }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */