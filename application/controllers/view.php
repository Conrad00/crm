<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View extends CI_Controller {
	
	public function index()
	{
		// show list
		$data["sqldata"]=$this->functions->get_list_item();
              
		$this->load->view("items",$data);
	}
        
        public function item($id)
	{
		//show item of a list
		$itemdata=$this->functions->get_item($id);
                if($itemdata->num_rows()){
                    $data["itemdata"]=$itemdata->row();
                   // $data["rowsdata"]=$this->functions->get_rows($id);
                    $this->load->view("rows",$data); 
                }
                    
               
		
	}
	
        function ajax_table($id){
		
		 return $this->functions->ajax_table_model($id);
	}
        
}