      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">from     <?php echo $task_data->user_name  ?> to <?php echo $task_data->dep_title  ?></h4>
        <ul class="menu_link">
            <li><a href="<?php echo site_url('pages/delete_task/'.$task_data->t_id)?>" class="btn btn-danger">Delete</a></li>
            <li><a href="<?php echo site_url('pages/edit_task/'.$task_data->t_id)?>" class="btn btn-default">Edit</a></li>
        </ul>
        <div class="clear" ></div>
      </div>
      <div class="modal-body">
          <?php
                if($task_data->status){
                    $checked='checked';
                    $color_c='color_c';
                }else{
                    $checked=NULL;
                    $color_c=NULL;
                }
                if( $task_data->due < mktime()){
                    $color_c.=' color_r ';
                }
          ?>
          <p  class="<?php echo $color_c?> task_link_<?php echo $task_data->t_id ?>">
              <input class="resolve" type="checkbox" <?php echo $checked ?> data="<?php echo $task_data->t_id ?>" />
              <?php echo $task_data->text  ?>
          </p>
          
      </div>
      <div class="modal-footer">
          <p>
              <?php echo $task_data->comment_text  ?>
          </p>
          <p>
               <?php echo $task_data->project_title . " - ".$task_data->customer_name  ?>
          </p>
      </div>