<?php

class Functions extends CI_Model {
    private $row=0;
    
    public function __construct()
    {
	parent::__construct();
        date_default_timezone_set('Asia/Tehran');
    }

    function pdate($mkt)
    {
        return date("m.d.Y H:i", $mkt);   
    }
    function get_year($mkt){
        $year=date("Y", $int); 
        return substr($year, 2);
    }
   function pwdate($mkt)
    {
        return date("m/d/Y", $mkt);   
    }	
    function date2picker($mkt)
    {
        include_once('pdate.php') ;
        
        $value = pgetdate($mkt) ;
        $value =  $value['mday']. '/' . $value['mon'] . '/' . $value['year'] ;
        
        return $value ;
    }	
    function jalali_to_date($string){
                    $birth_date=explode('/',$string);
                   
                    $per=mktime(0, 0, 0,$birth_date['0'] , $birth_date['1'], $birth_date['2']);
                    return $per;
    }

    function date_to_jalali($int){
        $todate = date("m/d/Y", $int); 
        return $todate;
    }


    ////////////////// start need table/////////////////////

    function ajax_table_projects(){

            $this->db->select('customers.c_id,customers.name,customers.tel,customers.mobile,customers.email,customers.address,customers.desc as customers_desc,projects.p_id , projects.title as project_title , projects.date as project_date ,projects.customer_id ,projects.desc as project_desc  , projects.p_id as properties, projects.status, status_name.title as status_title, status_name.label as status_label, projects.responsible');

            $this->db->from('projects');

            $this->db->where('projects.status >',0);

            $this->db->join('customers','customers.c_id=projects.customer_id','Left');

            $this->db->join('status_name','status_name.sn_id=projects.status','Left');

            $aColumns = array( 'p_id','project_title','name','responsible','project_date','status_title','status_label');
            $aColumns_real = array( 'p_id','projects.title','name','tel','projects.desc','mobile','email','address','customers.desc','status_name.title','responsible');

            /* 
             * Paging
             */

             if ($this->input->post('iDisplayStart') != NULL && $this->input->post('iDisplayLength') != '-1') {
                    $this->db->limit($this->input->post('iDisplayLength'), $this->input->post('iDisplayStart'));
             }

            /*
             * Ordering
             */
            $sOrder = "";
            if ($this->input->post('iSortCol_0')) {
                $sort = $sOrder = array();
                for ($i = 0; $i < intval($this->input->post('iSortingCols')); $i ++) {
                    if ($this->input->post('bSortable_' . intval($this->input->post('iSortCol_' . $i))) == "true") {
                        $sOrder = $aColumns [intval($this->input->post('iSortCol_' . $i))];
                        $sort = ($this->input->post('sSortDir_' . $i) === 'asc' ? 'asc' : 'desc');
                        $this->db->order_by($sOrder, $sort);
                    }
                }
            }else{
                        $this->db->order_by("p_id", "DESC");
            }

            if ( $this->input->post('sSearch') && $this->input->post('sSearch') != "" )
            {

                    $search_value=$this->input->post('sSearch');
                    $where = "( $aColumns_real[0] LIKE '%$search_value%' || $aColumns_real[1] LIKE '%$search_value%' || $aColumns_real[2] LIKE '%$search_value%' || $aColumns_real[3] LIKE '%$search_value%'  || $aColumns_real[4] LIKE '%$search_value%'  || $aColumns_real[5] LIKE '%$search_value%'  || $aColumns_real[6] LIKE '%$search_value%' || $aColumns_real[7] LIKE '%$search_value%'  || $aColumns_real[8] LIKE '%$search_value%' || $aColumns_real[9] LIKE '%$search_value%' || $aColumns_real[10] LIKE '%$search_value%')";

                    $this->db->where($where);

            }

                    $customer_data=$this->db->get();

                    $iTotal=$this->ajax_table_project_total();
                    $iFilteredTotal=$customer_data->result_array();
            /*
             * Output
             */
            $output = array(
                    "sEcho" => intval($this->input->post('sEcho')),
                    "iTotalRecords" => $iTotal,
                    "iTotalDisplayRecords" => $iTotal,
                    "aaData" => array(),
                    "aColumns"=>$aColumns
            );

            $count = $this->input->post('iDisplayStart') + 1;


            foreach ($iFilteredTotal as $key => $aRow) {
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                    $this->p_id=$aRow[ $aColumns[0] ];
                    $this->status_label=$aRow[ $aColumns[6] ];			
                    if ( $aColumns[$i] == "version" )
                    {

                            $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $this->print_table_project( $aColumns[$i],$aRow[ $aColumns[$i] ]);
                    }
                    else if ( $aColumns[$i] != ' ' )
                    {

                            $row[] = $this->print_table_project( $aColumns[$i],$aRow[ $aColumns[$i] ]);
                    }
            }
            $output['aaData'][] = $row;
    }



