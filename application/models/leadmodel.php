<?php
class leadmodel extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function tempinsert($data,$id)
	{
		if ($this->db->insert('tbltempleads', $data)) {
			/*$this->db->where('Id', $id);
			$this->db->set('AssignedLeads', 'AssignedLeads+1', FALSE);
			if($this->db->update('tblclients'))
			{*/
				return true;
/*			}
			else
			{
				return false;
			}
*/
		} else {
			return false;
		}
	}

	public function tempupdateclient($clientid,$id)
	{
		$this->db->select('Id');
		$this->db->where('Email', $clientid);
		$data=$this->db->get('tblclients')->result_array();
		$this->db->where('Id', $id);
		$this->db->set('ClientId',$data[0]['Id']);
		if($this->db->update('tbltempleads'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function tempdelete($id)
	{
			$this->db->where_in('Id', $id);
			if($this->db->delete('tbltempleads'))
			{
				return true;
			}
			else
			{
				return false;
			}
	}

	public function insert($teleid)
	{
		$this->db->where('CreatedBy', $teleid);
		$query=$this->db->get('tbltempleads');
		$status=true;
		foreach ($query->result() as $row) {
			$data=null;
			$data= array(
				'ClientId' => $row->ClientId,
				'Name' => $row->Name,
				'Contact' => $row->Contact,
				'Email' => $row->Email,
				'Address' => $row->Address,
				'Description' => $row->Description,
				'CreatedBy'=> $row->CreatedBy,
				'DateCreated'=> $row->DateCreated
				 );
			if($this->db->insert('tblleads',$data))
			{
				$this->db->where('Id', $row->ClientId);
				$this->db->set('AssignedLeads', 'AssignedLeads+1', FALSE);
				if($this->db->update('tblclients'))
				{
					if($status!=false)
					{
						$status=true;
					}
				}
				else
				{
					$status=false;
				}
			}
			else
			{
				$status=false;
			}
		}
		if($status==true)
		{
			$this->db->where_in('CreatedBy', $teleid);
			if($this->db->delete('tbltempleads'))
			{
				$status=true;
			}
			else
			{
				$status=false;
			}
		}
		return $status;
	}

	//Get All Leads
	public function getallLeads() 
	{
		$query_str = "SELECT l.*,c.ClientCompany,t.Name FROM tblleads as l,tblclients as c,tbltelecallers as t  where l.CreatedBy=t.Id And l.ClientId=c.Id";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Get All  Temp Leads by telecaller
	public function getalltempLeadsbytelecaller($client) 
	{
		$query_str = "SELECT l.*,c.ClientCompany,t.Name FROM tbltempleads as l,tblclients as c,tbltelecallers as t  where l.CreatedBy=t.Id And l.ClientId=c.Id And l.CreatedBy='$client'";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Get All temp leads by telecaller withou client
	public function gettempleads($teleid)
	{
		$query_str = "SELECT l.*,t.Name FROM tbltempleads as l,tbltelecallers as t  where l.CreatedBy=t.Id And l.CreatedBy='$teleid'";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Get All Leads by telecaller
	public function getallLeadsbytelecaller($client) 
	{
		$query_str = "SELECT l.*,c.ClientCompany,t.Name FROM tblleads as l,tblclients as c,tbltelecallers as t  where l.CreatedBy=t.Id And l.ClientId=c.Id And l.CreatedBy='$client'";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Get All Leads by Clients
	public function getallLeadsbyclient($client) 
	{
		$query_str = "SELECT l.*,l.Name as leadname,c.ClientCompany,t.Name FROM tblleads as l,tblclients as c,tbltelecallers as t  where l.CreatedBy=t.Id And l.ClientId=c.Id And l.ClientId='$client' And l.Old='false'";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Get All Leads by Telecaller And Clients
	public function getallLeadsbyteleandclient($client,$tele) 
	{
		$query_str = "SELECT l.*,c.ClientCompany,t.Name FROM tblleads as l,tblclients as c,tbltelecallers as t  where l.CreatedBy=t.Id And l.ClientId=c.Id And l.ClientId='$client' And l.CreatedBy='$tele'";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	//Get Lead info by Telecaller And Clients
	public function getleaddetailsbyclientandleadId($client,$id) 
	{
		$query_str = "SELECT l.*,l.Name as leadname,c.ClientCompany,t.Name FROM tblleads as l,tblclients as c,tbltelecallers as t  where l.CreatedBy=t.Id And l.ClientId=c.Id And l.ClientId='$client' And l.Old='false' AND l.Id=$id";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}


	//Get All leads by date
	public function getallLeadssbydate($startdate,$enddate) 
	{
		$query_str = "SELECT l.*,c.ClientCompany,t.Name FROM tblleads as l,tblclients as c,tbltelecallers as t  where l.CreatedBy=t.Id And l.ClientId=c.Id And l.DateCreated Between '$startdate' AND '$enddate'";
		$data = $this->db->query($query_str);
		return $data->result_array();
	}

	public function UpdateClientFeedback($id,$desc)
	{
		$this->db->where('Id', $id);
		$this->db->set('ClientFeedback', $desc);
		if($this->db->update('tblleads'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}