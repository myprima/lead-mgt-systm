<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VisitorController extends CI_Controller {

	public  function visitorsbytelecaller()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddtelecaller', 'Email', 'trim|required');
		$this->load->model('telecallermodel');
		$this->load->model('visitormodel');
		if ($this->form_validation->run() == FALSE)
		{
          $data['email'] = $this->telecallermodel->gettelecaller();
          echo "<script>alert('Please Select Email To Search !!! ');</script>";
			$this->load->view('admin/visitors',$data);
		}
		else
		{
			$Id= $this->input->post('ddtelecaller');
			$data['visitors']=$this->visitormodel->getvisitorsbyteleId($Id);
			$data['email'] = $this->telecallermodel->gettelecaller();
			$this->load->view('admin/visitors',$data);
		}
	}

}