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

	public function importlead()
	{
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->model('clientmodel');
		$this->load->model('leadmodel');
		$data['client']=$this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
		$data['templead']=$this->leadmodel->gettempleads($this->session->userdata('Id'));
		$this->load->view('telecaller/importlead',$data);  
	}

	public function updateclient()
	{
		$this->load->model('leadmodel');
		$this->load->helper(array('form', 'url'));
		$clientid= $this->input->post('ddClientId');
		$tempid=$this->input->post('id');
		if($this->leadmodel->tempupdateclient($clientid,$tempid))
		{
			echo json_encode('true');
		}
		else
		{
			echo json_encode('false');
		}
	}

	public function getclientbyemail()
	{
		$this->load->model('clientmodel');
		$this->load->helper(array('form', 'url'));
		$Id= $this->input->post('ddClientId');
		$data['clients']=$this->clientmodel->getclientsbyclientemail($Id);
		echo json_encode($data);
	}
	public function insertlead()
	{
		$this->load->model('leadmodel');
		if ($this->leadmodel->insert($this->session->userdata('Id'))) {
			$this->session->set_flashdata('msg', 'Lead Assigned');
			redirect('telecaller/telecaller/newlead');		
		}
		else{
			$this->session->set_flashdata('msg', 'Some Error Occured !!');
			redirect('telecaller/telecaller/newlead');
		}
	}
	public function insert()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtname', 'Price', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcontact', 'Contact No', 'trim|required|min_length[10]|max_length[12]|xss_clean');
		$this->form_validation->set_rules('txtemail', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('txtaddress', 'Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtclientid', 'Client', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txttotalleads', 'Client', 'trim|required');
		$this->form_validation->set_rules('txtassignedleads', 'Client', 'trim|required|callback_checkequal[txttotalleads]');
		$this->form_validation->set_rules('txtdesc', 'Description', 'trim|required');
		if ($this->form_validation->run() == FALSE)
		{
         	$this->load->model('clientmodel');
         	$this->load->model('leadmodel');
         	$data['client']=$this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
         	$data['templead']=$this->leadmodel->getalltempLeadsbytelecaller($this->session->userdata('Id'));
         	$this->load->view('telecaller/newlead',$data);
		}
		else
		{
			$this->load->model('leadmodel');
			$id=$this->input->post('txtclientid');
			$data= array(
				'ClientId' => $this->input->post('txtclientid'),
				'Name' => $this->input->post('txtname'),
				'Contact' => $this->input->post('txtcontact'),
				'Email' => $this->input->post('txtemail'),
				'Address' => $this->input->post('txtaddress'),
				'Description' => $this->input->post('txtdesc'),
				'CreatedBy'=> $this->session->userdata('Id'),
				'DateCreated'=> date("Y-m-d")
				 );
			if ($this->leadmodel->tempinsert($data,$id)) {
				$this->session->set_flashdata('msg', 'Lead Assigned');
				redirect('telecaller/telecaller/newlead');		
			}
			else{
				$this->session->set_flashdata('msg', 'Some Error Occured !!');
				redirect('telecaller/telecaller/newlead');
			}
		}
	}

	public function insertbycsv()
	{
				/*$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('file','File', 'required');
		*/
		$success=true;
		$this->load->model('leadmodel');

		if(isset($_POST["submit"]))
		{
			$file = $_FILES['file']['tmp_name'];
			$handle = fopen($file, "r");
			$c = 0;
			$row = 1;
			while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
			{
				if($row == 1 || $row==2){ 
					$row++; 
					continue; 
				}
/*				if($filesop[5]==null || $filesop[5]=='')
				{
					continue;
				}*/
				$data=null;
			//$id=$this->input->post('txtclientid');
			$data= array(
				'Name' => $filesop[0],
				'Contact' => $filesop[1],
				'Email' => $filesop[2],
				'Address' => $filesop[3],
				'Description' => $filesop[4],
				'CreatedBy'=> $this->session->userdata('Id'),
				'DateCreated'=> date("Y-m-d")
				 );
			$result=$this->leadmodel->tempinsert($data,null);
				if ($result!=false) {
/*					$payment= array(
						'ClientId' => $result, 
						'Paid' => $filesop[7],
						'Created_By' => $filesop[12],
						'Payment_Date' => $filesop[11]
						);
					if($this->clientmodel->insertpayment($payment,1))
					{

					}
					else
					{
						$success=false;
					}*/
				}
				else
				{
					$success=false;
				}
				//$num = count($filesop);
/*				$name = $filesop[0];
				$email = $filesop[1];
				echo $name.' '.$email.'<br/>';*/
				//$sql = mysql_query("INSERT INTO csv (name, email) VALUES ('$name','$email')");
			}
			if($success==true)
			{
				$this->session->set_flashdata('msg', 'Lead Assigned');
				redirect('telecaller/LeadController/importlead');
			}
			else
			{
				$this->session->set_flashdata('msg', 'Some Error Occured !!');
				redirect('telecaller/LeadController/importlead');
			}

/*			if($sql){
				echo "You database has imported successfully";
			}else{
				echo "Sorry! There is some problem.";
			}*/
		}
	}

	public function cancellead()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtId', 'Id', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
         	$this->load->model('clientmodel');
         	$this->load->model('leadmodel');
         	$data['client']=$this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
         	$data['templead']=$this->leadmodel->getalltempLeadsbytelecaller($this->session->userdata('Id'));
         	$this->load->view('telecaller/newlead',$data);
		}
		else
		{
			$this->load->model('leadmodel');
			if ($this->leadmodel->tempdelete($this->input->post('txtId'))) {
				$this->session->set_flashdata('msg', 'Lead Deleted');
				redirect('telecaller/telecaller/newlead');		
			}
			else{
				$this->session->set_flashdata('msg', 'Some Error Occured While Deleting Lead!!');
				redirect('telecaller/telecaller/newlead');
			}
		}
	}

	public function checkequal($assigned,$total)
	{
		if($assigned>=$total)
		{
			$this->form_validation->set_message('checkequal', 'Client Package Expired!!! Select Other Client');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function update()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('txtcompanyname', 'Company Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcompanytype', 'Company Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcontact', 'Mobile No', 'trim|required|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('txtemail', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('txtAddress', 'Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcontactperson', 'Contact Person', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtpassword', 'Password', 'trim|required');
		$this->form_validation->set_rules('txttelecallername', 'Tele Caller', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txttotalleads', 'Total Leads', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtpackage', 'Package', 'trim|required|xss_clean');


		$this->load->model('clientmodel');
		if ($this->form_validation->run() == FALSE)
		{
          $data['client']=$this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
          $this->load->view('telecaller/updateclient',$data);  
		}
		else
		{
			$data= array(
				'ClientCompany' => $this->input->post('txtcompanyname'),
				'ClientContact' => $this->input->post('txtcontact'),
				'ClientAddress' => $this->input->post('txtAddress'),
				'Password' => $this->input->post('txtpassword'),
				'IsActive' => 'true',
				'ClientName' => $this->input->post('txtcontactperson'),
				'DateUpdated' => date("Y-m-d"),
				'Email'=>$this->input->post('txtemail'),
				'DealerType'=>$this->input->post('txtcompanytype'),
				'Package'=>$this->input->post('txtpackage'),
				'TotalLeads'=>$this->input->post('txttotalleads'),
				'UpdatedBy'=>$this->input->post('txttelecallerid')
				 );
			$ID=$this->input->post('txtclientid');
			if ($this->clientmodel->updateclient($data,$ID)) {
				$this->session->set_flashdata('msg', 'Client Updated');
				redirect('telecaller/telecaller/updateclient');
			}
			else
			{
				$this->session->set_flashdata('msg', 'Unexpected Error Occurred !');
				redirect('telecaller/telecaller/updateclient');
			}
		}
	}

}