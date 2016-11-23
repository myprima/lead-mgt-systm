<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class adminController extends CI_Controller {
	public function index()
	{
		$this->load->helper('url');
		$this->load->library('form_validation');
		
		$this->load->view('admin/registeradmin');
	}

	public function viewalladmin()
	{
		$this->load->helper('url');
		$this->load->view('admin/manageadmin');
	}

	public function insert()
	{
		$this->load->helper(array('form', 'url'));
		//$this->load->helper('url');
		// Including Validation Library
		$this->load->library('form_validation');
		//$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		// Validating Name Field
		$this->form_validation->set_rules('txtname', 'Username', 'trim|required|min_length[4]|max_length[50]|xss_clean');
		// Validating Email Field
		$this->form_validation->set_rules('txtEmail', 'Email', 'trim|required|valid_email');
		// Validating Mobile no. Field
		$this->form_validation->set_rules('txtContactNo', 'Mobile No.', 'trim|required|regex_match[/^[0-9]{10}$/]');
			// Validating Address Field
		$this->form_validation->set_rules('txtAddress', 'Address', 'trim|required');

		$this->form_validation->set_rules('txtPassword', 'Password', 'trim|required|min_length[6]|max_length[20]');
		
		$this->form_validation->set_rules('txtConfPassword', 'Password Confirmation', 'trim|required|matches[txtPassword]');
		
		

		if ($this->form_validation->run() == FALSE)
		{
			//echo "Reached in Error";
			$this->load->view('admin/registeradmin');
		}
		else
		{
			$this->load->model('adminmodel');
			$data= array(
				'Name' => $this->input->post('txtname'),
				'Email' => $this->input->post('txtEmail'),
				'ContactNo' => $this->input->post('txtContactNo'),
				'Address' => $this->input->post('txtAddress'),
				'Password' => $this->input->post('txtPassword'),
				'IsActive' => 'true',
				'DateCreated' => date("Y-m-d")
				);
			if ($this->adminmodel->createadmin($data)) {
				$this->session->set_flashdata('msg', 'Admin Created');
				redirect('admin/adminController');
			}
			else
			{
				$this->session->set_flashdata('msg', 'Unexpected Error Occurred !');
				redirect('admin/adminController');
			}
		}
	}

	public function update()
	{
		$this->load->helper(array('form', 'url'));
		//$this->load->helper('url');
		// Including Validation Library
		$this->load->library('form_validation');
		//$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		// Validating Name Field
		$this->form_validation->set_rules('txtname', 'Username', 'trim|required|min_length[5]|max_length[50]|xss_clean');
		// Validating Email Field
		$this->form_validation->set_rules('txtEmail', 'Email', 'trim|required|valid_email');
		// Validating Mobile no. Field
		$this->form_validation->set_rules('txtContactNo', 'Mobile No.', 'trim|required|regex_match[/^[0-9]{10}$/]');
			// Validating Address Field
		$this->form_validation->set_rules('txtAddress', 'Address', 'trim|required');

		$this->form_validation->set_rules('txtPassword', 'Password', 'trim|required|min_length[6]|max_length[20]');
		
		$this->form_validation->set_rules('txtConfPassword', 'Password Confirmation', 'trim|required|matches[txtPassword]');
		
		$this->load->model('adminmodel');

		if ($this->form_validation->run() == FALSE)
		{
			//echo "Reached in Error";


			$data['email'] = $this->adminmodel->getemail();
			$this->load->view('admin/updateadmin',$data);
		}
		else
		{
			$data= array(
				'Name' => $this->input->post('txtname'),
				'ContactNo' => $this->input->post('txtContactNo'),
				'Address' => $this->input->post('txtAddress'),
				'Password' => $this->input->post('txtPassword'),
				'IsActive' => 'true',
				'DateCreated' => date("Y-m-d")
				);
			$email=$this->input->post('txtEmail');
			if ($this->adminmodel->updateadmin($data,$email)) {
				$this->session->set_flashdata('msg', 'Admin Updated');
				redirect('pages/upadmin');

			}
			else
			{
				$this->session->set_flashdata('msg', 'Unexpected Error Occurred !');
				/*
				echo "<script>alert('Form Submitted Successfully....!!!! ');</script>";
				$this->index();
				*/
				redirect('pages/upadmin');
			}
		}
	}

	public function updateisactive()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('adminmodel');
		$Isactive=$this->input->post('txtisactive');
		$Id=$this->input->post('txtId');
		$setisactive='';
		if($Isactive=='Enabled')
		{
			$setisactive='false';
		}
		else
		{
			$setisactive='true';
		}
		if($this->adminmodel->updateisactive($Id,$setisactive))
		{
			redirect('admin/pages/madmin');
		}
		else
		{
			echo "<script> alert('Some Error Occurred!!!')</script>";
			redirect('admin/pages/madmin');
		}
	}

	public function GetAdminByEmail()
	{
		$this->load->helper(array('form', 'url'));
		//$this->load->helper('url');
		// Including Validation Library
		$this->load->library('form_validation');
		
		// Validating Email Field
		$this->form_validation->set_rules('ddadminemail', 'Email', 'trim|required|valid_email');
		$this->load->model('adminmodel');
		if ($this->form_validation->run() == FALSE)
		{
			//echo post('ddadminemail');
			$data['name']['value'] = "";
			$data['emailedit']['value'] = "";
			$data['contact']['value'] = "";
			$data['address']['value'] = "";
			$data['password']['value'] = "";

			$data['email'] = $this->adminmodel->getemail();
			echo "<script>alert('Please Select Email To Search !!! ');</script>";
			$this->load->view('admin/updateadmin',$data);
		}
		else
		{
			
			$email= $this->input->post('ddadminemail');
			$query=$this->adminmodel->getdatabyemail($email);
			$data['name']['value'] = $query['Name'];
			$data['emailedit']['value'] = $query['Email'];
			$data['contact']['value'] = $query['ContactNo'];
			$data['address']['value'] = $query['Address'];
			$data['password']['value'] = $query['Password'];
			$data['email'] = $this->adminmodel->getemail();
			$this->load->view('admin/updateadmin',$data);
		}
	}

	public function GetAdminByEmailManage()
	{
		$this->load->helper(array('form', 'url'));
		//$this->load->helper('url');
		// Including Validation Library
		$this->load->library('form_validation');
		
		// Validating Email Field
		$this->form_validation->set_rules('ddadminemail', 'Email', 'trim|required|valid_email');
		$this->load->model('adminmodel');
		if ($this->form_validation->run() == FALSE)
		{
			//echo post('ddadminemail');
			$data['email'] = $this->adminmodel->getemail();
			echo "<script>alert('Please Select Email To Search !!! ');</script>";
			$this->load->view('admin/manageadmin',$data);
		}
		else
		{
			
			$email= $this->input->post('ddadminemail');
			$data['adminsdetails']=$this->adminmodel->getadminbyemail($email);
			
			$data['email'] = $this->adminmodel->getemail();
			$this->load->view('admin/manageadmin',$data);
		}
	}
	function changepassword()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtoldpassword', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtnewpassword', 'New', 'trim|required');
		$this->form_validation->set_rules('txtconfirmpassword', 'Password Confirmation', 'trim|required|matches[txtnewpassword]');
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('admin/profile');
		}
		else
		{
			$this->load->model('adminmodel');
			$oldpass=$this->input->post('txtoldpassword');
			$newpass=$this->input->post('txtnewpassword');
			$id=$this->session->userdata('Id');
			$result=$this->adminmodel->updatepassword($id,$oldpass,$newpass);
			if ($result) {
				$this->session->set_flashdata('msg', 'Password Updated');
				redirect('admin/pages/profile');
			}
			else if($result==false)
			{
				$this->session->set_flashdata('msg', 'Please check password!');
				redirect('admin/pages/profile');
			}
			else
			{
				$this->session->set_flashdata('msg', 'Some Error Ocurred while updating');
				redirect('admin/pages/profile');
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
