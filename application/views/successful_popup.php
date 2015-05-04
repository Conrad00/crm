<?php $this->load->view("includes/header_pop") ?>


<p>
    Successfully Saved.
</p>
<?php
    if(isset($project_id)){ 
         $link=site_url('pages/project/'.$project_id);
   }else{
         $link=base_url(); 
   }
?>
   <a href="#" onclick="window.top.location.href='<?php echo $link?>';" class="btn btn-warning" >Close</a>  
</body>
</html>