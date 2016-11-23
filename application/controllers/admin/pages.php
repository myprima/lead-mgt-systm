<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('url');
    $this->load->library('form_validation');
    if($this->session->userdata('Id')  && $this->session->userdata('Type')=='admin'){
    }
    else
    {
      if(!$this->session->userdata('redirected')){
        $this->session->set_userdata('redirected',TRUE);
        redirect('welcome');
      }
    }
  }
  public function index()
  {
    $this->load->view('admin/starter');
  }
  
  function ctele()
  {
    $this->load->helper('url');
    $this->load->library('form_validation');
    $this->load->view('admin/registertelecaller');
    
  }
  function clientpayment()
  {
    $this->load->model('clientmodel');
    $data['package']=$this->clientmodel->distinctpackages();
    $data['clients'] = $this->clientmodel->getallclients();
    $this->load->view('admin/clientpayment',$data);
  }

  function mclient()
  {
    $this->load->helper('url');
    $this->load->library('form_validation');
    $this->load->model('clientmodel');
    $data['package']=$this->clientmodel->distinctpackages();
    $data['clients'] = $this->clientmodel->getallclients();
    $this->load->view('admin/managecustomer',$data);
  }
  function cdetails()
  {
    $this->load->helper('url');
    $this->load->library('form_validation');
    $this->load->model('clientmodel');
    $data['clients'] = $this->clientmodel->getallclients();
    $this->load->view('admin/clientDetails',$data);
  }
  function callrecord()
  {
   $this->load->helper('url');
   $this->load->library('form_validation');
   $this->load->model('telecallermodel');
   $data['email'] = $this->telecallermodel->gettelecaller();
   $this->load->view('admin/visitors',$data);
   
 }
 function tcclient()
 {
  $this->load->helper('url');
  $this->load->library('form_validation');
  $this->load->model('telecallermodel');
  $data['email'] = $this->telecallermodel->gettelecaller();
  $this->load->view('admin/teleconfirmedclient',$data);
  
}
function mtele()
{

  $this->load->helper('url');
  $this->load->library('form_validation');
  $this->load->model('telecallermodel');
  $data['adminsdetails']=$this->telecallermodel->gettelecaller();
  $data['email'] = $this->telecallermodel->gettelecaller();

  $this->load->view('admin/managetelecaller',$data);
  
}
function upatetele()
{

  $this->load->helper('url');
  $this->load->library('form_validation');
  $this->load->model('telecallermodel');
  $data['name']['value'] = "";
  $data['emailedit']['value'] = "";
  $data['contact']['value'] = "";
  $data['address']['value'] = "";
  $data['password']['value'] = "";
  $data['email'] = $this->telecallermodel->gettelecaller();
  $this->load->view('admin/updatetelecaller',$data);
}
function cadmin()
{
  $this->load->helper('url');
  $this->load->library('form_validation');
  $this->load->view('admin/registeradmin');
}
function upadmin()
{
  $this->load->helper('url');
  $this->load->library('form_validation');
  $this->load->model('adminmodel');
  $data['name']['value'] = "";
  $data['emailedit']['value'] = "";
  $data['contact']['value'] = "";
  $data['address']['value'] = "";
  $data['password']['value'] = "";
  $data['email'] = $this->adminmodel->getemail();
  $this->load->view('admin/updateadmin',$data);
  
}
function madmin()
{
  $this->load->helper('url');
  $this->load->library('form_validation');
  $this->load->model('adminmodel');
  $data['adminsdetails']=$this->adminmodel->getadmin();
  $data['email'] = $this->adminmodel->getemail();
  $this->load->view('admin/manageadmin',$data);
}
function searchlead()
{
  $this->load->helper('url');
  $this->load->library('form_validation');
  $this->load->model('leadmodel');
  $this->load->model('clientmodel');
  $data['leads'] = $this->leadmodel->getallLeads();
  $data['clients']=$this->clientmodel->getallclients();
  $this->load->view('admin/searchclient',$data);
}
function leadby()
{
  $this->load->helper('url');
  $this->load->library('form_validation');
  $this->load->model('leadmodel');
  $this->load->model('telecallermodel');
  $this->load->model('clientmodel');
  $data['clients'] = $this->clientmodel->getallclients();
  $data['leads'] = $this->leadmodel->getallLeads();
  $data['tele'] = $this->telecallermodel->gettelecaller();
  $this->load->view('admin/clientbytelecaller',$data);
}
function profile()
{
  $this->load->view('admin/profile');
}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */