<?php $this->load->view("includes/header") ?>

    <!-- new user -->
    <?php
        echo form_open(site_url('pages/edit_task/'.$task_info->t_id));
        $get_deps_options=$this->functions->get_deps_options();
    ?>

<script>
    $(function(){
        $('.due').datepicker();
        $('.dep').val(<?php echo $task_info->dep?>);
        $('.status').val(<?php echo $task_info->status?>);
    });
</script>

<div class="container">
    <div class="container-fluid">
        <h4>Edit Task </h4>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        
                        <textarea name="text" class="text" ><?php echo $task_info->text ?></textarea>
                        <select name="dep" class="dep">
                             <?php echo $get_deps_options ?>
                        </select>
                        <input name="due" type="text" class="due" value="<?php echo $this->functions->date2picker($task_info->due)?>"/>
                        <select name="status" class="status">
                            <option value="1">Done</option>
                            <option value="0">Not done</option>
                        </select>

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
                 
                <a href="#" onclick="window.top.location.href='<?php echo base_url()?>';" class="btn btn-warning" >Cancel</a>       
            </div>

</body>
</html