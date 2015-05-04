<script>
    $(function(){

        $(".your_table").dataTable({

            'bServerSide'    : true,
            'bAutoWidth'     : false,
            'sPaginationType': 'full_numbers',
            'sAjaxSource': '<?php echo site_url("pages/ajax_your_tasks/".$status)?>',
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

    });
</script>    

            <div class="row">
             <div class="col-md-12">

                
                     <h2 class="titr rtl">Your tasks</h2>
                            <table class="table rtl your_table" >
                                    <thead>
                                            <th>Status</th>
                                            <th>Text</th>
                                            <th>Department</th>
                                            <th>Due-time</th>
                                            <th>Creator</th>
                                           
                                    </thead>
                                    <tbody>

                                    </tbody>
                            </table>
               
             </div>

          </div>