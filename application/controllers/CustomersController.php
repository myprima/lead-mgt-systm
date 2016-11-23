<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomersController extends CI_Controller {

	public  function index()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('leadmodel');
		$data['leads'] = $this->leadmodel->getallLeadsbyclient($this->session->userdata('Id'));
		$this->load->view('customers',$data);
	}

	public function customerdetailspage()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->model('leadmodel');
		$data['leads'] = $this->leadmodel->getallLeadsbyclient($this->session->userdata('Id'));
		$this->load->view('LeadDetails',$data);
	}

	public function customerdetail()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtleadid', 'Lead', 'trim|required');
		$this->load->model('leadmodel');
		if($this->form_validation->run() == FALSE)
		{
			echo "<script>alert('Please Select Lead To Search !!! ');</script>";
			$data['leads'] = $this->clientmodel->getallLeadsbyclient($this->session->userdata('Id'));
    		$this->load->view('LeadDetails',$data);
		}
		else
		{
			$id= $this->input->post('txtleadid');
			$data['leaddetail']=$this->leadmodel->getleaddetailsbyclientandleadId($this->session->userdata('Id'),$this->input->post('txtleadid'));
			$data['leads'] = $this->leadmodel->getallLeadsbyclient($this->session->userdata('Id'));
    		$this->load->view('LeadDetails',$data);
		}
	}

	public function UpdateClientFeedback()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtclientid', 'Client Id', 'trim|required');
		$this->form_validation->set_rules('txtfeedback', 'Feedback', 'trim|required');
		$this->load->model('leadmodel');
		if($this->form_validation->run() == FALSE)
		{
			echo "<script>alert('Please Enter Feedback !!! ');</script>";
			$data['leads'] = $this->clientmodel->getallLeadsbyclient($this->session->userdata('Id'));
    		$this->load->view('LeadDetails',$data);
		}
		else
		{
			$id= $this->input->post('txtclientid');
			$feedback= $this->input->post('txtfeedback');
			$data['leaddetail']=$this->leadmodel->getleaddetailsbyclientandleadId($this->session->userdata('Id'),$id);
			$data['leads'] = $this->leadmodel->getallLeadsbyclient($this->session->userdata('Id'));
			if($this->leadmodel->UpdateClientFeedback($id,$feedback))
			{
				echo "<script>alert('Feedback Updated !!');</script>";
			}
			else
			{
				echo "<script>alert('Some Error Occured While Updating !!');</script>";
			}
    		$this->load->view('LeadDetails',$data);
		}
	}
}