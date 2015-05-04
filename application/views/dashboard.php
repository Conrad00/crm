<?php $this->load->view("includes/header") ?>
              <div class="modal fade" id="add_task" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                      <h4 class="modal-title" id="myModalLabel">New Task</h4>
                    </div>
                    <div class="modal-body">
                      <iframe src="<?php echo site_url("pages/add_task")?>" class="popup status_popup" ></iframe>
                    </div>
                  </div>
                </div>
              </div>

<script>
	$(function(){

        $(".project_table").dataTable({
		            
        'bServerSide'    : true,
        'bAutoWidth'     : false,
        'sPaginationType': 'full_numbers',
        'sAjaxSource': '<?php echo site_url("pages/ajax_projects")?>',
        'aoColumns' : [
            { 'sName': 'p_id'},
            { 'sName': 'title' }, 
            { 'sName': 'name'},
            { 'sName': 'responsible'},
            { 'sName': 'project_date' },
            { 'sName': 'status_title' }
        ],
        'fnServerData': function(sSource, aoData, fnCallback){
            $.ajax({
                'dataType': 'json',
                'type': 'POST',
                'url': sSource,
                'data': aoData,
                'success': fnCallback
            }).done(function(e){
            	
            	
            });
        },
    });
    ////////////////////////////////////////////
    function load_tasks(){
       select_dep= $(".select_dep").val();
       $( ".show_task" ).load( "<?php echo site_url('pages/show_task')?>/"+select_dep );
    }
    
    $(".select_dep").change(function(){
        load_tasks()
    });
        load_tasks();
        
    $(".later_btn").click(function(){
        $(".later_div").toggleClass("hidden");
        return false;
    });
    
    /////////////////////////////////////////
    function your_tasks(){
       select_status= $(".select_status").val();
       $( ".your_task" ).load( "<?php echo site_url('pages/your_task')?>/"+select_status );
    }
    
    $(".select_status").change(function(){
        your_tasks()
    });
        your_tasks();
    ////////////////////////////////////////    
    
    
   });
 </script>
 

 
 <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
    </div>
  </div>
</div>
 
<div class="container">
 
     <a  href="<?php echo site_url('pages/logout')?>" class="btn btn-default back-btn" >Logout x</a>
     &nbsp;&nbsp;
     <span class="welcome">
         Hello  <?php echo $this->session->userdata("user_name")?>
     </span>     
     &nbsp;
      <div class="clear"></div>
      
      <!--  tasks -->
      
      <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                       
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                          <li class="active"><a href="#home" role="tab" data-toggle="tab">Your not done tasks</a></li>
                          <li><a href="#profile" role="tab" data-toggle="tab">All your tasks</a></li>
                          <li><a href="#messages" role="tab" data-toggle="tab">All tasks</a></li>
                          
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="home">
                                <div class="bs-callout bs-callout-danger">
                                    <h4>overdue</h4>
                                        
                                            <table class="table rtl" >
                                                    <thead>
                                                            <th>status</th>
                                                            <th>text</th>
                                                            <th>Department</th>
                                                            <th>due time</th>
                                                            <th>Creator</th>
                                                            
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            echo $this->functions->show_task_section(0);
                                                        ?>
                                                    </tbody>
                                            </table>
                                       
                                </div>
                                
                                
                                <div class="bs-callout bs-callout-warning">
                                   
                                     <h4>Today</h4>
                                    
                                        <table class="table rtl" >
                                                <thead>
                                                            <th>status</th>
                                                            <th>text</th>
                                                            <th>Department</th>
                                                            <th>due time</th>
                                                            <th>Creator</th>
                                                       
                                                </thead>
                                                <tbody>
                                                        <?php 
                                                            echo $this->functions->show_task_section(1);
                                                        ?>
                                                </tbody>
                                        </table>
                                  
                                    
                                </div>
                               
                                <div class="bs-callout bs-callout-info">
                                  <h4>Coming</h4>
                                    <table class="table rtl" >
                                                <thead>
                                                            <th>status</th>
                                                            <th>text</th>
                                                            <th>Department</th>
                                                            <th>due time</th>
                                                            <th>Creator</th>
                                                      
                                                </thead>
                                                <tbody>
                                                        <?php 
                                                            echo $this->functions->show_task_section(2);
                                                        ?>
                                                </tbody>
                                        </table>
                                </div>
                              
                                <div class="later">
                                    <a href="#" class="later_btn btn-info btn" >Later (<?php echo $this->functions->get_tasks_switch(3)->num_rows() ?> task(s)) </a>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#add_task">+ New task</button>
                                    
                                    <br>
                                    <div class="later_div hidden">
                                        <table class="table rtl" >
                                                <thead>
                                                            <th>status</th>
                                                            <th>text</th>
                                                            <th>Department</th>
                                                            <th>due time</th>
                                                            <th>Creator</th>
                                                        
                                                </thead>
                                                <tbody>
                                                        <?php 
                                                            echo $this->functions->show_task_section(3);
                                                        ?>
                                                </tbody>
                                        </table>
                                   </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="profile">
                                <div class="div-pad">
                                    Status : 
                                    <select class="select_status">
                                        <option value="0">Not done</option>
                                        <option value="2">All</option>
                                        <option value="1">Done</option>
                                    </select>
                                </div>
                                
                                <div class="your_task">
                                </div>
                            </div>
                            <div class="tab-pane" id="messages">
                                    <div class="div-pad">
                                        Department: 
                                        <select class="select_dep">
                                              <option value="0">All</option>
                                              <?php echo $this->functions->get_deps_options();?>
                                          </select>
                                    </div>
                                      
                                      <div class="show_task">
                                      </div>
                            </div>
                          
                        </div>


                    </div>
                </div>
            </div>
      
      <!-- end tasks -->
      
            <div class="container-fluid">

              <div class="row">
                 <div class="col-md-12">
                        
                     <?php
                     $this->load->view("comments",array("p_id"=>0));
                     ?>
                 </div>
                 
              </div>
            </div>
 
 
            <div class="container-fluid">

              <div class="row">
                 <div class="col-md-12">
 
                     <div class="well">
                         <h2 class="titr rtl">Orders</h2>
                            <hr>
                            <a href="<?php echo site_url('pages/add_project')?>" class="btn btn-primary">New order</a>
                                <table class="table rtl project_table" >
                                        <thead>
                                                <th>ID</th>
                                                <th>Order title</th>
                                                <th>Customer name</th>
                                                <th>Responsible</th>
                                                <th>Date</th>
                                                <th>Status</th>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                </table>
                     </div>
                 </div>
                 
              </div>
                
                
            </div>
    
           
            
      <p>
          
          Developed by Ahad Nemati
      </p>
</div>
     


	</body>
</html>