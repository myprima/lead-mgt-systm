<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ClientController extends CI_Controller {


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

	public function getclientbyemail()
	{
		$this->load->model('clientmodel');
		$this->load->helper(array('form', 'url'));
		$Id= $this->input->post('ddClientId');
		$data['clients']=$this->clientmodel->getclientsbyclientemail($Id);
		echo json_encode($data);
	}

	public function insert()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|xss_clean');
		$this->form_validation->set_rules('advance', 'Advance', 'trim|required|xss_clean');
		$this->form_validation->set_rules('totalleads', 'Total Leads', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pass', 'Password', 'trim|required');
		$this->form_validation->set_rules('txtvisitoremail', 'Email Id', 'trim|required|valid_email');
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->model('visitormodel');
			//$data['visitors']=$this->visitormodel->getvisitorsbyemail($this->session->userdata('Email'));
			echo "<script>alert('Fill all required field')</sript>";
			$this->load->view('telecaller/visitors'); 
		}
		else
		{
			$this->load->model('clientmodel');
			$data= array(
				'ClientCompany' => $this->input->post('txtcompanyname'),
				'ClientName' => $this->input->post('txtvisitorname'),
				'DealerType' => $this->input->post('txtvisitortype'),
				'ClientContact' => $this->input->post('txtvisitorcontact'),
				'ClientAddress' => $this->input->post('txtvisitoraddress'),
				'Email' => $this->input->post('txtvisitoremail'),
				'Package'=> $this->input->post('price'),
				'Paid'=> $this->input->post('advance'),
				'TotalLeads'=> $this->input->post('totalleads'),
				'AssignedLeads'=>0,
				'Password'=> $this->input->post('pass'),
				'IsActive'=> 'true',
				'DateCreated'=> date("Y-m-d"),
				'CreatedBy'=> $this->session->userdata('Id')
				);
                       $e_ids=array();
                       $email_ids=array();
                        $q="select Email  from tblclients";
                        $email_ids=$this->db->query($q)->result_array();
                        if(isset($email_ids) && !empty($email_ids)){
                            foreach($email_ids as $e){
                                $e_ids[]=$e['Email'];
                            }
                        }
                      if(in_array(trim($data['Email']), $e_ids)){
                          	$this->session->set_flashdata('msg', 'Email id already entered.');
				redirect('telecaller/telecaller/update');
                      }else{
                        $result=$this->clientmodel->insert($data);
			if ($result!=false) {
				$payment= array(
					'ClientId' => $result, 
					'Paid' => $this->input->post('advance'),
					'Created_By' => $this->session->userdata('Id'),
					'Payment_Date' => date("Y-m-d")
					);
				if($this->clientmodel->insertpayment($payment,$this->input->post('txtid')))
				{

$this->load->library('email');
$config['protocol'] = "smtp";
$config['smtp_host'] = "ssl://smtp.googlemail.com";
$config['smtp_port'] = "465";
$config['smtp_user'] = "marketing@gmail.com"; 
$config['smtp_pass'] = "";
$config['charset'] = "iso-8859-1";
$config['mailtype'] = "html";
$config['newline'] = "\r\n";
$this->email->initialize($config);
$this->email->from('marketing@gmail.com', 'LMS');
$this->email->to($this->input->post('txtvisitoremail'));
$this->email->reply_to('marketing@gmail.com', 'LMS');
$this->email->subject('Welcome');
$this->email->message('Congratulation!!! You have joined Client List of LMS. <br><br><br>Your Login Details are
						<br><br><br><b> Username: '.$this->input->post('txtvisitoremail').'<br> Your Password is: '.$this->input->post('pass').'</b> Please do not share your login details with others.');
$this->email->send();

					$this->session->set_flashdata('msg', 'Client successfully inserted');
					redirect('telecaller/telecaller/update');
		}
				else
				{
					$this->session->set_flashdata('msg', 'Error while inserting client payment record');
					redirect('telecaller/telecaller/update');	
				}
			}
			else{
				$this->session->set_flashdata('msg', 'Sorry!!! Some Problem Occured, Please Try again!');
				redirect('telecaller/telecaller/update');
			}                        
                      }
                  
			
		}
	}


	public function update()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('txtcompanyname', 'Company Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcompanytype', 'Company Type', 'trim|required|xss_clean');
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

	public function clientsdetails()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddClient', 'Client', 'trim|required|valid_email');
		$this->load->model('clientmodel');
		if($this->form_validation->run() == FALSE)
		{
			echo "<script>alert('Please Select Client To Search !!! ');</script>";
			$data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
    		$this->load->view('telecaller/clientDetails',$data);
		}
		else
		{
			$email= $this->input->post('ddClient');
			$data['clientdetail']=$this->clientmodel->getclientsbyclientemail($email);
			$data['clientpayment']=$this->clientmodel->getclientpaymentdetailbyid($data['clientdetail'][0]['Id']);
			$data['clientpackagehistory']=$this->clientmodel->getclientpacakgehistorydatebyid($data['clientdetail'][0]['Id']);
			$data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
    		$this->load->view('telecaller/clientDetails',$data);
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
			$data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
    		$this->load->view('telecaller/clientDetails',$data);
		}
		else
		{
			$Id= $this->input->post('txtid');
			$date=$this->input->post('ddClientdate');
			$data['clientdetail']=$this->clientmodel->getpackagehistorydetails($Id);
			$data['clientpayment']=$this->clientmodel->getpaymenthistorydetailbyidanddate($Id,$date);
			$data['clientpackagehistory']=$this->clientmodel->getclientpacakgehistorydatebyid($Id);
			$data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
    		$this->load->view('telecaller/clientDetails',$data);
		}
	}

	public function clientdetailsforrenew()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddClient', 'Client', 'trim|required|valid_email');
		$this->load->model('clientmodel');
		if($this->form_validation->run() == FALSE)
		{
			echo "<script>alert('Please Select Client To Search !!! ');</script>";
			$data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
    		$this->load->view('telecaller/renew',$data);
		}
		else
		{
			$email= $this->input->post('ddClient');
			$data['clientdetail']=$this->clientmodel->getclientsbyclientemail($email);
			$data['clientpayment']=$this->clientmodel->getclientpaymentdetailbyid($data['clientdetail'][0]['Id']);
			$data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
    		$this->load->view('telecaller/renew',$data);
		}
	}

	public function renewpackage()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtid', 'Id', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtname', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtamount', 'Package Price', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txttotalleads', 'Total Leads', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtnewamount', 'Package Price', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtnewtotalleads', 'Total Leads', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtassignedleads', 'Assign Leads', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtpaid', 'Paid', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtdateconfirmed', 'Date Confirmed', 'trim|required');
		$this->load->model('clientmodel');
		if ($this->form_validation->run() == FALSE)
		{
			$data['clients'] = $this->clientmodel->getclientsbyteleidandexpiredpackage($this->session->userdata('Id'));
    		$this->load->view('telecaller/renew',$data);
		}
		else
		{
			$history= array(
				'ClientId' => $this->input->post('txtid'),
				'Package' => $this->input->post('txtamount'),
				'Paid' => $this->input->post('txtpaid'),
				'TotalLeads' => $this->input->post('txttotalleads'),
				'AssignedLeads' => $this->input->post('txtassignedleads'),
				'Package_From' => $this->input->post('txtdateconfirmed'),
				'Pacakge_To' => date("Y-m-d"),
				'CreatedBy'=>$this->input->post('Id')
				);
			$updatedata= array(
				'Package' => $this->input->post('txtnewamount'),
				'Paid' => 0,
				'TotalLeads' => $this->input->post('txtnewtotalleads'),
				'AssignedLeads' => 0,
				'DateCreated' => date("Y-m-d"),
				'CreatedBy'=>$this->input->post('Id'),
				'DateUpdated' => date("Y-m-d"),
				'UpdatedBy'=>$this->input->post('Id')
				);
			$ID=$this->input->post('txtid');
			$from=$this->input->post('txtdateconfirmed');
			if ($this->clientmodel->renewpackage($history,$updatedata,$ID,$from)) {
				$this->session->set_flashdata('msg', 'Renewal Successfull');
				redirect('telecaller/telecaller/renew');
			}
			else
			{
				$this->session->set_flashdata('msg', 'Renewal Unsuccesfull !!! Some Error Occurred');
				redirect('telecaller/telecaller/renew');
			}
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
			$data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
			echo "<script>alert('Invalid Date or Amount !!!');</script>";
    		$this->load->view('telecaller/clientpayment',$data);
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
			    $data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
			    echo "<script>alert('Payment Updated');</script>";
			    $this->load->view('telecaller/clientpayment',$data);
			}
			else
			{
				$data['package']=$this->clientmodel->distinctpackages();
				$data['clients'] = $this->clientmodel->getclientsbyteleid($this->session->userdata('Id'));
				echo "<script>alert('Error Occured while updating Payment');</script>";
				$this->load->view('telecaller/clientpayment',$data);
			}
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

function test(){


$this->load->library('email');
$config['protocol'] = "smtp";
$config['smtp_host'] = "ssl://smtp.googlemail.com";
$config['smtp_port'] = "465";
$config['smtp_user'] = "marketing@gmail.com"; 
$config['smtp_pass'] = "";
$config['charset'] = "iso-8859-1";
$config['mailtype'] = "html";
$config['newline'] = "\r\n";

$this->email->initialize($config);

$this->email->from('admin@gmail.com', '');
$list = array('admin1@gmail.com');
$this->email->to($list);
$this->email->reply_to('my-email@gmail.com', 'Explendid Videos');
$this->email->subject('This is an email test');
$this->email->message('It is working. Great!');
$this->email->send();
echo "<pre/>";
echo "<br/>------------>";
print_r($this->email->print_debugger());
echo "<br/>----------->";
exit;


}
        
                }