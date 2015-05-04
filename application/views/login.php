<?php $this->load->view("includes/header") ?>
<div class="container">

            <h2 class="titr rtl">Login</h2>
            <hr>
 
                <?php echo form_open(); ?>           
            
<div class="container-fluid all_contet well">
        <ul class="rtl">
	 <?php echo  validation_errors('<li id="err">', '</li>') ; 
            echo @$err;
         ?>	
	</ul>	
  <div class="row">
     <div class="col-md-6">
         
                <div class="form-group">
		    <label for="name" class="col-sm-4 control-label">Username:</label>
		    <div class="col-sm-8">
                        <input name="user" type="text" class="form-control"  placeholder="Username" value="<?php set_value('user')?>">
		    </div>
		</div>
         
                <div class="form-group">
		    <label for="name" class="col-sm-4 control-label">Password:</label>
		    <div class="col-sm-8">
		      <input name="pass" type="password" class="form-control" id="name" placeholder="Password"  >
		    </div>
		</div>
                
         <br>
            <p>
                <span>
                    User: ahad  &nbsp;&nbsp;&nbsp;&nbsp;  Pass:godmk3
                </span>
            </p>
            <br>
		<div class="form-group">
		    <input name="submit" type="submit" class="btn btn-primary submit_btn" value="Login" />
		</div>
		
		
     </div>
     <div class="col-md-6">
        
     </div>
  </div>
</div>
<br />



</div>
     
<?php echo  form_close() ; ?>

	</body>
</html>