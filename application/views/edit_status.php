<?php $this->load->view("includes/header_pop") ?>

<!-- new user -->
                <?php
		echo form_open(site_url('pages/edit_status/'.$project_id));
                $option_status=$this->functions->option_status($project_id);
                $get_deps_options=$this->functions->get_deps_options();
		?>

<script>
    $(function(){
        var row=1;
        $(".add_row").click(function(){
            string="<tr class='tr"+row+"'>";
            string+='<td><a href="#" class="removerow remove'+row+'" data="'+row+'">x</a></td>';
            string+='<td><textarea name="text'+row+'" ></textarea></td>';
            string+="<td><select name='dep"+row+"'><?php echo $get_deps_options ?></select></td>";
            string+='<td><input name="due'+row+'" class="due'+row+'" type="text" /></td>';
            string+='<tr>';
            $(".task_table tbody").append(string);
            $('.due'+row).datepicker();
            $(".num").val(row);
            row++;
            return false;
        });
        
        $("body").on("click",".removerow",function(){
           num=$(this).attr("data");
           $(".tr"+num).remove();
           return false;
       });
        
    });
</script>

<div class="container">
    <div class="container-fluid">

              <div class="row">
                  <div class="col-md-12 col-sm-12">
                        <p class="top title">New comment:</p>

                        <textarea class="comment" name="comment"></textarea>
                  </div>
              </div>
        <br>
               <div class="row">
                    <div class="col-md-12 col-sm-12">
                            <span class="top title">Change status to:</span>

                            <select id="status"  name="status">

                                <?php echo $option_status ?>
                            </select>
                    </div>
                </div>
        <br><hr>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <table class="table task_table">
                            <thead>
                                <th width="3%">
                                    Delete
                                </th>
                                <th width="60%">
                                    Text
                                </th>
                                <th width="25%">
                                    Department
                                </th>
                                <th width="12%">
                                   Due-time
                                </th>
                            </thead>
                            <tbody>
                                <!--
                                <tr class="tr1">
                                    <td></td>
                                    <td>
                                        <textarea name="text1" class="text1" ></textarea>
                                    </td>
                                    <td>
                                        <select name="dep1">
                                            <?php echo $get_deps_options ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input name="due1" type="text" />
                                    </td>
                                </tr>-->
                            </tbody>
                            <a href="#" class="add_row btn btn-default" >+ New task</a>
                        </table>
                    </div>
                </div>
        
    </div>
</div>
<input type="hidden" name="num" class="num" value="0"/>
            <div class="modal-footer">
            
             <?php 
             $attributes = array(
             		'name'  => "submit",
             		'value' => "Save",
				    'class' => 'btn btn-primary'
				);
				
              echo form_submit($attributes);
              echo form_close();
              ?>
                 
                <a href="#" onclick="window.top.location.href='<?php echo site_url('pages/project/'.$project_id)?>';" class="btn btn-warning" >Cancel</a>       
            </div>

</body>
</html