<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyplanController extends CI_Controller {

	public  function index()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('clientmodel');
		$data['clientdetail']=$this->clientmodel->getclientsbyclientemail($this->session->userdata('Email'));
		$this->load->view('myplan',$data);
	}
}

