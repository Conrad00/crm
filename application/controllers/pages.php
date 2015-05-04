<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {
        
        public function __construct() {
            parent::__construct();
            if (!$this->session->userdata('user_id') && $this->uri->segment(2)!="login") {
                redirect(site_url("pages/login"));
            }
            $this->user_id=$this->session->userdata('user_id');
        }
    
	public function index()
	{
	    // dashboard
            $this->load->view("dashboard");
		
	}
        public function add_project($c_id=0)
	{
                
                $this->form_validation->set_rules('name', 'نام حقوقی مشتری', 'required|min_length[2]|trim') ;
		$this->form_validation->set_rules('email'	, 'ایمیل', 'trim') ;
		$this->form_validation->set_rules('mobile', 'موبایل', 'trim') ;
		$this->form_validation->set_rules('tel'	, 'ثابت', 'trim') ;
		$this->form_validation->set_rules('project', 'عنوان پروژه', 'required|trim') ;
		$this->form_validation->set_rules('project_desc'	, 'توضیحات پروژه', 'trim') ;
		$this->form_validation->set_rules('customer_desc'	, 'توضیحات مشتری', 'trim') ;
		$this->form_validation->set_rules('address'	, 'آدرس', 'trim') ;
                $this->form_validation->set_rules('responsible'	, 'مسئول', 'trim') ;
                
		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('add_project') ;
		}
		else
		{
			
                        $customer_id=$this->functions->insert_customer();
                        $project_data = array(
				
				'title'                 =>	set_value('project') ,
				'desc'                  =>	set_value('project_desc') ,
				'status'		=>	1 ,
				'customer_id'           =>      $customer_id,
				'user_id'               =>      $this->session->userdata("user_id"),
				'date'                  =>	mktime(),
                                'responsible'           =>      set_value('responsible')
			) ;
                        // insert project
			$this->db->insert('projects', $project_data) ;
                        $project_id=$this->db->insert_id();
			$this->load->view("successful");
		}
	}
	public function edit_project($id)
	{

                $this->form_validation->set_rules('name', 'نام مشتری', 'required|min_length[2]|trim') ;
		$this->form_validation->set_rules('email'	, 'ایمیل', 'trim') ;
		$this->form_validation->set_rules('mobile', 'موبایل', 'trim') ;
		$this->form_validation->set_rules('tel'	, 'ثابت', 'trim') ;
		$this->form_validation->set_rules('project', 'عنوان پروژه', 'required|trim') ;
		$this->form_validation->set_rules('project_desc'	, 'توضیحات پروژه', 'trim') ;
		$this->form_validation->set_rules('customer_desc'	, 'توضیحات مشتری', 'trim') ;
                $this->form_validation->set_rules('address'	, 'آدرس', 'trim') ;
                $this->form_validation->set_rules('responsible'	, 'مسئول', 'trim') ;
                
		$data["sql_data"]=$this->functions->project_info($id);
		if($this->form_validation->run() == FALSE)
		{
                        
			$this->load->view('edit_project',$data) ;
		}
		else
		{
			
			$customer_data = array(
				'name'			=>	set_value('name') ,
				'email'			=>	set_value('email') ,
				'mobile'		=>	set_value('mobile') ,
				'tel'                   =>	set_value('tel') ,
                                'address'               =>	set_value('address') ,
				'desc'                  =>	set_value('customer_desc') ,
				'user_id'               =>      $this->session->userdata("user_id"),
				
			) ;
                        
                        // insert customers
                        $this->db->update('customers',$customer_data , array('c_id' => $data["sql_data"]->c_id));
                        
                        $project_data = array(
				
				'title'                 =>	set_value('project') ,
				'desc'                  =>	set_value('project_desc') ,
				'user_id'               =>      $this->session->userdata("user_id"),
                                'responsible'           =>      set_value('responsible')
			) ;
                        // insert project
			$this->db->update('projects',$project_data , array('p_id' => $data["sql_data"]->p_id));
			$this->load->view("successful");
		}
	}
	
	function ajax_projects(){
		
		 return $this->functions->ajax_table_projects();
	}     
	
	function project($id=0){
            if(!$id){
                redirect(base_url());
            }
            $data["project_info"]=$this->functions->project_info($id);
            $data["project_status"]=$this->functions->project_status($id);
            $data["project_files"]=$this->functions->project_files($id);
            $this->load->view('project',$data) ;
        }

        function edit_status($id){
                $this->form_validation->set_rules('status', 'وضعیت', 'required') ;
		$this->form_validation->set_rules('comment', 'وضعیت', 'trim') ;
                
		if($this->form_validation->run() == FALSE)
		{
                        $data["project_id"]=$id;
			$this->load->view('edit_status',$data) ;
		}
		else
		{

                        $insert_commnet=NULL;
                        if($this->input->post("comment")){
                            $insert_commnet.= "<p>". set_value("comment")."</p>";
                        }
                        $project_info=$this->functions->project_info($id);
                        if($project_info->status != set_value('status')){
                   
                            $new_status_info=$this->functions->get_status_info(set_value('status'));
                            $insert_commnet.="<p><span class='label label-".$project_info->status_label."' >".$project_info->status_title."</span>";
                            $insert_commnet.=" ---> ";
                            $insert_commnet.="<span class='label label-".$new_status_info->label."' >".  $new_status_info->title ."</span></p>";
                            
                            //insert into status
                            $this->functions->insert_status(set_value('status'),$id);
                            
                             $this->db->update('projects', array("status"=>set_value('status')), array('p_id' => $id));
                             
                             
                        }
                        // insert comments
                        if($insert_commnet){
                            
                            $comment_data=array(
                              "text"        =>  $insert_commnet,
                              "active"      =>  1,
                              "project_id"  =>  $id,
                              "user_id"     =>  $this->session->userdata("user_id"),
                              "date"        =>  mktime()
                            );
                            
                            $this->db->insert('comments', $comment_data) ;
                            $comment_id=$this->db->insert_id();
                            
                            if($project_info->status != set_value('status')){
                                $this->functions->time_limit($id,$comment_id);
                            }
                            
                            
                            // insert into tasks
                            $num=$this->input->post("num");
                            for($i=1;$i<=$num;$i++){
                                $text=$this->input->post("text".$i);
                                $due=$this->input->post("due".$i);
                                if($due){
                                    $due= $this->functions->jalali_to_date($due);
                                    $due+=((24*3600)-1);
                                }else{
                                    $due=NULL;
                                }
                                $dep=$this->input->post("dep".$i);
                                if($text){
                                    
                                      $task_data=array(
                                        "text"          =>  $text,
                                        "dep"           =>  $dep,
                                        "due"           =>  $due,
                                        "comment_id"    =>  $comment_id,
                                      );
                                      
                                     $this->functions->insert_task($task_data);

                                }
                            }
                        }
                       
                        $data["project_id"]=$id;
			$this->load->view('successful_popup',$data) ;
		}
        }
        
       function ajax_comments($project_id){
           return $this->functions->ajax_table_comments($project_id);
       }
        function ajax_tasks($dep){
           return $this->functions->ajax_table_tasks($dep);
       }
        function ajax_your_tasks($status){
           return $this->functions->ajax_your_tasks($status);
       }
       function login(){
                $this->form_validation->set_rules('user', 'نام کاربری', 'required|trim') ;
		$this->form_validation->set_rules('pass', 'پسورد', 'required|trim') ;
                
		if($this->form_validation->run() == FALSE)
		{
                        
			$this->load->view('login') ;
		}
		else
		{
                    // check username and password
                    $result=$this->functions->check_login(); 
                    if($result){
                        redirect(base_url());
                    }else{
                        $err="نام کاربری و پسورد مطابقت ندارد ";
                        $this->load->view('login',array("err"=>$err)) ;
                    }
                  
                }
       }
       
       function logout(){
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('user_name');
            $this->session->sess_destroy();
            redirect(base_url());
       }
       
       function create_file_1($project_id){
                if($project_id){
                    $this->form_validation->set_rules('type', 'نوع', 'required|trim') ;
                    $this->form_validation->set_rules('desc', 'توضیحات', 'required|trim') ;
                    $this->form_validation->set_rules('decent', 'تخفیف', 'required|trim') ;
                    
                    if($this->form_validation->run() == FALSE)
                    {
                    $data["project_info"]=$this->functions->project_info($project_id);
                        $this->load->view('create_file_1',$data) ;    
                    }
                    else
                    {
                        $type=$this->input->post("type");
                        $desc=$this->input->post("desc");
                        $decent=$this->input->post("decent");
                        
                        redirect(site_url("pages/create_file_2/".$project_id."/".$type."/".$desc."/".$decent));
                    }
                }
 
       }
       
       function create_file_2($project_id=0,$type=1,$desc=0,$decent=0){
            if($project_id==0){
                redirect(base_url());
            }
            $data["type"]=$type;
            $data["desc"]=$desc;
            $data["decent"]=$decent;
            $data["project_info"]=$this->functions->project_info($project_id);
            
            $this->form_validation->set_rules('title1', 'عنوان', 'required|trim') ;
            $this->form_validation->set_rules('num1', 'تعداد', 'required|trim') ;
            $this->form_validation->set_rules('price1', 'مبلغ', 'required|trim') ;
            
            $this->form_validation->set_rules('top', 'متن بالا', 'trim') ;
            $this->form_validation->set_rules('bottom', 'متن پایین', 'trim') ;
            
            if($decent){
                $this->form_validation->set_rules('value1', 'تخفیف', 'required|trim') ;
            }
            if($this->form_validation->run() == FALSE)
            {
                $setting= $this->db->select('*')->from('setting')->where('option','sell_fields')->get('');
                $data["settings"]=json_decode($setting->row()->value);
                $this->load->view('create_file_2',$data) ;    
            }
            else
            {
                // save into a database and show redirect to a prview page
                $num_rows=$this->input->post("num_rows");
                
                $file_id=$this->functions->insert_files($data);
                
                for($i=1;$i<=$num_rows;$i++){
                   $price=$this->input->post("price".$i);  
                   $num=$this->input->post("num".$i);   
                   $total=$this->input->post("total".$i);   
                   if($decent){
                        $type=$this->input->post("type".$i);   
                        $value=$this->input->post("value".$i);  
                        $value=$value?$value:0;
                   }else{
                       $value=0;
                       $type=NULL;
                   }
                   $desc=$this->input->post("desc".$i);
                   if($price && $num){
                       // insert into rows
                       $row_data=array(
                            "title"            =>  $this->input->post("title".$i),
                            "num"              =>  $this->functions->unmask($num),
                            "price"            =>  $this->functions->unmask($price),
                            "total"            =>  $this->functions->unmask($total),  
                            "decent"           =>  $this->functions->unmask($value),
                            "decent_type"      =>  $type,
                            "files_id"         =>  $file_id,
                            "desc"             =>  $this->input->post("desc".$i) 
                          );
                          $this->db->insert('files_row', $row_data) ;
                          $insert_id=$this->db->insert_id();
                   }
                }
                
                redirect("pages/show_file/".$file_id);
            }
      }

      function show_file($file_id){
         $data["show_file"]= $this->functions->file_info($file_id);
         if($data["show_file"]->type == 1){
              $data["file_rows"]= $this->functions->file_rows($file_id);
         }elseif ($data["show_file"]->type == 2){
              $data["file_rows"]= $this->functions->file_rows($data["show_file"]->prefactor);
         }
        
         if($data["show_file"]->title)
         $data["title_page"]=$data["show_file"]->title . "-". $data["show_file"]->name." -شماره: ".$data["show_file"]->alias;
         $this->load->view("show_file",$data);
      }
      
      function accept_file($file_id){
            $sql_data = array(
                    'status'	=>  2 ,
            ) ;
            // insert customers
            $this->db->update('files',$sql_data , array('f_id' => $file_id));
            redirect("pages/show_file/".$file_id);
      }
      
     
      function delete_file($id){
        $this->db->select("*");
        $this->db->from("files");
        $this->db->where("f_id",$id);
        $this->db->where("status",1);
        $query=$this->db->get();
        $num= $query->num_rows();
        if($num){
            $project_id=$query->row()->project_id;
            // delete it
            $this->db->delete('files', array('f_id' => $id)); 
            
            redirect(site_url("pages/project/".$project_id));
        }else{
            // do noting
            redirect(base_url());
        }
      }
      
      function will_pay($id){
        $this->db->select("*");
        $this->db->from("files");
        $this->db->where("f_id",$id);
        $this->db->where("status",2);
        $query=$this->db->get();
        $num= $query->num_rows();
        if($num){
            // update it
            $data = array(
               'status' => 3
            );
            $this->db->where('f_id', $id);
            $this->db->update('files', $data);
            
            redirect(site_url("pages/show_file/".$id));
        }else{
            // do noting
            redirect(base_url());
        }
      }
      
     
       function customer($id){
          // page of customer . show their orders . add new order
          
      }
      
      function edit_file($id=0){
          if(!$id){
              redirect(base_url());
          }
          // edit the files , both of status
            $this->form_validation->set_rules('title1', 'عنوان', 'required|trim') ;
            $this->form_validation->set_rules('num1', 'تعداد', 'required|trim') ;
            $this->form_validation->set_rules('price1', 'مبلغ', 'required|trim') ;
            $this->form_validation->set_rules('top', 'متن بالا', 'trim') ;
            $this->form_validation->set_rules('bottom', 'متن پایین', 'trim') ;
            
           
          if($this->form_validation->run() == FALSE)
            {

                $data["file_info"]= $this->functions->file_info($id);
                $data["file_rows"]= $this->functions->file_rows($id); 
                $this->load->view('update_file',$data) ;    
            }
            else
            {
          
            $file_info=$this->functions->file_info($id);
            if($file_info->status == 1){
                // before accept , update it
                $this->functions->update_file_1($id);
                $file_id=$id;
                // delete all rows of prevouse and insert new
                $this->db->delete('files_row', array('files_id' => $id)); 
                
            }elseif($file_info->status == 2 OR $file_info->status == 3){
                // after accept , insert a new with same alias
                $file_id=$this->functions->update_file_2($id);
                
            }
            // old $id;
            // new $file_id=
            $num_rows=$this->input->post("num_rows");
                
            for($i=1;$i<=$num_rows;$i++){
                   $price=$this->input->post("price".$i);  
                   $num=$this->input->post("num".$i);   
                   $total=$this->input->post("total".$i);   
                   if($file_info->is_decent){
                        $type=$this->input->post("type".$i);   
                        $value=$this->input->post("value".$i);  
                        $value=$value?$value:0;
                   }else{
                       $value=0;
                       $type=NULL;
                   }
                   $desc=$this->input->post("desc".$i);
                   if($price && $num){
                       // insert into rows
                       $row_data=array(
                            "title"            =>  $this->input->post("title".$i),
                            "num"              =>  $this->functions->unmask($num),
                            "price"            =>  $this->functions->unmask($price),
                            "total"            =>  $this->functions->unmask($total),  
                            "decent"           =>  $this->functions->unmask($value),
                            "decent_type"      =>  $type,
                            "files_id"         =>  $file_id,
                            "desc"             =>  $this->input->post("desc".$i) 
                          );
                          $this->db->insert('files_row', $row_data) ;
                          
                   }
                }
                 redirect(site_url("pages/show_file/".$file_id));
             }

      }
      
      function make_factor($id){
          // get the information of prefactor
          // if status is 3
          // insert into db for type 2
          $file_info=$this->functions->file_info($id);
          if($file_info->status == 3){
              // insert a factor
               $invoice_id=$this->functions->insert_invoice($file_info);
               $this->functions->update_prefactor($id,$invoice_id);
              // update column final_invoice
               redirect(site_url("pages/show_file/".$invoice_id));
          }else{
              // go to hell
              redirect(base_url());
          }
      }
      
      
      function load_task($id){
          $data["task_data"]=$this->functions->get_task_data($id);
          $this->load->view("load_task",$data);
      }
      
      function set_status(){
          $id=$this->input->post("id");
          $data["status"]=$this->input->post("status");
          $this->db->update('tasks',$data , array('t_id' => $id));
      }
      
      function show_task($dep=0){
          $data["dep"]=$dep;
           $this->load->view("show_task",$data);
      }
      
      function your_task($status=0){
          $data["status"]=$status;
          $this->load->view("your_task",$data);
      }
      
      function add_task(){
          
            if($this->input->post("submit")){
                     // insert into tasks
                    $num=$this->input->post("num");
                    for($i=1;$i<=$num;$i++){
                        $text=$this->input->post("text".$i);
                        $due=$this->input->post("due".$i);
                        if($due){
                            $due= $this->functions->jalali_to_date($due);
                            $due+=((24*3600)-1);
                            $later=0;
                        }else{
                            $due=0;
                            $later=1;
                        }
                        $dep=$this->input->post("dep".$i);
                        if($text){
                            $task_data=array(
                                "text"          =>  $text,
                                "dep"           =>  $dep,
                                "due"           =>  $due,
                                "comment_id"    =>  0,
                                "later"         =>  $later
                              );
                            
                             $this->functions->insert_task($task_data);
                        }
                    }
                    // end insert
                    $this->load->view("successful_popup");
            }else{
                $this->load->view("add_task");
            }
      }
      
       public function add_order($customer_id=0)
	{
                if(!$customer_id){
                    redirect(base_url());
                }
		$this->form_validation->set_rules('project', 'عنوان پروژه', 'required|trim') ;
		$this->form_validation->set_rules('project_desc'	, 'توضیحات پروژه', 'trim') ;
                $this->form_validation->set_rules('responsible'	, 'مسئول', 'trim') ;
                
		if($this->form_validation->run() == FALSE)
		{
                        $data["c_id"]=$customer_id;
                        $customer_info=$this->functions->customer_info($customer_id);
                        $data["customer_name"]=$customer_info->name;
			$this->load->view('add_order',$data) ;
		}
		else
		{

                        $project_data = array(
				
				'title'                 =>	set_value('project') ,
				'desc'                  =>	set_value('project_desc') ,
				'status'		=>	1 ,
				'customer_id'           =>      $customer_id,
				'user_id'               =>      $this->session->userdata("user_id"),
				'date'                  =>	mktime(),
                                'responsible'           =>      set_value('responsible')
			) ;
                        // insert project
			$this->db->insert('projects', $project_data) ;
                        $project_id=$this->db->insert_id();
			$this->load->view("successful");
		}
	}
      function edit_task($id){
            if($this->input->post("submit")){
                     // insert into tasks
                    $text=$this->input->post("text");
                    $due=$this->input->post("due");
                    $dep=$this->input->post("dep");
                    $status=$this->input->post("status");
                    if($due){
                            $due= $this->functions->jalali_to_date($due);
                            $due+=((24*3600)-1);
                            $later=0;
                    }else{
                            $due=NULL;
                            $later=1;
                    }
                    
                    if($text){
                            $task_data=array(
                                "text"          =>  $text,
                                "dep"           =>  $dep,
                                "due"           =>  $due,
                                "creator"       =>  $this->session->userdata("user_id"),
                                "submitdate"    =>  mktime(),
                                "status"        =>  $status,
                                "later"         =>  $later
                              );

                              $this->db->update('tasks',$task_data , array('t_id' => $id));
                    }
                    
                    // end update
                    redirect(base_url());
            }else{
                $data["task_info"]=$this->functions->get_task_data($id);
                $this->load->view("edit_task",$data);
            }
      }
      
      function delete_task($id){
          $this->db->delete('tasks', array('t_id' => $id));
          redirect(base_url());
      }

      function send_email(){
          $this->functions->email_task(2);
      }
      
      function add_buy(){
          
            $this->form_validation->set_rules('name', 'نام فروشگاه', 'required|min_length[2]|trim') ;
            $this->form_validation->set_rules('email', 'ایمیل', 'trim') ;
            $this->form_validation->set_rules('mobile', 'موبایل', 'trim') ;
            $this->form_validation->set_rules('tel'	, 'ثابت', 'trim') ;
            $this->form_validation->set_rules('buy_desc'	, 'توضیحات خرید', 'trim') ;
            $this->form_validation->set_rules('customer_desc'	, 'توضیحات مشتری', 'trim') ;
            $this->form_validation->set_rules('address'	, 'آدرس', 'trim') ;
            $this->form_validation->set_rules('overal', 'جمع کل', 'required|trim') ;
            $this->form_validation->set_rules('final', 'جمع نهایی', 'required|trim') ;
            $this->form_validation->set_rules('reseller_id', 'شناسه مشتری', 'trim') ;    
            $this->form_validation->set_rules('factor_num', 'شماره فاکتور', 'trim') ;
            
            if($this->form_validation->run() == FALSE)
            {
                $data["charge_way_option"]=$this->functions->charge_way_options();
                $data["accounts_option"]=$this->functions->accounting_accounts_option();
		$data["check_account"]= $this->functions->check_account_option();
                $data["charge_way_real_options"]=$this->functions->charge_way_real_options();
                
                $this->load->view('add_buy',$data) ;
            }
            else
            {
                $reseller_id=$this->input->post("reseller_id");
                $is_correct=$this->functions->is_customer($reseller_id,$this->input->post("name"));
                if($reseller_id && $is_correct){
                    // old customer
                    $customer_id=$this->input->post("reseller_id");
                    // update customer information
                }else{
                    // new customer
                    $customer_id=$this->functions->insert_customer();
                }
                
                $buy_id=$this->functions->insert_buy($customer_id);
                 // save into a database and show redirect to a prview page
                $num_rows=$this->input->post("num_rows");
                
                for($i=1;$i<=$num_rows;$i++){
                    $price=$this->input->post("buy_price".$i);  
                    $num=$this->input->post("num".$i);   
                    $total=$this->input->post("total_price".$i);
                    $obj_id=$this->input->post("obj_id".$i);

                   if($obj_id){
                       // insert into rows
                       $row_data=array(
                            "bo_object_id"      =>  $this->input->post("obj_id".$i),
                            "bo_buy_id"         =>  $buy_id,
                            "bo_num"            =>  $this->functions->unmask($num),
                            "bo_price"          =>  $this->functions->unmask($price),  
                            
                          );
                          $this->db->insert('buy_object', $row_data) ;
                          $this->functions->update_obj($obj_id,$i);
                          //print_r($row_data);
                   }
                }
                 //redirect("pages/add_buy");
                
                // insert into accounting 
                $payment_num=$this->input->post("payment_num");
                for($i=1;$i<=$payment_num;$i++){
                    $payment_charge_way=$this->input->post("payment_charge_way_".$i);  
                    $payment_account=$this->input->post("payment_account_from_".$i);
                    $payment_value=$this->input->post("payment_value_".$i);
                    
                   if($payment_value){
                       // insert into rows
                       $row_data=array(
                            "ca_value"          =>  $this->functions->unmask($payment_value),
                            "ca_way"            =>  $payment_charge_way,
                            "ca_status"         =>  1,
                            "ca_customer_id"    =>  $customer_id,  
                            "ca_user_id"        =>  $this->user_id,
                            "ca_type"           =>  9,
                            "ca_first_account"  =>  $payment_account,
                            "ca_for"            =>  "خرید/خدمات",
                            "ca_submitdate"     =>  mktime(),
                            "ca_buy_id"         =>  $buy_id,
                            "ca_should"         =>  $this->functions->unmask($this->input->post("final"))
                          );
                       if($payment_charge_way==4){
                           // cheque
                           $row_data["ca_cheque_date"]=$this->functions->jalali_to_date($this->input->post("check_date_".$i));
                       }
                          $this->db->insert('charge_account', $row_data) ;
                   }
                }
                
                // insert trans
                if($this->input->post("is_transport") && $this->input->post("trans_value")){
                    $trans_data=array(
                            "ca_value"          =>  $this->functions->unmask($this->input->post("trans_value")),
                            "ca_way"            =>  $this->input->post("trans_charge_way"),
                            "ca_status"         =>  1,
                            "ca_customer_id"    =>  $customer_id,  
                            "ca_user_id"        =>  $this->user_id,
                            "ca_type"           =>  11,
                            "ca_first_account"  =>  $this->input->post("trans_account_from"),
                            "ca_for"            =>  "حمل و نقل",
                            "ca_submitdate"     =>  mktime(),
                            "ca_buy_id"         =>  $buy_id,
                            "ca_should"         =>  $this->functions->unmask($this->input->post("trans_value"))
                          );
                     $this->db->insert('charge_account', $trans_data) ;
                }
                
            }
           
      }
      
      	function add_obj_by_name(){
		$word=$this->input->post("obj_name");
		$type=$this->input->post("obj_type");
		$sql_data = array(
				'obj_title'		=>	$word ,
				'obj_type'              =>	$type ,
				'obj_buy_price'		=>	0 ,
				'obj_sell_price'	=>	0 ,
				'obj_last_change'       =>	mktime() ,
				'obj_status'		=>	1 ,
				'obj_submitdate'	=>	mktime(),
			) ;
		$this->db->insert('object', $sql_data) ;	
		$insert_id=$this->db->insert_id();
		$array=array(
			"id"	=>	$insert_id
		);
		echo json_encode($array);
	}
	
        function search_addbuy(){
		$word=$this->input->post("search_word");
                if($word){
                        $result=$this->functions->search_name_addbuy($word);
                        echo json_encode($result);
                }
	}
        
        
        function search_customer_name(){
            $word=$this->input->post("customer_name");
            if($word){
                    $result=$this->functions->search_customer_name($word);
                    echo json_encode($result);
            }
	}
        
        function shopping_list(){
            $this->load->view("shopping_list");
        }
        
        function buys_list(){
            return $this->functions->buys_ajax();
        }
       
}