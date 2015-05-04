<?php $this->load->view("includes/header") ?>

    
            <div class="modal fade" id="edit_status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                      <h4 class="modal-title" id="myModalLabel">Change status</h4>
                    </div>
                    <div class="modal-body">
                      <iframe src="<?php echo site_url("pages/edit_status/".$project_info->p_id)?>" class="popup status_popup" ></iframe>
                    </div>
                    
                  </div>
                </div>
              </div>


            <div class="container">
                <br>

                <h2 class="titr rtl"><?php echo $project_info->project_title ?></h2>
               
                             <span class="main_label label label-<?php echo $project_info->status_label?>"><?php echo $project_info->status_title ?></span> 
                            
                <a  href="<?php echo base_url()?>" class="btn btn-default back-btn" >Back -></a>
               
                <div class="clear"></div>
                <hr>

            <div class="container-fluid">

              <div class="row">
                 <div class="col-md-5">
                     <div class="well" >
                         <p>
                             <b> Customer Name:</b>  <?php echo $project_info->name ?>
                         </p>
                         
                         <p>
                             <b> Responsible:</b>  <?php echo $project_info->responsible ?>
                         </p>
                         <hr>
                         <p>
                              <b>  Order Description: </b><?php echo $project_info->project_desc ?>
                         </p>
                         <p>
                             <b>  Customer Description: </b><?php echo $project_info->customers_desc ?>
                         </p>
                         <hr>
                         <p>
                             <b>   Telephone: </b><?php echo $project_info->tel ?>
                         </p>
                         <p>
                             <b>   Mobile: </b><?php echo $project_info->mobile ?>
                         </p>
                         <p>
                             <b>   Email: </b><?php echo $project_info->email ?>
                         </p>
                         <p>
                             <b>   Address: </b><?php echo $project_info->address ?>
                         </p>
                          <hr>
                         <p>
                             
                             <a href="<?php echo site_url('pages/edit_project/'.$project_info->p_id)?>" class="btn btn-default">Edit Info</a>
                             &nbsp;&nbsp;&nbsp;
                             <a href="<?php echo site_url('pages/add_order/'.$project_info->c_id)?>" class="btn btn-primary">+ New Order for this customer</a> 
                         </p>
                     </div>

                 </div>
                 <div class="col-md-7">
                     
                     <div class="well">
                         <h3>Status change summery</h3>
                         <table class="table status_table">
                             <?php
                                foreach ($project_status as $value) {
                                  
                                    echo $value;
                                    
                                }
                             ?>
                         </table>
                         
                     </div>

                 </div>
              </div>
            </div>
            <br />

            
            <div class="container-fluid">

              <div class="row">
                 <div class="col-md-12">
                        
                     <?php
                     $this->load->view("comments",array("p_id"=>$project_info->p_id));
                     ?>
                 </div>
                 
              </div>
            </div>
<br /><br />

                <div class="container-fluid">
                    
                  <div class="row">
                     <div class="col-md-12">
                         <a href="<?php echo site_url('pages/create_file_1/'.$project_info->p_id)?>" class="btn btn-primary" >+ New Invoice</a>
                         <table class="table">
                             <thead>
                             <th>Title</th>
                             <th>Date</th>
                             <th>Final amount</th>
                             <th>Paid amount</th>
                             <th>Creator</th>
                             <th>Status</th>
                             </thead>
                             <tbody>
                                 <?php
                                        foreach ($project_files->result() as $row) {
                                            echo "<tr><td>";
                                            $link=  site_url("pages/show_file/".$row->f_id);
                                            echo "<a href='".$link."'>".$this->functions->title_letter($row->type)." ".$row->alias."</a>";
                                            echo "</td><td>";
                                            echo $this->functions->pdate($row->date);
                                            echo "</td><td>";
                                            echo number_format($row->final_price);
                                            echo "</td><td>";
                                            
                                            if($row->type ==1){
                                                echo number_format($row->prepayment); 
                                            }elseif($row->type ==2){
                                                echo number_format($row->should_pay); 
                                            }
                                            
                                            echo "</td><td>";
                                            echo $row->name; 
                                            echo "</td><td>";
                                            echo $this->functions->file_status($row->status); 
                                            echo "</td></tr>";
                                        }

                                 ?>
                             </tbody>
                         </table>
                         
                     </div>

                  </div>
                </div>

<br />
<br />
<br />
<br />

            </div>
     
	</body>
</html>