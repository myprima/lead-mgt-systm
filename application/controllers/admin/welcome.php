<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
          
          $this->load->helper('url');
          $this->load->library('form_validation');
          $this->load->model('telecallermodel');
          $data['adminsdetails']=$this->telecallermodel->gettelecaller();
          $data['email'] = $this->telecallermodel->getemail();

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

          $data['email'] = $this->telecallermodel->getemail();
          $this->load->view('admin/updatetelecaller',$data);
          
          $this->load->helper('url');
          $this->load->library('form_validation');
          $this->load->view('admin/updatetelecaller');
      
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
       
          $this->load->view('admin/searchclient');
      
      }
             function leadby()
      {
       
          $this->load->view('admin/clientbytelecaller');
      
      }
   
 
              
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */