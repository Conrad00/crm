<script>
	$(function(){

        $(".comment_table").dataTable({
                        

        'bServerSide'    : true,
        'bAutoWidth'     : false,
        'sPaginationType': 'full_numbers',
        'sAjaxSource': '<?php echo site_url("pages/ajax_comments/".$p_id)?>',
        'aoColumns' : [
            { 'sName': 'text'},
            { 'sName': 'projects_title' }, 
            { 'sName': 'date'},
            { 'sName': 'user_real_name' }
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
   });
 </script>

            
                    <div class="well">
                        <?php
                        if($p_id){?>
                             <button class="btn btn-primary" data-toggle="modal" data-target="#edit_status">
                             New Comment
                            </button>
                         
                        <?php } ?>
                        
                         <h3>comments</h3>
                         
                         <table class="table rtl comment_table" >
                                <thead>
                                        <th>Text</th>
                                        <th>Order Title</th>
                                        <th>Time</th>
                                        <th>User</th>
                                </thead>
                                <tbody>

                                </tbody>
                        </table>
                         
                     </div>