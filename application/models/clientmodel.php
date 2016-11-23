<?php
class clientmodel extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function insert($data)
	{
		if ($this->db->insert('tblclients', $data)) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

        
//counting
    public function getallclientcount() {
        $data = $this->db->count_all_results('tblclients');
        return $data;
    }
        
	public function insertpayment($data,$visitorid)
	{
		if ($this->db->insert('tblclientpayment', $data)) {
			$query=array(
				'Transfered'=>'true'
				);
			$this->db->where('Id', $visitorid);
			if($this->db->update('tblvisitorentry',$query))
			{
				return true;
			}
			else
			{
			 	return false;
			}
		} else {
			return false;
		}
	}

	public function updateclient($data,$id)
	{
		$this->db->where('Id', $id);
		if($this->db->update('tblclients', $data))
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
		$query_str = "SELECT * FROM tblclients where Id=$id and Password='$oldpass'";
		$data = $this->db->query($query_str);
		$rowcount=$data->num_rows();
		if($rowcount>0)
		{
			$data = array(
               'Password' => $newpass
            );
			$this->db->where('Id', $id);
			if($this->db->update('tblclients', $data))
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
		return $data->result_array();
	}

	public function updatepayment($data,$id,$paid)
	{
		if ($this->db->insert('tblclientpayment', $data)) {
			$query=array(
				'Transfered'=>'true'
				);
			$this->db->where('Id', $id);
			$this->db->set('Paid', 'Paid+'.$paid, FALSE);
			if($this->db->update('tblclients'))
			{
				return true;
			}
			else
			{
				return false;
			}
		} else {
			return false;
		}
	}

	public function renewpackage($historydata,$updatdata,$id,$from)
	{
		if ($this->db->insert('tblclientpackagehistory', $historydata)) {
			$this->db->where('Id', $id);
			if($this->db->update('tblclients', $updatdata))
			{
				$updateOld=array(
					'Old'=>'true'
					);
				$this->db->where('ClientId', $id);
				if($this->db->update('tblclientpayment', $updateOld))
				{
					$query_str = "Update tblclientpayment SET Package_From='$from' where ClientId='$id' and Package_From IS NULL";
					if($this->db->query($query_str))
					{
						$newquery_str = "Update tblleads SET Package_From='$from',Old='true' where ClientId='$id' and Package_From IS NULL";
						if($this->db->query($newquery_str))
						{
							return true;
						}
						else{
							return false;
						}
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		} else {
			return false;
		}
	}

	public function getemail()
	{
		$this->db->select('Email');
		$data=$this->db->get('tbltelecallers');
		return $data;
	}
	public function getdatabyemail($email) 
	{
		$data=$this->db->get_where('tblvisitorentry',array('Email'=>$email));
		return $data->row_array();
	}
	public function gettelecaller() 
	{
		$data=$this->db->get('tbltelecallers');
		return $data->result_array();
	}

	//Search telecaller and client by client email 
	public function getclientsbyclientemail($email) 
	{
		$query_str = "SELECT c.*,t.Name FROM tblclients as c,tbltelecallers as t  where c.CreatedBy = t.Id and  c.Email = '$email'";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Search Client payment detail by Client Id
	public function getclientpaymentdetailbyid($id)
	{
		$data=$this->db->get_where('tblclientpayment',array('ClientId'=>$id,'Old'=>'false'));
		return $data->result_array();
	}

	//Search telecaller and client by telecaller ID 
	public function getclientsbyteleid($ID) 
	{
		$query_str = "SELECT c.*,t.Name FROM tblclients as c,tbltelecallers as t  where c.CreatedBy = t.Id and  c.CreatedBy = $ID";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Select All Clients
	public function getallclients() 
	{
		$query_str = "SELECT c.*,t.Name FROM tblclients as c,tbltelecallers as t  where c.CreatedBy = t.Id";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Select All Clients by Package
	public function getallclientsbypackage($package) 
	{
		$query_str = "SELECT c.*,t.Name FROM tblclients as c,tbltelecallers as t  where c.CreatedBy = t.Id And c.Package='$package'";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Select All Clients by date
	public function getallclientsbydate($startdate,$enddate) 
	{
		$query_str = "SELECT c.*,t.Name FROM tblclients as c,tbltelecallers as t  where c.CreatedBy = t.Id And c.DateCreated Between '$startdate' AND '$enddate'";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	public function distinctpackages()
	{
		$query_str = "SELECT Distinct(Package) FROM tblclients";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	public function getclientbyexpiredpackage()
	{
		$query_str = "SELECT * from tblclients where AssignedLeads>=TotalLeads";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Search telecaller and client by telecaller ID 
	public function getclientsbyteleidandexpiredpackage($ID) 
	{
		$query_str = "SELECT c.*,t.Name FROM tblclients as c,tbltelecallers as t  where c.CreatedBy = t.Id and  c.CreatedBy = $ID and c.AssignedLeads>=c.TotalLeads";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//get client package history
	public function getclientpacakgehistorydatebyid($ID)
	{
		$query_str = "Select Distinct(Package_From) as date from tblclientpackagehistory where ClientId=$ID";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	public function getpackagehistorydetails($ID)
	{
		$query_str = "SELECT t.Name,c.Id,c.ClientName,c.Email,c.CreatedBy,c.DateCreated,ph.Package,ph.TotalLeads,ph.AssignedLeads,ph.Paid,ph.Package_From,ph.Pacakge_To FROM tblclients as c,tblclientpackagehistory as ph,tbltelecallers as t
		 WHERE c.CreatedBy = t.Id and c.Id=ph.ClientId and c.Id=$ID";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Search Client previous package payment detail by Client Id and Package From Date
	public function getpaymenthistorydetailbyidanddate($id,$date)
	{
		$data=$this->db->get_where('tblclientpayment',array('ClientId'=>$id,'Package_From'=>'$date'));
		return $data->result_array();
	}
}