<?php $this->load->view("includes/header") ?>
<div class="container">
    <br>
     
            <h2 class="titr rtl">Edit Order</h2>
            <hr>
 
                <?php echo form_open(); ?>           
            
<div class="container-fluid all_contet well">
        <ul class="rtl">
	 <?php echo  validation_errors('<li id="err">', '</li>') ; ?>	
	</ul>	
  <div class="row">
     <div class="col-md-6">
         
                <div class="form-group">
		    <label for="name" class="col-sm-4 control-label">Order title:</label>
		    <div class="col-sm-8">
                        <input name="project" type="text" class="form-control"  placeholder="Order 7 eq" value="<?php echo $sql_data->project_title?>">
		    </div>
		</div>
         
                <div class="form-group">
		    <label for="name" class="col-sm-4 control-label">Customer name:</label>
		    <div class="col-sm-8">
		      <input name="name" type="text" class="form-control" id="name" placeholder="organization" value="<?php echo $sql_data->name?>" >
		    </div>
		</div>
		<div class="form-group">
		    <label for="responsible" class="col-sm-4 control-label">* Responsible:</label>
		    <div class="col-sm-8">
		      <input name="responsible" type="text" class="form-control" id="responsible" placeholder="First name Last name" value="<?php echo $sql_data->responsible?>" >
		    </div>
		</div>
                <div class="form-group">
		    <label for="inputEmail3" class="col-sm-4 control-label">Tel:</label>
		    <div class="col-sm-8">
		      <input  name="tel" type="text" class="form-control  ltr"  placeholder="Tel" value="<?php echo $sql_data->tel?>">
		    </div>
		</div>
         
                <div class="form-group">
		    <label for="inputEmail3" class="col-sm-4 control-label">Mobile:</label>
		    <div class="col-sm-8">
		      <input  name="mobile" type="text" class="form-control  ltr"  placeholder="Mobile" value="<?php echo $sql_data->mobile?>">
		    </div>
		</div>
         
		<div class="form-group">
		    <label for="inputEmail3" class="col-sm-4 control-label">Email:</label>
		    <div class="col-sm-8">
		      <input  name="email" type="text" class="form-control ltr"  placeholder="Email" value="<?php echo $sql_data->email?>">
		    </div>
		</div>

     </div>
     <div class="col-md-6">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Address:</label>
            <div class="col-sm-8">

               <textarea name="address" id='address' class="form-control text" ><?php echo $sql_data->address?></textarea>
            </div>
        </div>
         
        <div class="form-group">
            <label for="project_desc" class="col-sm-4 control-label">Order Description:</label>
            <div class="col-sm-8">
                <textarea name="project_desc" id='project_desc' class="form-control text" ><?php  echo $sql_data->project_desc ?></textarea>
            </div>
        </div>
         
         <div class="form-group">
            <label for="customer_desc" class="col-sm-4 control-label">Customer Description:</label>
            <div class="col-sm-8">
                <textarea name="customer_desc" id='customer_desc' class="form-control text" ><?php echo $sql_data->customers_desc ?></textarea>
            </div>
        </div>
		
     </div>
  </div>
</div>
<br />

<div class="container-fluid all_contet" style="text-align: center;">
  <div class="row">
     <div class="col-md-12">
     		
			
			<input name="submit" type="submit" class="btn btn-primary submit_btn" value="Submit" />
                        <a href="<?php echo base_url()?>" class="btn btn-default" > Back </a>
     </div>
  </div>
</div>


</div>
     
<?php echo  form_close() ; ?>

	</body>
</html>