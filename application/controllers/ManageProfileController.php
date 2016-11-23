<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ManageProfileController extends CI_Controller {

	public  function index()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->view('profile');
	}

	public function changepassword()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtoldpassword', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtnewpassword', 'New', 'trim|required');
		$this->form_validation->set_rules('txtconfirmpassword', 'Password Confirmation', 'trim|required|matches[txtnewpassword]');
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('profile');
		}
		else
		{
			$this->load->model('clientmodel');
			$oldpass=$this->input->post('txtoldpassword');
			$newpass=$this->input->post('txtnewpassword');
			$id=$this->session->userdata('Id');
			$result=$this->clientmodel->updatepassword($id,$oldpass,$newpass);
			if ($result) {
				$this->session->set_flashdata('msg', 'Password Updated');
				redirect('ManageProfileController');
			}
			else if($result==false)
			{
				$this->session->set_flashdata('msg', 'Please check password!');
				redirect('ManageProfileController');
			}
			else
			{
				$this->session->set_flashdata('msg', 'Some Error Ocurred while updating');
				redirect('ManageProfileController');
			}
		}
	}
}