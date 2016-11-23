<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ClientController extends CI_Controller {

	public  function clientsbytelecaller()
	{
		$this->load->helper(array('form', 'url'));
		//$this->load->helper('url');
		// Including Validation Library
		$this->load->library('form_validation');
		
		// Validating Email Field
		$this->form_validation->set_rules('ddtelecaller', 'Telecaller', 'trim|required');
		$this->load->model('telecallermodel');
		$this->load->model('clientmodel');
		if ($this->form_validation->run() == FALSE)
		{
			$data['email'] = $this->telecallermodel->gettelecaller();
			echo "<script>alert('Please Select Email To Search !!! ');</script>";

			$this->load->view('admin/teleconfirmedclient',$data);
		}
		else
		{
			$Id= $this->input->post('ddtelecaller');
			$data['clients']=$this->clientmodel->getclientsbyteleid($Id);
			$data['email'] = $this->telecallermodel->gettelecaller();
			$this->load->view('admin/teleconfirmedclient',$data);
		}
	}

	public function clientsdetails()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddClient', 'Client', 'trim|required|valid_email');
		$this->load->model('clientmodel');
		if($this->form_validation->run() == FALSE)
		{
			$data['clients'] = $this->clientmodel->getallclients();
			echo "<script>alert('Please Select Client To Search !!! ');</script>";
			$this->load->view('admin/clientDetails',$data);
		}
		else
		{
			$email= $this->input->post('ddClient');
			$data['clientdetail']=$this->clientmodel->getclientsbyclientemail($email);
			$data['clientpayment']=$this->clientmodel->getclientpaymentdetailbyid($data['clientdetail'][0]['Id']);
			$data['clientpackagehistory']=$this->clientmodel->getclientpacakgehistorydatebyid($data['clientdetail'][0]['Id']);
			$data['clients'] = $this->clientmodel->getallclients();
			$this->load->view('admin/clientDetails',$data);
		}
	}

	public function Packagehistorydetails()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddClientdate', 'Joining Date', 'trim|required');
		$this->form_validation->set_rules('txtid', 'Joining Date', 'trim|required');
		$this->load->model('clientmodel');
		if($this->form_validation->run() == FALSE)
		{
			echo "<script>alert('Please Select Joining Date To Search !!! ');</script>";
			$data['clients'] = $this->clientmodel->getallclients();
			$this->load->view('admin/clientDetails',$data);
		}
		else
		{
			$Id= $this->input->post('txtid');
			$date=$this->input->post('ddClientdate');
			$data['clientdetail']=$this->clientmodel->getclientsbyclientemail($email);
			$data['clientpayment']=$this->clientmodel->getclientpaymentdetailbyid($data['clientdetail'][0]['Id']);
			$data['clientpackagehistory']=$this->clientmodel->getclientpacakgehistorydatebyid($Id);
			$data['clients'] = $this->clientmodel->getallclients();
			$this->load->view('admin/clientDetails',$data);
		}
	}


	public function clientsbypackage()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddpackage', 'Package', 'trim|required');
		$this->load->model('clientmodel');
		if($this->form_validation->run() == FALSE)
		{
			$data['package']=$this->clientmodel->distinctpackages();
			$data['clients'] = $this->clientmodel->getallclients();
			echo "<script>alert('Please Select Package To Search !!! ');</script>";
			$this->load->view('admin/managecustomer',$data);
		}
		else
		{
			$package= $this->input->post('ddpackage');
			$data['package']=$this->clientmodel->distinctpackages();
			$data['clients'] = $this->clientmodel->getallclientsbypackage($package);
			$this->load->view('admin/managecustomer',$data);
		}	
	}

	public function clientsbydate()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtfrom','Date From', 'trim|required');
		$this->form_validation->set_rules('txtto','Date To', 'trim|required|callback_comparedate[txtfrom]');
		$this->load->model('clientmodel');
		$this->load->model('telecallermodel');
		if($this->form_validation->run() == FALSE)
		{
			$data['email'] = $this->telecallermodel->getemail();
			echo "<script>alert('Select Proper Date ');</script>";
			$this->load->view('admin/teleconfirmedclient',$data);
		} 
		else
		{
			$startdate= $this->input->post('txtfrom');
			$enddate=$this->input->post('txtto');
			$data['email'] = $this->telecallermodel->getemail();
			$data['clients'] = $this->clientmodel->getallclientsbydate($startdate,$enddate);
			$this->load->view('admin/teleconfirmedclient',$data);
		}	
	}

	public function manageclientsbydate()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtfrom','Date From', 'trim|required');
		$this->form_validation->set_rules('txtto','Date To', 'trim|required|callback_comparedate[txtfrom]');
		$this->load->model('clientmodel');
		$this->load->model('telecallermodel');
		if($this->form_validation->run() == FALSE)
		{
			$data['package']=$this->clientmodel->distinctpackages();
			$data['clients'] = $this->clientmodel->getallclients();
			echo "<script>alert('Select Proper Date ');</script>";
			$this->load->view('admin/managecustomer',$data);
		} 
		else
		{
			$startdate= $this->input->post('txtfrom');
			$enddate=$this->input->post('txtto');
			$data['package']=$this->clientmodel->distinctpackages();
			$data['clients'] = $this->clientmodel->getallclientsbydate($startdate,$enddate);
			$this->load->view('admin/managecustomer',$data);
		}	
	}

	public function updateclientpayment()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtId','Client Id', 'trim|required');
		$this->form_validation->set_rules('txtbalance','balance', 'trim|required');
		$this->form_validation->set_rules('txtpayamount','Payment Amount', 'trim|required|callback_comparepayment[txtbalance]');
		$this->form_validation->set_rules('txtpaydate','Payment Date', 'trim|required');
		$this->load->model('clientmodel');
		if($this->form_validation->run() == FALSE)
		{
			$data['package']=$this->clientmodel->distinctpackages();
			$data['clients'] = $this->clientmodel->getallclients();
			echo "<script>alert('Invalid Date or Amount !!!');</script>";
			$this->load->view('admin/clientpayment',$data);
		} 
		else
		{
			$payment= array(
				'ClientId' => $this->input->post('txtId'),
				'Paid' => $this->input->post('txtpayamount'),
				'Created_By' => $this->session->userdata('Id'),
				'Payment_Date' =>$this->input->post('txtpaydate') 
				);
			if($this->clientmodel->updatepayment($payment,$this->input->post('txtId'),$this->input->post('txtpayamount')))
			{
			    $data['package']=$this->clientmodel->distinctpackages();
			    $data['clients'] = $this->clientmodel->getallclients();
			    echo "<script>alert('Payment Updated');</script>";
			    $this->load->view('admin/clientpayment',$data);
			}
			else
			{
				$data['package']=$this->clientmodel->distinctpackages();
				$data['clients'] = $this->clientmodel->getallclients();
				echo "<script>alert('Error Occured while updating Payment');</script>";
				$this->load->view('admin/clientpayment',$data);
			}
		}
	}
	public function comparedate($enddate,$startdate){
		$time1 = strtotime($enddate);
		$time2 = strtotime($startdate);
		$end=date('Y-m-d',$time1);
		$start=date('Y-m-d',$time2);
		if($end > $start){
			return TRUE;
		}
		else 
		{
			$this->form_validation->set_message('comparedate', 'End date should be greater than Start Date.');
			return FALSE;
		}
	}

	public function comparepayment($payamount,$balance)
	{
		$bal=$this->input->post('txtbalance');
		if($payamount<=$bal)
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}