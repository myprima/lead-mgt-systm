<?php
class telecallermodel extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	public function createtellecaller($data)
	{
		if ($this->db->insert('tbltelecallers', $data)) {
			return true;
		} else {
			return false;
		}
	}

	//TO be Deleted
	public function getemail()
	{
		$this->db->select('Email');
		$data=$this->db->get('tbltelecallers');
		return $data;
	}
	//TO be Deleted
	public function getdatabyemail($email) 
	{
		$data=$this->db->get_where('tbltelecallers',array('Email'=>$email));
		return $data->row_array();
	}

	public function getdatabyId($Id) 
	{
		$data=$this->db->get_where('tbltelecallers',array('Id'=>$Id));
		return $data->result_array();
	}
	public function gettelecaller() 
	{
		$data=$this->db->get('tbltelecallers');
		return $data->result_array();
	}

	//To be Deleted
	public function gettelecallerbyemail($email) 
	{
		$data=$this->db->get_where('tbltelecallers',array('Email'=>$email));
		return $data->result_array();
	}
	public function updatetelecaller($data,$Id)
	{ 
		$this->db->where('Id', $Id);
		if($this->db->update('tbltelecallers', $data))
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
		if($this->db->update('tbltelecallers', $data))
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
		$query_str = "SELECT * FROM tbltelecallers where Id=$id and Password='$oldpass'";
		$data = $this->db->query($query_str);
		$rowcount=$data->num_rows();
		if($rowcount>0)
		{
			$data = array(
               'Password' => $newpass
            );
			$this->db->where('Id', $id);
			if($this->db->update('tbltelecallers', $data))
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