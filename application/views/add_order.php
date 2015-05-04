<?php $this->load->view("includes/header") ?>
<div class="container">
    <br>
     
            <h2 class="titr rtl">سفارش جدید</h2>
            <h3 class="titr rtl"><?php echo $customer_name?></h3>
            <hr>
                <?php echo form_open(site_url("pages/add_order/".$c_id)); ?>           
            
<div class="container-fluid all_contet well">
        <ul class="rtl">
	 <?php echo  validation_errors('<li class="red">', '</li>') ; ?>	
	</ul>	
  <div class="row">
     <div class="col-md-6">
         
                <div class="form-group">
		    <label for="name" class="col-sm-4 control-label">*  عنوان سفارش:</label>
		    <div class="col-sm-8">
                        <input name="project" type="text" class="form-control"  placeholder="مثلا سفارش اول" value="<?php echo $this->functions->project_name()?>">
		    </div>
		</div>

                <div class="form-group">
		    <label for="responsible" class="col-sm-4 control-label">*  مسئول پیگیری:</label>
		    <div class="col-sm-8">
		      <input name="responsible" type="text" class="form-control" id="responsible" placeholder="نام و نام خانوادگی" value="<?php echo set_value('responsible')?>" >
		    </div>
		</div>
		
     </div>
     <div class="col-md-6">
          
         
        <div class="form-group">
            <label for="project_desc" class="col-sm-4 control-label">توضیحات سفارش:</label>
            <div class="col-sm-8">
                <textarea name="project_desc" id='project_desc' class="form-control text" ><?php echo set_value('project_desc')?></textarea>
            </div>
        </div>
         
         
		
     </div>
  </div>
</div>
<br />

<div class="container-fluid all_contet" style="text-align: center;">
  <div class="row">
     <div class="col-md-12">
     		
			
			<input name="submit" type="submit" class="btn btn-primary submit_btn" value="ارسال" />
                        <a href="<?php echo base_url()?>" class="btn btn-default" > بازگشت </a>
     </div>
  </div>
</div>


</div>
     
<?php echo  form_close() ; ?>

	</body>
</html>