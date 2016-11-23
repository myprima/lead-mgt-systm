<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TelecallerController extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->view('admin/registertelecaller');
	}

	public function insert()
	{
		$this->load->helper(array('form', 'url'));
		//$this->load->helper('url');
		// Including Validation Library
		$this->load->library('form_validation');
		//$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		// Validating Name Field
		$this->form_validation->set_rules('txtname', 'Username', 'trim|required|min_length[5]|max_length[50]|xss_clean');
		// Validating Mobile no. Field
		$this->form_validation->set_rules('txtContactNo', 'Mobile No.', 'trim|required|regex_match[/^[0-9]{10}$/]');
			// Validating Address Field
		$this->form_validation->set_rules('txtAddress', 'Address', 'trim|required');

		$this->form_validation->set_rules('txtPassword', 'Password', 'trim|required|min_length[5]|max_length[20]');
		
		$this->form_validation->set_rules('txtUserId', 'User Id', 'trim|required|is_unique[tbltelecallers.TelecallerId]');
		
		$this->form_validation->set_rules('txtConfPassword', 'Password Confirmation', 'trim|required|matches[txtPassword]');
		

		if ($this->form_validation->run() == FALSE)
		{
			//echo "Reached in Error";
			$this->load->view('admin/registertelecaller');
		}
		else
		{
			$this->load->model('telecallermodel');
			$data= array(
				'TelecallerId'=>$this->input->post('txtUserId'),
				'Name' => $this->input->post('txtname'),
				'ContactNo' => $this->input->post('txtContactNo'),
				'Address' => $this->input->post('txtAddress'),
				'Password' => $this->input->post('txtPassword'),
				'IsActive' => 'true',
				'DateCreated' => date("Y-m-d")
				);
			if ($this->telecallermodel->createtellecaller($data)) {
				$this->session->set_flashdata('msg', 'Tele Caller Created');
				redirect('admin/TelecallerController');
			}
			else
			{
				$this->session->set_flashdata('msg', 'Unexpected Error Occurred !');
				redirect('admin/TelecallerController');
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

		// Validation
		$this->form_validation->set_rules('txtname', 'Username', 'trim|required|min_length[5]|max_length[50]|xss_clean');
		$this->form_validation->set_rules('txtContactNo', 'Mobile No.', 'trim|required|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('txtAddress', 'Address', 'trim|required');
		$this->form_validation->set_rules('txtPassword', 'Password', 'trim|required|min_length[5]|max_length[20]');
		$this->form_validation->set_rules('txtUserId', 'User Id', 'trim|required');
		$this->form_validation->set_rules('txtConfPassword', 'Password Confirmation', 'trim|required|matches[txtPassword]');
		$this->form_validation->set_rules('txtId', 'Id', 'trim|required');
		$this->load->model('telecallermodel');
		if ($this->form_validation->run() == FALSE)
		{
			$data['email'] = $this->telecallermodel->getemail();
			$this->load->view('admin/updatetelecaller',$data);
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
			$id=$this->input->post('txtId');
			if ($this->telecallermodel->updatetelecaller($data,$id)) {
				$this->session->set_flashdata('msg', 'Tele Caller Updated');
				redirect('welcome/upatetele');
			}
			else
			{
				$this->session->set_flashdata('msg', 'Unexpected Error Occurred !');
				redirect('welcome/upatetele');
			}
		}
	}

	public function updateisactive()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('telecallermodel');
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
		if($this->telecallermodel->updateisactive($Id,$setisactive))
		{
			redirect('admin/pages/mtele');
		}
		else
		{
			echo "<script> alert('Some Error Occurred!!!')</script>";
			redirect('admin/pages/mtele');
		}
	}

	public function GetAdminByEmail()
	{
		$this->load->helper(array('form', 'url'));
		//$this->load->helper('url');
		// Including Validation Library
		$this->load->library('form_validation');
		
		// Validating Email Field
		$this->form_validation->set_rules('ddadminemail', 'Telecaller', 'trim|required');
		$this->load->model('telecallermodel');
		if ($this->form_validation->run() == FALSE)
		{
			//echo post('ddadminemail');
			$data['name']['value'] = "";
			$data['emailedit']['value'] = "";
			$data['contact']['value'] = "";
			$data['address']['value'] = "";
			$data['password']['value'] = "";
			$data['teleid']['value']="";
			$data['email'] = $this->telecallermodel->gettelecaller();
			echo "<script>alert('Please Select Email To Search !!! ');</script>";
			$this->load->view('admin/updatetelecaller',$data);
		}
		else
		{
			
			$Id= $this->input->post('ddadminemail');
			$data['telecallerdetails']=$this->telecallermodel->getdatabyId($Id);
			$data['email'] = $this->telecallermodel->gettelecaller();
			$this->load->view('admin/updatetelecaller',$data);
		}
	}

	public function GetTeleCallerByEmailManage()
	{
		$this->load->helper(array('form', 'url'));
		// Including Validation Library
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('ddadminemail', 'Telecaller', 'trim|required');
		$this->load->model('telecallermodel');
		if ($this->form_validation->run() == FALSE)
		{
			$data['email'] = $this->telecallermodel->gettelecaller();
			echo "<script>alert('Please Select Email To Search !!! ');</script>";
			$this->load->view('admin/managetelecaller',$data);
		}
		else
		{
			
			$Id= $this->input->post('ddadminemail');
			$data['adminsdetails']=$this->telecallermodel->getdatabyId($Id);
			
			$data['email'] = $this->telecallermodel->gettelecaller();
			$this->load->view('admin/managetelecaller',$data);
		}
	}
}