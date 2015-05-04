<?php $this->load->view("includes/header") ?>
<div class="container">
    <br>
     
            <h2 class="titr rtl">Create invoice <?php echo $project_info->project_title?></h2>
            <hr>
 
                <?php echo form_open(site_url('pages/create_file_1/'.$project_info->p_id)); ?>           
            
<div class="container-fluid all_contet well">
        <ul class="rtl">
	 <?php echo  validation_errors('<li id="err">', '</li>') ; ?>	
	</ul>	
  <div class="row">
     <div class="col-md-6">
         
         <input type="hidden" name="type" value="1"/>
                <div class="form-group">
		    <label for="name" class="col-sm-8 control-label">Description for every products?:</label>
		    <div class="col-sm-4">
                        <select name="desc">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
		    </div>
		</div>
		
		<div class="form-group">
		    <label for="name" class="col-sm-8 control-label">Decent for every products?:</label>
		    <div class="col-sm-4">
                        <select name="decent">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
		    </div>
		</div>
     </div>
     <div class="col-md-6">
        
     </div>
  </div>
</div>
<br />

<div class="container-fluid all_contet" style="text-align: center;">
  <div class="row">
     <div class="col-md-12">
     		
			
			<input name="submit" type="submit" class="btn btn-primary submit_btn" value="Next" />
                        <a href="<?php echo site_url('pages/project/'.$project_info->p_id)?>" class="btn btn-default" >Cancel</a>
     </div>
  </div>
</div>


</div>
     
<?php echo  form_close() ; ?>

	</body>
</html>