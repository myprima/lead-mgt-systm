<?php

class visitormodel extends CI_Model {

    public function __construct() {
        $this->load->library('Datatables');
        $this->load->library('table');
        $this->load->database();
    }

    public function createvisitor($data) {
        if ($this->db->insert('tblvisitorentry', $data)) {
            return $this->db->insert_id();
        } else {
            return 'false';
        }
    }

    public function updatevisitor($data, $Id) {
        $this->db->where('Id', $Id);
        if ($this->db->update('tblvisitorentry', $data)) {
            return true;
        } else {
            return false;
        }
    }

//counting
    public function getallvisitorcount() {
        $data = $this->db->count_all_results('tblvisitorentry');
        return $data;
    }

//counting todays
    public function getalltodaysvisitorcount() {
        $todaydate = date("Y-m-d");
        $this->db->where('EntryDate', $todaydate);
        $data = $this->db->count_all_results('tblvisitorentry');
        return $data;
    }

    public function createvisitorhistory($data, $Id) {
        if ($Id != null) {
            $this->db->where('VisitorId', $Id);
            if ($this->db->update('tblvisitorhistory', array('viewed' => 'true'))) {
                if ($this->db->insert('tblvisitorhistory', $data)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            if ($this->db->insert('tblvisitorhistory', $data)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getallvisitor() {
        $data = $this->db->get('tblvisitorentry');
        return $data->result_array();
    }

    public function getpendingvisitorbyId($Id, $visitorId) {
        $this->db->where('Id');
        if ($this->db->update('tblvisitorhistory', array('viewed' => 'true'))) {
            $data = $this->db->get_where('tblvisitorentry', array('Id' => $visitorId));
            return $data->result_array();
        } else {
            return false;
        }
    }

    public function getallvisitorbyId($Id) {
        $data = $this->db->get_where('tblvisitorentry', array('Id' => $Id));
        return $data->result_array();
    }

    public function getdatabyemail($email) {
        $data = $this->db->get_where('tblvisitorentry', array('Email' => $email));
        return $data->row_array();
    }

    Public function datatable() {
        $this->datatables->select('Id,Name,Password,Email')
                ->unset_column('Id')
                ->from('tbltelecallers');

        echo $this->datatables->generate();
    }

    //Gets all visitors by telecaller
    //To be removed
    public function getvisitorsbyemail($email) {
        $ID = $this->db->get_where('tbltelecallers', array('Email' => $email))->row()->Id;
        $query_str = "SELECT v.*,t.Name FROM tblvisitorentry as v,tbltelecallers as t  where v.TeleCallerID = t.Id and  v.TeleCallerID = $ID";
        $data = $this->db->query($query_str);
        return $data->result_array();
    }

    //Get all visitors by telecaller Id
    public function getvisitorsbyteleId($ID) {
        //$ID=$this->db->get_where('tbltelecallers',array('Id'=>$Id))->row()->Id;
        $query_str = "SELECT v.*,t.Name FROM tblvisitorentry as v,tbltelecallers as t  where v.TeleCallerID = t.Id and  v.TeleCallerID = $ID";
        $data = $this->db->query($query_str);
        return $data->result_array();
    }

    //Gets confirmed and not yet trasfered visitors by telecaller
    public function getconfirmedvisitorsbyemail($Id) {
        $query_str = "SELECT v.*,t.Name FROM tblvisitorentry as v,tbltelecallers as t  where v.TeleCallerID = t.Id and v.TeleCallerID = $Id and v.Status='Approved' and v.Transfered='false'";
        $data = $this->db->query($query_str);
        return $data->result_array();
    }

    //Gets visitor history by Visitor Id
    public function getvisitorhistorybyid($Id) {
        $query_str = "SELECT v.CompanyName,v.VisitorName,v.VisitorContact,v.Status,vh.* FROM tblvisitorentry as v,tblvisitorhistory as vh  where v.Id = vh.VisitorId and v.Id = $Id";
        $data = $this->db->query($query_str);
        return $data->result_array();
    }

    public function getemail() {
        $this->db->select('Email');
        $data = $this->db->get('tbltelecallers');
        return $data;
    }

    public function updatetelecaller($data, $email) {
        $this->db->where('Email', $email);
        if ($this->db->update('tbltelecallers', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Get  All pending visitors
    public function getallpendingvisitor() {
        $query_str = "SELECT v.CompanyName,vh.* FROM tblvisitorentry as v,tblvisitorhistory as vh where vh.VisitorId=v.Id  and v.Status='Pending' ";
        $data = $this->db->query($query_str);
        return $data->result_array();
    }

    // Get visitors by pending for notifications
    public function getpendingvisitor() {
        $todaydate = date("Y-m-d");
        $query_str = "SELECT v.CompanyName,vh.* FROM tblvisitorentry as v,tblvisitorhistory as vh where vh.VisitorId=v.Id and vh.ReminderDate<='$todaydate' and viewed='false'  and ReminderDate !='0000-00-00'";
        $data = $this->db->query($query_str);
        //$this->db->where('ReminderDate <=', date("Y-m-d"));
        //$data=$this->db->get('tblvisitorhistory');
        return $data->result_array();
    }

    // Get count visitors by pending for notifications
    public function getpendingvisitorcount() {
        $todaydate = date("Y-m-d");
        $query_str = "SELECT count(*) as `X`  FROM tblvisitorentry as v,tblvisitorhistory as vh where vh.VisitorId=v.Id and vh.ReminderDate='$todaydate' and viewed='false'  and ReminderDate !='0000-00-00'";
        $data = $this->db->query($query_str);
        //$this->db->where('ReminderDate <=', date("Y-m-d"));
        //$data=$this->db->get('tblvisitorhistory');
        if ($data->num_rows() > 0) {  //Ensure that there is at least one result 
            foreach ($data->result_array() as $row) { //Iterate through results
                return $row['X'];
            }
        }
    }
}