            echo json_encode( $output );

    }
        
    function print_table_project($col, $var) {
        $return = $var;
       
        switch ($col) {
          
            case 'project_date' :
                $return = $this->functions->pdate($var);
                break;
             case 'project_title' :
                $link=  site_url('pages/project/'.$this->p_id);
                $return = "<a href='".$link."'>".$var."</a>";
                break;
             case 'status_title' :
                $return = "<span class='label label-". $this->status_label."'>".$var."</span>";
                break;
            default :
                break;
        }

        return $return;
    }

    
	function ajax_table_project_total(){

		
		
            $this->db->select('customers.c_id,customers.name,customers.tel,customers.mobile,customers.email,customers.address,customers.desc as customers_desc,projects.p_id , projects.title as project_title , projects.date as project_date ,projects.customer_id ,projects.desc as project_desc  , projects.p_id as properties, projects.status, status_name.title as status_title, status_name.label as status_label, projects.responsible');

            $this->db->from('projects');

            $this->db->where('projects.status >',0);

            $this->db->join('customers','customers.c_id=projects.customer_id','Left');

            $this->db->join('status_name','status_name.sn_id=projects.status','Left');

            $aColumns = array( 'p_id','project_title','name','responsible','project_date','status_title','status_label');
            $aColumns_real = array( 'p_id','projects.title','name','tel','projects.desc','mobile','email','address','customers.desc','status_name.title','responsible');

                if ( $this->input->post('sSearch') && $this->input->post('sSearch') != "" )
                {

                        $search_value=$this->input->post('sSearch');
                        $where = "( $aColumns_real[0] LIKE '%$search_value%' || $aColumns_real[1] LIKE '%$search_value%' || $aColumns_real[2] LIKE '%$search_value%' || $aColumns_real[3] LIKE '%$search_value%'  || $aColumns_real[4] LIKE '%$search_value%'  || $aColumns_real[5] LIKE '%$search_value%'  || $aColumns_real[6] LIKE '%$search_value%' || $aColumns_real[7] LIKE '%$search_value%'  || $aColumns_real[8] LIKE '%$search_value%'  || $aColumns_real[9] LIKE '%$search_value%'  || $aColumns_real[10] LIKE '%$search_value%')";

                        $this->db->where($where);

                }

		$customer_data=$this->db->get();
		
		return $customer_data->num_rows();

	}
	
        function project_info($id){
                $this->db->select('customers.c_id,customers.name,customers.tel,customers.mobile,customers.email,customers.address,customers.desc as customers_desc,customers.date as registered_date,projects.p_id , projects.title as project_title , projects.date as project_date ,projects.customer_id ,projects.desc as project_desc  , projects.p_id as properties, projects.status, status_name.title as status_title, status_name.label as status_label,projects.responsible');
		
		$this->db->from('projects');
		
		$this->db->where('projects.p_id',$id);
		
                $this->db->join('customers','customers.c_id=projects.customer_id','Left');
                
                $this->db->join('status_name','status_name.sn_id=projects.status','Left');
                
                $project_info=$this->db->get()->row();
		
		return $project_info;
        }
        
        function get_status($id=0){
            if($id){
                 $this->db->select("*");
                 $this->db->from('projects');
                 $this->db->where('p_id',$id);
                 $project_info=$this->db->get();
                 if($project_info->num_rows()){
                     //true
                     $where=true;  
                 }else{
                     //false
                     $where=false;
                 }
            }else{
                $where=false;
            }
                $status=$this->get_status_info($project_info->row()->status);
                $status_cat=$status->category;
                $this->db->select("*");
                $this->db->from('status_name');
                $this->db->where('active',1);  
                if($where){
                    if($status_cat!=0){
                        if($status->sn_id == 8){
                            // go to next level
                            $this->db->where('(category=2 OR category=0)');
                            
                        }else{
                            // this level only
                            $this->db->where("(category=$status_cat OR category=0)");
                        }
                         
                    }
                   
                }
                $this->db->order_by("category","desc");
                $status_info=$this->db->get();
                return array("status_info"=>$status_info,"project_info"=>$project_info);
            
            
        }
        
        function option_status($id=0){
            $result=$this->get_status($id);
          
            $string=$selected=NULL;
            $status=$result["project_info"]->row()->status;
            foreach ($result["status_info"]->result() as $value) {
                if($value->sn_id == $status){
                    $selected="selected='selected'";
                }else{
                     $selected=NULL;
                }
               
               $string .="<option ".$selected." value='".$value->sn_id."' >".$value->title."</option>";
            }
            
            return $string;
        }
        
        function get_status_info($status){
            
                $this->db->select("*");
                $this->db->from('status_name');
                $this->db->where('sn_id',$status);  
                $status_info=$this->db->get();
                return $status_info->row();
        }
        
        function project_status($id){
                $this->db->select("status.* , status_name.title as status_title , status_name.label as status_label");
                $this->db->from('status');
                $this->db->where('project_id',$id);  
                $this->db->join('status_name','status_name.sn_id=status.status_id','Left');
                $this->db->order_by("s_id","DESC");
                $status_info=$this->db->get();
                $i=0;
                $final_array=array();
                if($status_info->num_rows()){
                   $count=$status_info->num_rows();
                   $result=$status_info->result();
                   foreach ($result as $value) {
                       $count2=($count)-$i;
                            $second["text"]=$value->status_title;
                            $second["label"]=$value->status_label;
                        if(isset($result[$i+1])){
                            // there is a row
                            $first["text"]=$result[$i+1]->status_title;
                            $first["label"]=$result[$i+1]->status_label;
                        }else{
                            // last row
                            $first["text"]="Newbie";
                            $first["label"]="default";
                        }
                        $final_array[].="<tr><td>".$count2."</td>";
                        $final_array[].="<td><span class='label label-".$first["label"]."' >".$first["text"]."</span>";
                        $final_array[].=" --->";
                        $final_array[].="<span class='label label-".$second["label"]."' >".$second["text"]."</span></td>";
                        $final_array[].="<td>".$this->pdate($value->date)."</td></tr>";
                        $i++;
                    } 
                    
                }
                return $final_array;
        }
        
          ////////////////// start comments table/////////////////////
	
	function ajax_table_comments($id=0){
 
		$this->db->select('comments.*,users.name as user_real_name,projects.title as projects_title,projects.p_id,customers.name as customer_name');
		
		$this->db->from('comments');
		
                if($id){
                    $this->db->where('project_id',$id);
                }
		
		$this->db->join('projects','projects.p_id=comments.project_id','Left');
                $this->db->join('users','users.u_id=comments.user_id','Left');
                $this->db->join('customers','customers.c_id=projects.customer_id','Left');
                
                
                $aColumns = array( 'text','projects_title','date','user_real_name','p_id','customer_name');
                $aColumns_real = array( 'text','projects.title','users.name','customers.name');

                /* 
                 * Paging
                 */

                 if ($this->input->post('iDisplayStart') != NULL && $this->input->post('iDisplayLength') != '-1') {
                        $this->db->limit($this->input->post('iDisplayLength'), $this->input->post('iDisplayStart'));
                 }

                /*
                 * Ordering
                 */
                $sOrder = "";
                if ($this->input->post('iSortCol_0')) {
                    $sort = $sOrder = array();
                    for ($i = 0; $i < intval($this->input->post('iSortingCols')); $i ++) {
                        if ($this->input->post('bSortable_' . intval($this->input->post('iSortCol_' . $i))) == "true") {
                            $sOrder = $aColumns [intval($this->input->post('iSortCol_' . $i))];
                            $sort = ($this->input->post('sSortDir_' . $i) === 'asc' ? 'asc' : 'desc');
                            $this->db->order_by($sOrder, $sort);
                        }
                    }
                }else{
                            $this->db->order_by("c_id", "DESC");
                }

                if ( $this->input->post('sSearch') && $this->input->post('sSearch') != "" )
                {

                        $search_value=$this->input->post('sSearch');
                        $where = "( $aColumns_real[0] LIKE '%$search_value%' || $aColumns_real[1] LIKE '%$search_value%' || $aColumns_real[2] LIKE '%$search_value%')";

                        $this->db->where($where);

                }

                        $customer_data=$this->db->get();

                        $iTotal=$this->ajax_table_comments_total($id);
                        $iFilteredTotal=$customer_data->result_array();
                /*
                 * Output
                 */
                $output = array(
                        "sEcho" => intval($this->input->post('sEcho')),
                        "iTotalRecords" => $iTotal,
                        "iTotalDisplayRecords" => $iTotal,
                        "aaData" => array(),
                        "aColumns"=>$aColumns
                );

                $count = $this->input->post('iDisplayStart') + 1;
                               
                foreach ($iFilteredTotal as $key => $aRow) {
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$this->p_id=$aRow[ $aColumns[4] ];
                        $this->c_name=$aRow[ $aColumns[5] ];
			if ( $aColumns[$i] == "version" )
			{
				
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $this->print_table_comments( $aColumns[$i],$aRow[ $aColumns[$i] ]);
			}
			else if ( $aColumns[$i] != ' ' )
			{
				
				$row[] = $this->print_table_comments( $aColumns[$i],$aRow[ $aColumns[$i] ]);
			}
		}
		$output['aaData'][] = $row;
	}

                echo json_encode( $output );

	}
        
    function print_table_comments($col, $var) {
        $return = $var;
       
        switch ($col) {
          
            case 'date' :
                $return = $this->functions->pdate($var);
                break;
             case 'projects_title' :
                $link=  site_url('pages/project/'.$this->p_id);
                $return = "<a href='".$link."'>".$var."</a> - ".$this->c_name;
               
                break;
            
            default :
                break;
        }

        return $return;
    }

    
	function ajax_table_comments_total($id=0){

		
		$this->db->select('comments.*,users.name as user_real_name,projects.title as projects_title,projects.p_id');
		
		$this->db->from('comments');
		
                if($id){
                    $this->db->where('project_id',$id);
                }
		
		$this->db->join('projects','projects.p_id=comments.project_id','Left');
                $this->db->join('users','users.u_id=comments.user_id','Left');

                $aColumns = array( 'text','projects_title','date','user_real_name','p_id');
                $aColumns_real = array( 'text','projects_title','user_real_name');

                 if ( $this->input->post('sSearch') && $this->input->post('sSearch') != "" )
                {

                        $search_value=$this->input->post('sSearch');
                        $where = "( $aColumns_real[0] LIKE '%$search_value%' || $aColumns_real[1] LIKE '%$search_value%' || $aColumns_real[2] LIKE '%$search_value%')";

                        $this->db->where($where);

                }

		$customer_data=$this->db->get();
		
		return $customer_data->num_rows();

	}
        
        function check_login(){
             $user=  set_value("user");
             $pass=  set_value("pass");
            
            $this->db->select("*");
            $this->db->from('users');
            $this->db->where('username',$user);  
            $this->db->where('password',$pass);  
            $this->db->where('active',1);  
            $user_info=$this->db->get();
            if($user_info->num_rows()){
                $this->session->set_userdata("user_id",$user_info->row()->u_id);
                $this->session->set_userdata("user_name",$user_info->row()->name);
                
                return True;
            }else{
                return FALSE;
            }
            
        }
        
        function get_date(){
            $mkt=mktime();
            return $this->pwdate($mkt);
        }
        
        function insert_files($data){
            $customer_id=$data["project_info"]->c_id;
            $this->db->select("*");
            $this->db->from("files");
            $this->db->join('projects','projects.p_id=files.project_id','Left');
            $this->db->where('projects.customer_id',$customer_id);
            $query=$this->db->get();
            $file_th=($query->num_rows())+1;
            
            $year=$this->get_year($data["project_info"]->registered_date);
            $setting_array=array();
            $alias=$year.$customer_id."-".$file_th;
            ////////////////////
            $sell_rows=$this->input->post("sell_rows");
            for($i=1;$i<=$sell_rows;$i++){
                   $selltype=$this->input->post("selltype_".$i);  
                   $selltitle=$this->input->post("selltitle_".$i);   
                   $sellunit=$this->input->post("sellunit_".$i);   
                   $sellinput=$this->input->post("sellinput_".$i);
                   $sellprice=$this->input->post("sellprice_".$i);
                   if($sellinput){
                      $setting_array[$i]=array(
                          "selltype"    =>  $selltype,
                          "selltitle"   =>  $selltitle,
                          "sellunit"    =>  $sellunit,
                          "sellinput"   =>  $this->unmask($sellinput),
                          "sellprice"   =>  $this->unmask($sellprice)
                      );
                   }
             }
             
            $detail_array=array(
                "setting"   =>  $setting_array,
                "top"       =>  set_value("top"),
                "bottom"    =>  set_value("bottom"),
            );

            $detail=  json_encode($detail_array);
            $sign=($this->input->post("sign"))?1:0;
            $fifty=($this->input->post("fifty"))?1:0;
            $rtp=($this->input->post("remove_total_price"))?1:0;
            $sql_data=array(
              "date"             =>  mktime(),
              "project_id"       =>  $data["project_info"]->p_id,
              "type"             =>  $data["type"],
              "is_decent"        =>  $data["decent"],
              "is_desc"          =>  $data["desc"],  
              "user_id"          =>  $this->session->userdata("user_id"),
              "details"          =>  $detail,
              "alias"            =>  $alias,
              "final_price"      =>  $this->input->post("final_price"),
              "overal_price"     =>  $this->input->post("overal_price"),
              "status"           =>  1,
              "prepayment"       =>  $this->prepayment($this->input->post("prepayment")),
              "signiture"        =>  $sign,
              "fifty"            =>  $fifty,  
              "remove_total_price"  => $rtp
            );
            
            $this->db->insert('files', $sql_data) ;
            $file_id=$this->db->insert_id();
            
            return $file_id;
        }
        
       function file_info($file_id){
           $this->db->select("files.*, projects.title,customers.name,customers.c_id as customer_id");
           $this->db->from("files");
           $this->db->where("f_id",$file_id);
           $this->db->join('projects','projects.p_id=files.project_id','Left');
           $this->db->join('customers','customers.c_id=projects.customer_id','Left');
           $query=$this->db->get();
           return $query->row();
       }
        
       function file_rows($file_id){
           $this->db->select("*");
           $this->db->from("files_row");
           $this->db->where("files_id",$file_id);
           $query=$this->db->get();
           return $query;
       }
       
       function title_letter($type){
           switch ($type){
               case 1:
                   $title="Pre-invoiced";
                   break;
               case 2:
                   $title="Invoiced";
                   break;
               default :    
                   $title="Letter";
                   break;
                   
           }
           return $title;
       }
       
       function project_files($file_id){
           $this->db->select("files.* , users.name");
           $this->db->from("files");
           $this->db->where("project_id",$file_id);
           $this->db->where("status >",0);
           $this->db->join('users','users.u_id=files.user_id','Left');
           $this->db->order_by("f_id","DESC");
           $query=$this->db->get();
           return $query;
       }
       
       function file_status($status){
           switch ($status) {
               case 1:
                   $return= "<span class='label label-default'>Not approved</a>";
                   break;
               case 2:
                   $return= "<span class='label label-success'>Approved</a>";
                   break;
              case 3:
                   $return= "<span class='label label-success'>Paid</a>";
                   break;
               default:
                   $return=NULL;
                   break;
           }
           return $return ;
       }
       
       function unmask($value){
           return str_replace(",", "", $value);
       }
       
       function project_name(){
            $this->db->select("*");
            $this->db->from("projects");
            $query=$this->db->get();
            $num= $query->num_rows();
            $return= "Order ".($num+1);
                
           $seted=set_value('project');
           if(isset($seted)){
               if($seted){
                   return $seted;
               }else{
                  return $return ;
               }
               
           }else{
                return $return;
           }
          
       }
       function prepayment($value){
           if(!$value){
               $value=0;
           }
           return $this->unmask($value);
       }
       
       function mask($value){
           return $value;
       }
      function update_file_1($id){
           
            $sell_rows=$this->input->post("sell_rows");
            $setting_array=array();
            for($i=1;$i<=$sell_rows;$i++){
                   $selltype=$this->input->post("selltype_".$i);  
                   $selltitle=$this->input->post("selltitle_".$i);   
                   $sellunit=$this->input->post("sellunit_".$i);   
                   $sellinput=$this->input->post("sellinput_".$i);
                   $sellprice=$this->input->post("sellprice_".$i);
                   if($sellinput){
                      $setting_array[$i]=array(
                          "selltype"    =>  $selltype,
                          "selltitle"   =>  $selltitle,
                          "sellunit"    =>  $sellunit,
                          "sellinput"   =>  $this->unmask($sellinput),
                          "sellprice"   =>  $this->unmask($sellprice)
                      );
                   }
             }
             
            $detail_array=array(
                "setting"   =>  $setting_array,
                "top"       =>  set_value("top"),
                "bottom"    =>  set_value("bottom"),
            );

            $detail=  json_encode($detail_array);
            $sign=($this->input->post("sign"))?1:0;
            $fifty=($this->input->post("fifty"))?1:0;
            $rtp=($this->input->post("remove_total_price"))?1:0;
            $sql_data=array(
              "date"             =>  mktime(),
              "user_id"          =>  $this->session->userdata("user_id"),
              "details"          =>  $detail,
              "final_price"      =>  $this->input->post("final_price"),
              "overal_price"     =>  $this->input->post("overal_price"),
              "status"           =>  1,
              "prepayment"       =>  $this->prepayment($this->input->post("prepayment")),
              "signiture"        =>  $sign,
              "fifty"            =>  $fifty,
              "remove_total_price"  =>  $rtp
            );
            
            // insert customers
            $this->db->update('files',$sql_data , array('f_id' => $id));

        }
       
        
        function update_file_2($id){
            $query=$this->file_info($id);
            $project_info=$this->project_info($query->project_id);
            
            $sell_rows=$this->input->post("sell_rows");
            $setting_array=array();
            for($i=1;$i<=$sell_rows;$i++){
                   $selltype=$this->input->post("selltype_".$i);  
                   $selltitle=$this->input->post("selltitle_".$i);   
                   $sellunit=$this->input->post("sellunit_".$i);   
                   $sellinput=$this->input->post("sellinput_".$i);
                   $sellprice=$this->input->post("sellprice_".$i);
                   if($sellinput){
                      $setting_array[$i]=array(
                          "selltype"    =>  $selltype,
                          "selltitle"   =>  $selltitle,
                          "sellunit"    =>  $sellunit,
                          "sellinput"   =>  $this->unmask($sellinput),
                          "sellprice"   =>  $this->unmask($sellprice)
                      );
                   }
             }
             
            $detail_array=array(
                "setting"   =>  $setting_array,
                "top"       =>  set_value("top"),
                "bottom"    =>  set_value("bottom"),
            );

            $detail=  json_encode($detail_array);
            $sign=($this->input->post("sign"))?1:0;
            $fifty=($this->input->post("fifty"))?1:0;
            $rtp=($this->input->post("remove_total_price"))?1:0;
            $sql_data=array(
              "date"             =>  mktime(),
              "project_id"       =>  $query->project_id,
              "type"             =>  $query->type,
              "is_decent"        =>  $query->is_decent,
              "is_desc"          =>  $query->is_desc,  
              "user_id"          =>  $this->session->userdata("user_id"),
              "details"          =>  $detail,
              "alias"            =>  $query->alias,
              "final_price"      =>  $this->input->post("final_price"),
              "overal_price"     =>  $this->input->post("overal_price"),
              "status"           =>  1,
              "prepayment"       =>  $this->prepayment($this->input->post("prepayment")),
              "signiture"        =>  $sign,
              "fifty"            =>  $fifty,  
              "remove_total_price"  =>  $rtp
            );
            
            $this->db->insert('files', $sql_data) ;
            $file_id=$this->db->insert_id();
            
            return $file_id;
        }
        
       function insert_invoice($file_info){
            $customer_id=$file_info->customer_id;
            $this->db->select("*,projects.date as project_date");
            $this->db->from("files");
            $this->db->join('projects','projects.p_id=files.project_id','Left');
            $this->db->where('projects.customer_id',$customer_id);
            $query=$this->db->get();
            $file_th=($query->num_rows())+1;
            
            $year=$this->get_year($query->row()->project_date);
            $alias=$year.$customer_id."-".$file_th;
            ///////////////////
            $should_pay=($file_info->final_price)-($file_info->prepayment);
            $sql_data=array(
              "date"             =>  mktime(),
              "project_id"       =>  $file_info->project_id,
              "type"             =>  2,
              "is_decent"        =>  $file_info->is_decent,
              "is_desc"          =>  $file_info->is_desc,  
              "user_id"          =>  $this->session->userdata("user_id"),
              "details"          =>  $file_info->details,
              "alias"            =>  $alias,
              "final_price"      =>  $file_info->final_price,
              "overal_price"     =>  $file_info->overal_price,
              "status"           =>  1,
              "prepayment"       =>  $file_info->prepayment,
              "should_pay"       =>  $should_pay,
              "signiture"        =>  $file_info->signiture,
              "fifty"            =>  0,  
              "prefactor"       =>  $file_info->f_id,
              "invoice_id"       =>  0,
              
            );
            
            $this->db->insert('files', $sql_data) ;
            $file_id=$this->db->insert_id();
            
            return $file_id;
       }
        
       function update_prefactor($id,$invoice_id){
            $data = array(
                   'invoice_id'	=> $invoice_id ,
           ) ;
           // insert customers
           $this->db->update('files',$data , array('f_id' => $id));
       }
       
       function get_deps(){
           $this->db->select("*");
           $this->db->from("dep");
           $this->db->where("status >",0);
           $query=$this->db->get();
           return $query;
       }
       
       function get_deps_options(){
           $dep=$this->functions->get_deps();
           $return=NULL;
           foreach ($dep->result() as $value) {
                $return.= "<option value='$value->id'>$value->title</option>";
            }
            return $return;
       }
       
                ////////////////// start comments table/////////////////////
	
	function ajax_table_tasks($dep=0){
 
		$this->db->select('tasks.t_id , tasks.text as task_text , dep.title as dep_title , tasks.due , users.name as user_name , projects.p_id , tasks.status as task_status , tasks.later');
		
		$this->db->from('tasks');
		
                if($dep){
                    $this->db->where('dep',$dep);
                }
		
                $this->db->join('users','users.u_id=tasks.creator','Left');
                $this->db->join('comments','comments.c_id=tasks.comment_id','Left');
                $this->db->join('projects','projects.p_id=comments.project_id','Left');
                $this->db->join('customers','customers.c_id=projects.customer_id','Left');
                
                $this->db->join('dep','dep.id=tasks.dep','Left');
                
                $aColumns = array( 't_id','task_text','dep_title','due','user_name','p_id','task_status','later');
                $aColumns_real = array( 't_id','tasks.text','dep.title','users.name');

                /* 
                 * Paging
                 */

                 if ($this->input->post('iDisplayStart') != NULL && $this->input->post('iDisplayLength') != '-1') {
                        $this->db->limit($this->input->post('iDisplayLength'), $this->input->post('iDisplayStart'));
                 }

                /*
                 * Ordering
                 */
                $sOrder = "";
                if ($this->input->post('iSortCol_0')) {
                    $sort = $sOrder = array();
                    for ($i = 0; $i < intval($this->input->post('iSortingCols')); $i ++) {
                        if ($this->input->post('bSortable_' . intval($this->input->post('iSortCol_' . $i))) == "true") {
                            $sOrder = $aColumns [intval($this->input->post('iSortCol_' . $i))];
                            $sort = ($this->input->post('sSortDir_' . $i) === 'asc' ? 'asc' : 'desc');
                            $this->db->order_by($sOrder, $sort);
                        }
                    }
                }else{
                            $this->db->order_by("tasks.due = 0 ASC, tasks.due ASC");
                }

                if ( $this->input->post('sSearch') && $this->input->post('sSearch') != "" )
                {

                        $search_value=$this->input->post('sSearch');
                        $where = "( $aColumns_real[0] LIKE '%$search_value%' || $aColumns_real[1] LIKE '%$search_value%' || $aColumns_real[2] LIKE '%$search_value%'  || $aColumns_real[3] LIKE '%$search_value%')";

                        $this->db->where($where);

                }

                        $customer_data=$this->db->get();

                        $iTotal=$this->ajax_table_tasks_total($dep);
                        $iFilteredTotal=$customer_data->result_array();
                /*
                 * Output
                 */
                $output = array(
                        "sEcho" => intval($this->input->post('sEcho')),
                        "iTotalRecords" => $iTotal,
                        "iTotalDisplayRecords" => $iTotal,
                        "aaData" => array(),
                        "aColumns"=>$aColumns
                );

                $count = $this->input->post('iDisplayStart') + 1;
                               
                foreach ($iFilteredTotal as $key => $aRow) {
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$this->t_id=$aRow[ $aColumns[0] ];
                        $this->task_status=$aRow[ $aColumns[6] ];
                        $this->due =$aRow[ $aColumns[3] ];
                        $this->later =$aRow[ $aColumns[7] ];
			if ( $aColumns[$i] == "version" )
			{
				
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $this->print_table_tasks( $aColumns[$i],$aRow[ $aColumns[$i] ]);
			}
			else if ( $aColumns[$i] != ' ' )
			{
				
				$row[] = $this->print_table_tasks( $aColumns[$i],$aRow[ $aColumns[$i] ]);
			}
		}
		$output['aaData'][] = $row;
	}

                echo json_encode( $output );

	}
        
    function print_table_tasks($col, $var) {
        $return = $var;
       
        switch ($col) {
          
            case 'due' :
               
                if($var){
                    $return=$this->functions->time_left ($var);
                }else{
                    $return="بعدا";
                }
                
                break;
             case 'task_text' :
                $text=  character_limiter($var, 100);
                if($this->task_status){
                    $color_c='color_c';
                }else{
                    $color_c=NULL;
                }
                if($this->due < mktime() && $this->later == 0){
                    // overtime
                    $color_c.=' color_r ';
                }
                $return = "<a href='#' class='task_link ".$color_c." task_link_".$this->t_id."' data='".$this->t_id."' >".$text."</a>";
                break;
            case 't_id' :
                if($this->task_status){
                    $checked='checked';
                }else{
                    $checked=NULL;
                }
                $return = "<input type='checkbox' ".$checked." class='resolve' data='".$var."' />";
                break;
            default :
                break;
        }

        return $return;
    }

    
	function ajax_table_tasks_total($dep=0){

		
		$this->db->select('tasks.t_id , tasks.text as task_text , dep.title as dep_title , tasks.due , users.name as user_name , projects.p_id');
		$this->db->from('tasks');

                if($dep){
                    $this->db->where('dep',$dep);
                }
                $this->db->join('users','users.u_id=tasks.creator','Left');
                $this->db->join('comments','comments.c_id=tasks.comment_id','Left');
                $this->db->join('projects','projects.p_id=comments.project_id','Left');
                $this->db->join('customers','customers.c_id=projects.customer_id','Left');
                
                $this->db->join('dep','dep.id=tasks.dep','Left');
                
                $aColumns = array( 't_id','task_text','dep_title','due','user_name','p_id');
                $aColumns_real = array( 't_id','tasks.text','dep.title','users.name');

                 if ( $this->input->post('sSearch') && $this->input->post('sSearch') != "" )
                {

                        $search_value=$this->input->post('sSearch');
                        $where = "( $aColumns_real[0] LIKE '%$search_value%' || $aColumns_real[1] LIKE '%$search_value%' || $aColumns_real[2] LIKE '%$search_value%'  || $aColumns_real[3] LIKE '%$search_value%')";

                        $this->db->where($where);

                }

		$customer_data=$this->db->get();
		
		return $customer_data->num_rows();

	}
        
       function get_task_data($id){
           $this->db->select("tasks.*,users.name as user_name, comments.text comment_text, projects.title as project_title , customers.name as customer_name , dep.title as dep_title  , dep.id as dep_id");
           $this->db->from("tasks");
           $this->db->where("t_id",$id);
           $this->db->join('users','users.u_id=tasks.creator','Left');
           $this->db->join('comments','comments.c_id=tasks.comment_id','Left');
           $this->db->join('projects','projects.p_id=comments.project_id','Left');
           $this->db->join('customers','customers.c_id=projects.customer_id','Left');
           $this->db->join('dep','dep.id=tasks.dep','Left');
           $query=$this->db->get()->row();
           return $query;
       }
       
       function customer_info($c_id){
           $this->db->select("*");
           $this->db->from("customers");
           $this->db->where("c_id",$c_id);
           $query=$this->db->get()->row();
           return $query;
       }
       
       function time_limit($id,$c_id){
           $project_info=$this->project_info($id);
           $status=$this->input->post("status");
           if($status == 10){
               // for designer
               $text=$project_info->project_title." order went to designing stage";
               $dep=3;
               $due=strtotime("+2 days", time());
               
           }elseif($status == 11){
               // insert task for chaap
               $text=$project_info->project_title." order went to printing stage";
               $dep=6;
               $due=strtotime("+3 days", time());
               
           }elseif($status == 12){
                // insert task for kargah
               $text=$project_info->project_title." order went to producing stage";
               $dep=5;
               $due=strtotime("+3 days", time());
               
           }elseif ($status == 15 ) {
               
               // insert task for nasb
               $text=$project_info->project_title." order needs installation";
               $dep=4;
               $due=strtotime("+3 days", time());
            
           }elseif($status == 6){
                // insert task for foroosh
               $text=$project_info->project_title." order needs a pre-invoiced";
               $dep=8;
               $due=strtotime("+1 day", time());
           }
           
           if( in_array($status, array(10,11,12,15,6))){
                $task_data=array(
                    "text"          =>  $text,
                    "dep"           =>  $dep,
                    "due"           =>  $due,
                    "comment_id"    =>  $c_id,
                  );
                                      
               $this->insert_task($task_data);
           }
          
       }
       
       function insert_status($status,$project_id){
           $status_data = array(
                    'project_id'		=>	$project_id ,
                    'date'			=>	mktime() ,
                    'user_id'                   =>	$this->session->userdata("user_id") ,
                    'status_id'                 =>	$status
            ) ;
         $this->db->insert('status', $status_data) ;
       }
       
       function insert_task($array){
         $user_id=$this->session->userdata("user_id");
         $array["creator"]=($user_id)?$user_id:0;
         $array["status"]= 0;
         $array["submitdate"]= mktime();
         $this->db->insert('tasks', $array) ;
         $task_id=$this->db->insert_id();
         // email
         $this->email_task($task_id);
       }
       
       function email_task($task_id){
            $task_data=$this->get_task_data($task_id);
           
            $email_text="این وظیفه برای بخش ".$task_data->dep_title." قرار داده شد که شما شامل این بخش هستید .";
            $email_text.="<br>";
            $email_text.=$task_data->text;
            $email_text.="<br>";
            if($task_data->project_title){
                 $email_text.="<br> نام سفارش: ".$task_data->project_title;
            }
            if($task_data->project_title){
                $email_text.="<br>نام مشتری: ".$task_data->customer_name;
            }
            $email_text.="<br>نام ایجاد کننده: ".$task_data->user_name;
            $email_text.="<br>مهلت انجام : ".$this->functions->pdate($task_data->due);
            $email_array["text"]=$email_text;
            $email_array['subject']=  character_limiter($task_data->text,100);
            $email_result=$this->get_user_dep($task_data->dep_id);
            $to_array=array();
            foreach ($email_result->result() as $value) {
                $to_array[]=$value->email;
            }
            if($email_array){
                // send email
                $email_array['to_email']= implode(",", $to_array);
                //$this->functions->send_mail($email_array);
               
            }
             
       }
       
       
       function send_sms( $to, $text) {
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient("http://87.107.121.54/post/send.asmx?wsdl");
            $parameters['username'] = "9121799575";
            $parameters['password'] = "fgh29as32";
            //$parameters['from'] = "30008810118811";
            $parameters['from'] = "2186019110";
            $parameters['to'] = $to;
            $parameters['text'] = $text ;
            $parameters['isflash'] = false;
            $parameters['udh'] = "";
            $parameters['recId'] = array(0);
            $parameters['status'] = 0x0;
            $client->SendSms($parameters)->SendSmsResult;
        } catch (SoapFault $ex) {
            echo $ex->faultstring;
        }
    }
    
    function overdue_task_sms(){
         $query=$this->overdue_task();
         foreach ($query->result() as $value) {
            $task_data=$this->get_task_data($value->t_id);
            $email_text="اتمام مهلت وظیفه بخش ".$task_data->dep_title."\n";
            $email_text.=character_limiter($task_data->text,70);
            $email_text.="\n";
            if($task_data->project_title){
                 $email_text.="نام سفارش: ".$task_data->project_title."\n";
            }
            if($task_data->project_title){
                $email_text.="نام مشتری: ".$task_data->customer_name."\n";
            }
            $email_text.="نام ایجاد کننده: ".$task_data->user_name."\n";
            $email_text.="مهلت انجام : ".$this->functions->pdate($task_data->due)."\n";
             
             $this->send_sms_dep($value->dep,$email_text);
         }
         
    }
    
    function overdue_task(){
           $this->db->select("tasks.*,dep.id as dep_id");
           $this->db->from("tasks");
           $this->db->where("due < ",mktime());
           $this->db->where("tasks.status",0);
           $this->db->join('dep','dep.id=tasks.dep','Left');
           $query=$this->db->get();
           return $query;
    }
    
    function send_sms_dep($dep_id,$text){
        $users=$this->get_user_dep($dep_id);
        $user_array=array();
        foreach ($users->result() as $value) {
              $user_array[]=$value->mobile;
         }
         if($user_array){
             $this->send_sms($user_array,$text);
         }
                 
    }
    function get_user_dep($dep_id){
           $this->db->select("user_relation.*,users.mobile,users.email");
           $this->db->from("user_relation");
           $this->db->where("r_dep",$dep_id);
           $this->db->join('users','users.u_id=user_relation.r_user_id','Left');
           $query=$this->db->get();
           return $query;
    }
    
    	function send_mail($array){
                
                $this->load->library('email');
                $config['useragent'] = 'Negaaran.ir';
                $config['protocol'] = 'mail';
                $config['mailpath'] = '/usr/sbin/sendmail';
                $config['charset'] = 'UTF-8';
                $config['wordwrap'] = TRUE;
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->from("sale@negaaran.ir","Negaaran Portal");
                $this->email->to($array['to_email']);
                $this->email->bcc("all_email@negaaran.ir");
                $this->email->subject($array['subject']);
                $message="<p style='direction:rtl'>";
                $message.=$array['text']."</p>";
                $this->email->message($message);	

                $this->email->send();

               // echo $this->email->print_debugger();

        }
    
        function get_tasks_switch($type=0){
            $mktime=mktime();
            $today = mktime(0, 0, 0, date('m'), date('d'), date('y'));
            $tomorrow= mktime(0, 0, 0, date('m'), date('d')+1, date('y'));
            $next_3=mktime(0, 0, 0, date('m'), date('d')+4, date('y'));
            $array=$this->dep_user_array($this->session->userdata("user_id"));
           
           $this->db->select("tasks.*,tasks.status as task_status ,users.name as user_name, comments.text comment_text, projects.title as project_title , customers.name as customer_name , dep.title as dep_title  , dep.id as dep_id");
           $this->db->from("tasks");
           switch ($type) {
               case 0:
                   // overdue
                   $this->db->where("due <",$mktime);
                   break;
               case 1:
                   // today
                   $this->db->where("due <",$tomorrow);
                   $this->db->where("due >",$today);
                   break;
               case 2:
                   // next 3 days
                   $this->db->where("due <",$next_3);
                   $this->db->where("due >",$tomorrow);
                   break;
  
           }
           
           if($type == 3){
               $this->db->where("later",1);
           }else{
               $this->db->where("later !=",1);
           }
           $this->db->where("tasks.status",0);
           
           $this->db->where_in('tasks.dep', $array);
           $this->db->join('users','users.u_id=tasks.creator','Left');
           $this->db->join('comments','comments.c_id=tasks.comment_id','Left');
           $this->db->join('projects','projects.p_id=comments.project_id','Left');
           $this->db->join('customers','customers.c_id=projects.customer_id','Left');
           $this->db->join('dep','dep.id=tasks.dep','Left');
           $query=$this->db->get();
           return $query;
        }    
        
        function show_task_section($type=0){
            $tasks=$this->functions->get_tasks_switch($type);
            $string=NULL;
            foreach ($tasks->result() as $value) {
                if($value->due){
                    $date=$this->functions->time_left ($value->due);
                }else{
                    $date="Later";
                }
                $string.="<tr>";
                $string.= "<td><input type='checkbox' class='resolve' data='".$value->t_id."' /></td>";
                $text=  character_limiter($value->text, 100);
                
                $string.= "<td><a href='#' class='task_link  task_link_".$value->t_id."' data='".$value->t_id."' >".$text."</a></td>";  
                $string.= "<td>".$value->dep_title."</td>";   
                $string.= "<td>".$date."</td>";
                $string.= "<td>".$value->user_name."</td>";
              //  $string.= "<td>".$value->priority."</td>";
                $string.="</tr>";
                
            }
            return $string;
        }
        
        function time_left($time){
            $mktime=mktime();
            $diff=$time-$mktime;
            $day=intval($diff/(3600*24));
            if($diff>=0){
               $after=" Left";
            }else{
               $after=" Ago"; 
            }
             $return=abs($day)." day(s)".$after;
             return $return;
        }
        function dep_user_array($user_id){
           $this->db->select("*");
           $this->db->from("user_relation");
           $this->db->where("r_user_id",$user_id);
           $query=$this->db->get();
           $array=array();
           foreach ($query->result() as $value) {
               $array[]=$value->r_dep;
           }
           return $array;
        }
        function ajax_your_tasks($status=0){
                $array=$this->dep_user_array($this->session->userdata("user_id"));

		$this->db->select('tasks.t_id , tasks.text as task_text ,tasks.dep, dep.title as dep_title , tasks.due , users.name as user_name , projects.p_id , tasks.status as task_status , tasks.later');
		
		$this->db->from('tasks');
		
                if($status < 2){
                   $this->db->where('tasks.status',$status);
                }
		$this->db->where_in('tasks.dep', $array);
                
                $this->db->join('users','users.u_id=tasks.creator','Left');
                $this->db->join('comments','comments.c_id=tasks.comment_id','Left');
                $this->db->join('projects','projects.p_id=comments.project_id','Left');
                $this->db->join('customers','customers.c_id=projects.customer_id','Left');
                
                $this->db->join('dep','dep.id=tasks.dep','Left');
                
                $aColumns = array( 't_id','task_text','dep_title','due','user_name','p_id','task_status','later');
                $aColumns_real = array( 't_id','tasks.text','dep.title','users.name');

                /* 
                 * Paging
                 */

                 if ($this->input->post('iDisplayStart') != NULL && $this->input->post('iDisplayLength') != '-1') {
                        $this->db->limit($this->input->post('iDisplayLength'), $this->input->post('iDisplayStart'));
                 }

                /*
                 * Ordering
                 */
                $sOrder = "";
                if ($this->input->post('iSortCol_0')) {
                    $sort = $sOrder = array();
                    for ($i = 0; $i < intval($this->input->post('iSortingCols')); $i ++) {
                        if ($this->input->post('bSortable_' . intval($this->input->post('iSortCol_' . $i))) == "true") {
                            $sOrder = $aColumns [intval($this->input->post('iSortCol_' . $i))];
                            $sort = ($this->input->post('sSortDir_' . $i) === 'asc' ? 'asc' : 'desc');
                            $this->db->order_by($sOrder, $sort);
                        }
                    }
                }else{
                            $this->db->order_by("tasks.due = 0 ASC, tasks.due ASC");
                }

                if ( $this->input->post('sSearch') && $this->input->post('sSearch') != "" )
                {

                        $search_value=$this->input->post('sSearch');
                        $where = "( $aColumns_real[0] LIKE '%$search_value%' || $aColumns_real[1] LIKE '%$search_value%' || $aColumns_real[2] LIKE '%$search_value%'  || $aColumns_real[3] LIKE '%$search_value%')";

                        $this->db->where($where);

                }

                        $customer_data=$this->db->get();

                        $iTotal=$this->ajax_your_tasks_total($status);
                        $iFilteredTotal=$customer_data->result_array();
                /*
                 * Output
                 */
                $output = array(
                        "sEcho" => intval($this->input->post('sEcho')),
                        "iTotalRecords" => $iTotal,
                        "iTotalDisplayRecords" => $iTotal,
                        "aaData" => array(),
                        "aColumns"=>$aColumns
                );

                $count = $this->input->post('iDisplayStart') + 1;
                               
                foreach ($iFilteredTotal as $key => $aRow) {
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$this->t_id=$aRow[ $aColumns[0] ];
                        $this->task_status=$aRow[ $aColumns[6] ];
                        $this->due =$aRow[ $aColumns[3] ];
                        $this->later =$aRow[ $aColumns[7] ];
			if ( $aColumns[$i] == "version" )
			{
				
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $this->print_table_tasks( $aColumns[$i],$aRow[ $aColumns[$i] ]);
			}
			else if ( $aColumns[$i] != ' ' )
			{
				
				$row[] = $this->print_table_tasks( $aColumns[$i],$aRow[ $aColumns[$i] ]);
			}
		}
		$output['aaData'][] = $row;
	}

                echo json_encode( $output );

	}
        
        
        function ajax_your_tasks_total($status=0){
                $array=$this->dep_user_array($this->session->userdata("user_id"));

		$this->db->select('tasks.t_id , tasks.text as task_text ,tasks.dep, dep.title as dep_title , tasks.due , users.name as user_name , projects.p_id , tasks.status as task_status');
		
		$this->db->from('tasks');
		
                if($status < 2){
                   $this->db->where('tasks.status',0);
                }
		$this->db->where_in('tasks.dep', $array);
                
                $this->db->join('users','users.u_id=tasks.creator','Left');
                $this->db->join('comments','comments.c_id=tasks.comment_id','Left');
                $this->db->join('projects','projects.p_id=comments.project_id','Left');
                $this->db->join('customers','customers.c_id=projects.customer_id','Left');
                
                $this->db->join('dep','dep.id=tasks.dep','Left');
                
                $aColumns = array( 't_id','task_text','dep_title','due','user_name','p_id','task_status');
                $aColumns_real = array( 't_id','tasks.text','dep.title','users.name');

                
                if ( $this->input->post('sSearch') && $this->input->post('sSearch') != "" )
                {

                        $search_value=$this->input->post('sSearch');
                        $where = "( $aColumns_real[0] LIKE '%$search_value%' || $aColumns_real[1] LIKE '%$search_value%' || $aColumns_real[2] LIKE '%$search_value%'  || $aColumns_real[3] LIKE '%$search_value%')";

                        $this->db->where($where);

                }

                        $customer_data=$this->db->get();

              return $customer_data->num_rows();

	}
        
        function insert_customer(){
            $customer_data = array(
                    'name'			=>	set_value('name') ,
                    'email'			=>	set_value('email') ,
                    'mobile'                    =>	set_value('mobile') ,
                    'tel'                       =>	set_value('tel') ,
                    'address'                   =>	set_value('address') ,
                    'desc'                      =>	set_value('customer_desc') ,
                    'user_id'                   =>      $this->session->userdata("user_id"),
                    'date'                      =>	mktime(),
            ) ;

            // insert customers
            $this->db->insert('customers', $customer_data) ;
            return $this->db->insert_id();
        }
        
        function insert_buy($customer_id){
            $factor_date=$this->input->post("factor_date");
            if($factor_date){
                $factor_date=$this->jalali_to_date($factor_date);
            }else{
                $factor_date=mktime();
            }
            $array=array(
              "b_date"          =>  $factor_date,
              "b_submitdate"    =>  mktime(),
              "b_total_price"   =>  $this->functions->unmask($this->input->post("overal")),
              "b_final_price"   =>  $this->functions->unmask($this->input->post("final")),
              "b_status"        =>  1,
              "b_customer_id"   =>  $customer_id,
              "b_factor_num"    =>  $this->input->post("factor_num")
            );
            $this->db->insert('buy', $array) ;
            $insert_id=$this->db->insert_id();
            return $insert_id;
        }
        
        function update_obj($id,$i){
            $price=$this->input->post("buy_price".$i);  
            $obj_data=array(
                "obj_buy_price"     =>  $this->functions->unmask($price),
                "obj_sell_price"    =>  $this->functions->unmask($price),
                "obj_last_change"   =>  mktime(),
                "obj_desc"          =>  $this->input->post("obj_desc".$i)
            );
           $this->db->update('object ',$obj_data , array('obj_id' => $id));
           
        }
        function search_name_addbuy($word){
		$return_array=array();
		$this->db->select('*');
                $this->db->from('object');
                $where = "( obj_title LIKE '%$word%' || obj_desc LIKE '%$word%' )";
                $this->db->where($where);
                $this->db->where("obj_status ",1);
                $this->db->limit(10);
                $products =$this->db->get();
		foreach ($products->result() as  $value) {
			$return_array[]=array("key"=>$value->obj_id,"value"=>$value->obj_title,"buy_price"=>$value->obj_buy_price);
		}
		return $return_array;
	}
        
        function accounting_accounts(){
           $query=$this->db->select('*')->from('accounting_account')->where('aa_status',1)->get('');
           return $query;
        }
        
        function accounting_accounts_option(){
           $accounts= $this->accounting_accounts();
           $accounts_option=NULL;
		foreach ($accounts->result() as  $value) {
		     $accounts_option.= "<option value='".$value->aa_id."'>".$value->aa_name."</option>";
		}
           return $accounts_option;
        }
        function charge_way(){
           
             $charge_way=$this->db->select('*')->from('charge_way')->where('cw_status',1)->get('');
             return $charge_way;
        }
        
        function charge_way_options(){
             $charge_way_option=NULL;
             $charge_way=$this->charge_way();
            foreach ($charge_way->result() as  $value) {
		 $charge_way_option.= "<option value='".$value->cw_id."'>".$value->cw_title."</option>";
             }
            return  $charge_way_option;
        }
        
	function check_account(){
		$accounts = $this->db->select('*')->from('accounting_account')->where("aa_status",1)->where("aa_cheque",1)->get();
		return $accounts;
	}
	
	function check_account_option(){
		$return=NULL;
		foreach ($this->check_account()->result() as  $value) {
			$return.="<option value='".$value->aa_id."'>".$value->aa_name."</option>";
		} 
		return $return;
	}
        function charge_way_real(){
           
             $charge_way=$this->db->select('*')->from('charge_way')->where('cw_id !=',4)->where('cw_status',1)->get('');
             return $charge_way;
        }
        
        function charge_way_real_options(){
             $charge_way_option=NULL;
             $charge_way=$this->charge_way_real();
            foreach ($charge_way->result() as  $value) {
		 $charge_way_option.= "<option value='".$value->cw_id."'>".$value->cw_title."</option>";
             }
            return  $charge_way_option;
        }
        function search_customer_name($word){
		$return_array=array();
		$customers = $this->db->select('*')->from('customers')->like("name",$word)->limit(10)->get();
		foreach ($customers->result() as  $value) {
			$return_array[]=array("key"=>$value->c_id,"value"=>$value->name,"mobile"=>$value->mobile,"address"=>$value->address,"desc"=>$value->desc,"tell"=>$value->tel);
		}
		
		return $return_array;
	}
	
        function is_customer($customer_id,$customer_name){
                $this->db->select('*');
                $this->db->from('customers');
                $this->db->where("c_id ",$customer_id);
                $this->db->where("name ",trim($customer_name));
                $customer =$this->db->get();
                return $customer->num_rows();
        }
        
        /////////////// buy ajax
        
        function buys_ajax(){
		
		$this->db->select('buy.*, customers.name as customer_name');
		
		$this->db->from('buy');
		
		$this->db->where('buy.b_status >',0);

                $aColumns = array( 'b_id','b_factor_num','customer_name','b_date','b_final_price');
                $aColumns_real = array( 'b_id','b_factor_num','customer_name','b_date','b_final_price');

                /* 
                 * Paging
                 */

                if (  $this->input->post('iDisplayStart')!=NULL && $this->input->post('iDisplayLength') != '-1' )
                {
                                $this->db->limit($this->input->post('iDisplayLength'),$this->input->post('iDisplayStart'));

                }

                /*
                 * Ordering
                 */
                $sOrder = "";
                if ( $this->input->post('iSortCol_0'))
                {
                        $sort=$sOrder = array();
                        for ( $i=0 ; $i<intval( $this->input->post('iSortingCols') ) ; $i++ )
                        {
                                if ( $this->input->post( 'bSortable_'.intval($this->input->post('iSortCol_'.$i))) == "true" )
                                {
                                        $sOrder = $aColumns[ intval( $this->input->post('iSortCol_'.$i) ) ];
                                        $sort=($this->input->post('sSortDir_'.$i)==='asc' ? 'asc' : 'desc');
                                        $this->db->order_by($sOrder,$sort);
                                }
                        }
                }else{
                                                $this->db->order_by("b_id","DESC");
                }

                if ( $this->input->post('sSearch') && $this->input->post('sSearch') != "" )
                {

                        $search_value=$this->input->post('sSearch');
                        $where = "( $aColumns_real[0] LIKE '%$search_value%' || $aColumns_real[1] LIKE '%$search_value%'  || $aColumns_real[2] LIKE '%$search_value%' )";
                        $this->db->where($where);

                }

		$this->db->join('customers','customers.c_id=buy.b_customer_id','Left');

		$customer_data=$this->db->get();
		
		$iTotal=$this->buys_ajax_total();
		$iFilteredTotal=$customer_data->result_array();

                /*
                 * Output
                 */
                $output = array(
                        "sEcho" => intval($this->input->post('sEcho')),
                        "iTotalRecords" => $iTotal,
                        "iTotalDisplayRecords" => $iTotal,
                        "aaData" => array(),
                        "aColumns"=>$aColumns
                );

                foreach ($iFilteredTotal as $key => $aRow) {
                        $row = array();
                        for ( $i=0 ; $i<count($aColumns) ; $i++ )
                        {
                                $this->buy_id=$aRow[ $aColumns[0] ];
                              
                                if ( $aColumns[$i] == "version" )
                                {

                                        $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $this->print_buy_custom( $aColumns[$i],$aRow[ $aColumns[$i] ]);
                                }
                                else if ( $aColumns[$i] != ' ' )
                                {

                                        $row[] = $this->print_buy_custom( $aColumns[$i],$aRow[ $aColumns[$i] ]);
                                }
                        }
                        $output['aaData'][] = $row;
                }

                echo json_encode( $output );
		
	}

	function buys_ajax_total(){
            
		$this->db->select('buy.*, customers.name as customer_name');
		
		$this->db->from('buy');
		
		$this->db->where('buy.b_status >',0);
		
                $aColumns = array( 'b_id','b_factor_num','customer_name','b_date','b_final_price');
                $aColumns_real = array( 'b_id','b_factor_num','customer_name','b_date','b_final_price');

                /* 
                 * Paging
                 */

                if (  $this->input->post('iDisplayStart')!=NULL && $this->input->post('iDisplayLength') != '-1' )
                {
                                $this->db->limit($this->input->post('iDisplayLength'),$this->input->post('iDisplayStart'));

                }

                /*
                 * Ordering
                 */
                $sOrder = "";
                if ( $this->input->post('iSortCol_0'))
                {
                        $sort=$sOrder = array();
                        for ( $i=0 ; $i<intval( $this->input->post('iSortingCols') ) ; $i++ )
                        {
                                if ( $this->input->post( 'bSortable_'.intval($this->input->post('iSortCol_'.$i))) == "true" )
                                {
                                                $sOrder = $aColumns[ intval( $this->input->post('iSortCol_'.$i) ) ];
                                                $sort=($this->input->post('sSortDir_'.$i)==='asc' ? 'asc' : 'desc');
                                                $this->db->order_by($sOrder,$sort);
                                }
                        }
                }else{
                                                $this->db->order_by("b_id","DESC");
                }

                if ( $this->input->post('sSearch') && $this->input->post('sSearch') != "" )
                {

                        $search_value=$this->input->post('sSearch');
                        $where = "( $aColumns_real[0] LIKE '%$search_value%' || $aColumns_real[1] LIKE '%$search_value%'  || $aColumns_real[2] LIKE '%$search_value%' )";
                        $this->db->where($where);

                }

		$this->db->join('customers','customers.c_id=buy.b_customer_id','Left');

		$customer_data=$this->db->get();
                
		$iTotal= $customer_data->num_rows();
		
		return $iTotal;

	}
	
	function print_buy_custom($col,$var){
		$return=$var;
		switch ($col) {
			case 'customer_name':
                            $link=  site_url("pages/show_buy/".$this->buy_id);
                            $return="<a href='".$link."'>".$var."</a>";
				
				break;
			case 'b_date':
                            
                            $return=$this->functions->pdate($var);
				
				break;
		}
		
		return $return;
	}

        /////////////  end buy
        
}	

?>
