<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VisitorController extends CI_Controller {

	public function index()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->view('telecaller/registercustomer');	
	}

	public function insert()
	{
		$this->load->helper(array('form', 'url'));
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtcompanyname', 'Company Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcompanytype', 'Company Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcontact', 'Mobile No', 'trim|required|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('txtAddress', 'Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcontactperson', 'Contact Person', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcallbackdate', 'Callback Date', 'trim');
		$this->form_validation->set_rules('txtfeedback', 'Feedback', 'trim|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('telecaller/registercustomer');
		}
		else
		{
			 $this->load->model('visitormodel');
			$data= array(
				'TeleCallerID' => $this->session->userdata('Id'),
				'CompanyName' => $this->input->post('txtcompanyname'),
				'VisitorName' => $this->input->post('txtcontactperson'),
				'VisitorType' => $this->input->post('txtcompanytype'),
				'VisitorContact' => $this->input->post('txtcontact'),
				'VisitorAddress' => $this->input->post('txtAddress'),
				'Email' => $this->input->post('txtemail'),
				'EntryDate' => date("Y-m-d"),
				'Status' => $this->input->post('ddStatus'),
				'Transfered'=>'false'
				 );
			$id=$this->visitormodel->createvisitor($data);
			if ($id!='false') {
				if($this->input->post('ddStatus')=='Pending')
				{
					$viewed='false';
				}
				else
				{
					$viewed='true';
				}
				$data1=array(
					'VisitorId' =>$id, 
					'TeleCallerId'=>$this->session->userdata('Id'),
					'ReminderDate'=>$this->input->post('txtcallbackdate'),
					'VisitorRemark'=>$this->input->post('txtfeedback'),
					'viewed'=>$viewed,
					'DateCreated'=>date("Y-m-d")
					);
				if($this->visitormodel->createvisitorhistory($data1,null))
				{
					$this->session->set_flashdata('msg', 'Record Created');
					redirect('telecaller/VisitorController');		
				}
				else
				{
					$this->session->set_flashdata('msg', 'Problem While Inserting Visitor History!!!');
					redirect('telecaller/VisitorController');		
				}
			}
			else
			{
				$this->session->set_flashdata('msg', $id);
				redirect('telecaller/VisitorController');
			}
		}
	}

	public function insertvisitorhistory()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtcompanyname', 'Company Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcompanytype', 'Company Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcontact', 'Mobile No', 'trim|required|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('txtAddress', 'Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcontactperson', 'Contact Person', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txtcallbackdate', 'Callback Date', 'trim');
		$this->form_validation->set_rules('txtfeedback', 'Feedback', 'trim|xss_clean');
		$this->load->model('visitormodel');
		if ($this->form_validation->run() == FALSE)
		{
			//echo "Reached in Error";
			$data['visitor'] = $this->visitormodel->getallvisitor();
          $this->load->view('telecaller/updatecustomer',$data);
		}
		else{

			$data= array(
				'TeleCallerID' => $this->input->post('txttelecallerid'),
				'CompanyName' => $this->input->post('txtcompanyname'),
				'VisitorName' => $this->input->post('txtcontactperson'),
				'VisitorType' => $this->input->post('txtcompanytype'),
				'VisitorContact' => $this->input->post('txtcontact'),
				'VisitorAddress' => $this->input->post('txtAddress'),
				'Email' => $this->input->post('txtemail'),
				'EntryDate' => date("Y-m-d"),
				'Status' => $this->input->post('ddStatus')
				 );
			$id=$this->input->post('txtvisitorid');
			if($this->visitormodel->updatevisitor($data,$id))
			{
				if($this->input->post('ddStatus')=='Pending')
				{
					$viewed='false';
				}
				else
				{
					$viewed='true';
				}
					$data1=array(
					'VisitorId' =>$id, 
					'TeleCallerId'=>$this->input->post('txttelecallerid'),
					'ReminderDate'=>$this->input->post('txtcallbackdate'),
					'VisitorRemark'=>$this->input->post('txtfeedback'),
					'viewed'=>$viewed
					);
				if($this->visitormodel->createvisitorhistory($data1,$id))
				{
					$this->session->set_flashdata('msg', 'Record Updated');
					redirect('telecaller/telecaller/reentry');		
				}
				else
				{
					$this->session->set_flashdata('msg', 'Problem While Inserting Visitor History!!!');
					redirect('telecaller/telecaller/reentry');		
				}
			}
			else
			{
					$this->session->set_flashdata('msg', 'Some Unexpected Error Occured !!!');
					redirect('telecaller/telecaller/reentry');	
			}
		}
	}

	public function Visitorhistorybyid()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddVisitorId', 'Company', 'trim|required');
		$this->load->model('visitormodel');
		if ($this->form_validation->run() == FALSE)
		{
			 $this->load->model('visitormodel');
  			 $data['users'] = $this->visitormodel->getallvisitor();
			 echo "<script>alert('Please Select Company To Search !!! ');</script>";
  			 $this->load->view('telecaller/usercallhistory',$data);
		}
		else
		{
			 $this->load->model('visitormodel');
  			 $data['users'] = $this->visitormodel->getallvisitor();
			 $Id= $this->input->post('ddVisitorId');
			 $data['visitorhistory']=$this->visitormodel->getvisitorhistorybyid($Id);
			 $this->load->view('telecaller/usercallhistory',$data);
		}		
	}

	public function visitorsbyId()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddVisitorId', 'Company', 'trim|required');
		$this->load->model('visitormodel');
		if ($this->form_validation->run() == FALSE)
		{
          $data['visitor'] = $this->visitormodel->getallvisitor();
          echo "<script>alert('Please Select Company To Search !!! ');</script>";
          $this->load->view('telecaller/updatecustomer',$data);
		}
		else
		{
			$Id= $this->input->post('ddVisitorId');
			$data['visitorinfo']=$this->visitormodel->getallvisitorbyId($Id);
                        $data['feedback']= $this->visitormodel->getvisitorhistorybyid($Id);
			$data['visitor'] = $this->visitormodel->getallvisitor();
			$this->load->view('telecaller/updatecustomer',$data);
		}
	}

	public function pendingvisitorsbyid()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddVisitorId', 'Company', 'trim|required');
		$this->load->model('visitormodel');
		if ($this->form_validation->run() == FALSE)
		{
          $data['visitors'] = $this->visitormodel->getpendingvisitor();
          echo "<script>alert('Please Select Company To Search !!! ');</script>";
		  $this->load->view('telecaller/pendingcustomer',$data);
		}
		else
		{
			$visitorId= $this->input->post('ddVisitorId');
			$visitorlastfeedback= $this->input->post('txtvisitorlastfeedback');
			$data['lastfeedback']=$visitorlastfeedback;
                        $data['visitorinfo']=$this->visitormodel->getallvisitorbyId($visitorId);
			$data['visitor'] = $this->visitormodel->getallvisitor();
			$this->load->view('telecaller/updatecustomer',$data);
		}
	}

	public function visitorpending()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('visitormodel');
		$data['visitors'] = $this->visitormodel->getpendingvisitor();
		$this->load->view('telecaller/pendingcustomer',$data);
	}

	public function allpendingrecall()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('visitormodel');
		$data['visitors'] = $this->visitormodel->getallpendingvisitor();
		$this->load->view('telecaller/pendingcustomer',$data);
	}

	public function Notify()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('visitormodel');
		$this->load->model('clientmodel');
		$data['recallnotify'] = $this->visitormodel->getpendingvisitor();
		$data['expiredpackagenotify']=$this->clientmodel->getclientbyexpiredpackage();
		echo json_encode($data);
		//$this->load->view('telecaller/updatecustomer',$data);
	}
}