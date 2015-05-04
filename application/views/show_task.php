        <div class="modal fade" id="edit_status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">New status</h4>
              </div>
              <div class="modal-body">
                <iframe src="<?php echo site_url("pages/edit_status/".$project_info->p_id)?>" class="popup status_popup" ></iframe>
              </div>

            </div>
          </div>
        </div>

<script>
    $(function(){

        $(".task_table").dataTable({
                   
            'bServerSide'    : true,
            'bAutoWidth'     : false,
            'sPaginationType': 'full_numbers',
            'sAjaxSource': '<?php echo site_url("pages/ajax_tasks/".$dep)?>',
            'aoColumns' : [
                { 'sName': 't_id'},
                { 'sName': 'task_text' }, 
                { 'sName': 'dep_title'},
                { 'sName': 'due'},
                { 'sName': 'user_name' }
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


        $("body").on('click','.task_link', function () {
                $('#myModal').removeData('bs.modal');
                $('#myModal').modal({remote: '<?php echo site_url("pages/load_task")?>/' + $(this).attr('data') });
                $('#myModal').modal('show');
                return false;
        });

        $("body").on('click','.resolve', function () {
            res=$(this);
            id=res.attr("data");
            if(res.is(':checked')){
                   check=1;
            }else{
                   check=0;
            }


         $.post( "<?php echo site_url("pages/set_status")?>", { id: id , status : check})
            .done(function( data ) {
              if(res.is(':checked')){
                    $(".task_link_"+id).addClass("color_c");
                }else{
                    $(".task_link_"+id).removeClass("color_c");
                }
            });
        });

    });
</script>    

            <div class="row">
             <div class="col-md-12">

                 <div class="well">
                     <h2 class="titr rtl">All tasks</h2>
                       
                      
                            <table class="table rtl task_table" >
                                    <thead>
                                            <th>status</th>
                                            <th>Text</th>
                                            <th>Department</th>
                                            <th>due-time</th>
                                            <th>Creator</th>
                                          
                                    </thead>
                                    <tbody>

                                    </tbody>
                            </table>
                 </div>
             </div>

          </div>