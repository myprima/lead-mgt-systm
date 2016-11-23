<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LeadController extends CI_Controller {


	public function getclient()
	{
		$this->load->helper(array('form', 'url'));
		
		$this->load->library('form_validation');
		$this->load->model('clientmodel');
		$this->form_validation->set_rules('ddClientId', 'Company', 'trim|required');
		if ($this->form_validation->run() == FALSE)
		{
			$data['client']=$this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
			echo "<script>alert('Please Select Company To Search !!! ');</script>";
			$this->load->view('telecaller/updateclient',$data);  
		}
		else
		{
			$Id= $this->input->post('ddClientId');
			$data['visitorinfo']=$this->clientmodel->getclientsbyclientemail($Id);
			$data['client']=$this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
			$this->load->view('telecaller/updateclient',$data);  
		}
	}

	public function getleadbyclient()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddclient', 'Client', 'trim|required');
		$this->load->model('leadmodel');
		$this->load->model('clientmodel');
		if ($this->form_validation->run() == FALSE)
		{
			$data['leads'] = $this->leadmodel->getallLeads();
			$data['clients']=$this->clientmodel->getallclients();
			echo "<script>alert('Please Select Client To Search !!! ');</script>";
			$this->load->view('admin/searchclient',$data); 
		}
		else
		{
			$Id= $this->input->post('ddclient');
			$data['leads'] = $this->leadmodel->getallLeadsbyclient($Id);
			$data['clients']=$this->clientmodel->getallclients();
			$this->load->view('admin/searchclient',$data);  
		}
	}

	public function getleadbytele()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddtele', 'Tele caller', 'trim|required');
		$this->load->model('leadmodel');
		$this->load->model('telecallermodel');
		$this->load->model('clientmodel');
		if ($this->form_validation->run() == FALSE)
		{
			$data['clients'] = $this->clientmodel->getallclients();
			$data['leads'] = $this->leadmodel->getallLeads();
			$data['tele'] = $this->telecallermodel->gettelecaller();
			echo "<script>alert('Please Select Telecaller To Search !!! ');</script>";
			$this->load->view('admin/clientbytelecaller',$data);
		}
		else
		{
			$Id= $this->input->post('ddtele');
			$data['clients'] = $this->clientmodel->getallclients();
			$data['leads'] = $this->leadmodel->getallLeadsbytelecaller($Id);
			$data['tele'] = $this->telecallermodel->gettelecaller();
			$this->load->view('admin/clientbytelecaller',$data);
		}
	}

	public function getleadbyteleandclient()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddtele1', 'Tele caller', 'trim|required');
		$this->form_validation->set_rules('ddclient', 'Client', 'trim|required');
		$this->load->model('leadmodel');
		$this->load->model('telecallermodel');
		$this->load->model('clientmodel');
		if ($this->form_validation->run() == FALSE)
		{
			$data['clients'] = $this->clientmodel->getallclients();
			$data['leads'] = $this->leadmodel->getallLeads();
			$data['tele'] = $this->telecallermodel->gettelecaller();
			echo "<script>alert('Please Select Telecaller And Client To Search !!! ');</script>";
			$this->load->view('admin/clientbytelecaller',$data);
		}
		else
		{
			$teleId= $this->input->post('ddtele1');
			$clientId=$this->input->post('ddclient');
			$data['clients'] = $this->clientmodel->getallclients();
			$data['leads'] = $this->leadmodel->getallLeadsbyteleandclient($clientId,$teleId);
			$data['tele'] = $this->telecallermodel->gettelecaller();
			$this->load->view('admin/clientbytelecaller',$data);
		}
	}


	public function getleadbydate()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtfrom','Date From', 'trim|required');
		$this->form_validation->set_rules('txtto','Date To', 'trim|required');
		$this->load->model('clientmodel');
		$this->load->model('leadmodel');
		if($this->form_validation->run() == FALSE)
		{
			$data['leads'] = $this->leadmodel->getallLeads();
			$data['clients']=$this->clientmodel->getallclients();
			echo "<script>alert('Select Proper Date ');</script>";
			$this->load->view('admin/searchclient',$data);
		} 
		else
		{
			$startdate= $this->input->post('txtfrom');
			$enddate=$this->input->post('txtto');
			$data['clients']=$this->clientmodel->getallclients();
			$data['leads'] = $this->leadmodel->getallLeadssbydate($startdate,$enddate);
			$this->load->view('admin/searchclient',$data);
		}	
	}

}