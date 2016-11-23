<?php
class adminmodel extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	public function login($user,$pass,$type)
	{
		if($type=='Administrator')
		{
			$data=$this->db->get_where('tbladmins',array('Email'=>$user,'Password'=>$pass,'IsActive'=>'true'));
			if($data->num_rows()>0)
			{
				return $data->result_array();
			}
			else
			{
				return false;
			}
		}
		else if($type=='Telecaller')
		{
			$data=$this->db->get_where('tbltelecallers',array('TelecallerId'=>$user,'Password'=>$pass,'IsActive'=>'true'));
			if($data->num_rows()>0)
			{
				return $data->result_array();
			}
			else
			{
				return false;
			}
		}
		else
		{
			$data=$this->db->get_where('tblclients',array('Email'=>$user,'Password'=>$pass,'IsActive'=>'true'));
			if($data->num_rows()>0)
			{
				return $data->result_array();
			}
			else
			{
				return false;
			}
		}
	}
	public function createadmin($data)
	{
		if ($this->db->insert('tbladmins', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function getemail()
	{
		$this->db->select('Email,Name');
		$data=$this->db->get('tbladmins');
		return $data->result_array();
	}
	public function getdatabyemail($email) 
	{
		$data=$this->db->get_where('tbladmins',array('Email'=>$email));
		return $data->row_array();
	}
	public function getadmin() 
	{
		$data=$this->db->get('tbladmins');
		return $data->result_array();
	}
	public function getadminbyemail($email) 
	{
		$data=$this->db->get_where('tbladmins',array('Email'=>$email));
		return $data->result_array();
	}
	public function updateadmin($data,$email)
	{
		$this->db->where('Email', $email);
		if($this->db->update('tbladmins', $data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function updateisactive($Id,$Isactive)
	{
		$this->db->where('Id', $Id);
		$this->db->set('IsActive', $Isactive);
		if($this->db->update('tbladmins', $data))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function updatepassword($id,$oldpass,$newpass)
	{
		$query_str = "SELECT * FROM tbladmins where Id=$id and Password='$oldpass'";
		$data = $this->db->query($query_str);
		$rowcount=$data->num_rows();
		if($rowcount>0)
		{
			$data = array(
				'Password' => $newpass
				);
			$this->db->where('Id', $id);
			if($this->db->update('tbladmins', $data))
			{
				return true;
			} 
			else
			{
				return 'error updating';
			}
		}
		else
		{
			return false;
		}
	}
}