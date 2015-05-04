<?php $this->load->view("includes/header") ?>
<?php
$details=json_decode($file_info->details);
?>
<script src="<?php echo base_url()?>front_theme/js/jquery.maskMoney.js" type="text/javascript"></script>

<script>
    
$(function(){
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
      
      $(document).unbind('keydown').bind('keydown', function (event) {
            var doPrevent = false;
            if (event.keyCode === 8) {
                var d = event.srcElement || event.target;
                if ((d.tagName.toUpperCase() === 'INPUT' && (d.type.toUpperCase() === 'TEXT' || d.type.toUpperCase() === 'PASSWORD' || d.type.toUpperCase() === 'FILE' || d.type.toUpperCase() === 'EMAIL' )) 
                     || d.tagName.toUpperCase() === 'TEXTAREA') {
                    doPrevent = d.readOnly || d.disabled;
                }
                else {
                    doPrevent = true;
                }
            }

            if (doPrevent) {
                event.preventDefault();
            }
        });
      
    function mask(value) {

       r=value.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
       return r;
    }
    function unmask(price){
        r=parseFloat(price.toString().toString().replace(/[^0-9-.]/g, ''));
        return r;
    }
  
    var rows=parseInt(<?php echo $file_rows->num_rows() ?>);
    $(".add_row").click(function(){
        rows++;
        desc=parseInt(<?php echo $file_info->is_desc ?>);
        decent=parseInt(<?php echo $file_info->is_decent ?>);
        
        tr_string="<tr id='row_"+rows+"'><td>"+rows+" <a href='#' class='remove_row red' id='remove_row_"+rows+"' data='"+rows+"'>X</a></td>";
        tr_string+='<td><input type="text" name="title'+rows+'" class="title'+rows+'" /></td>';
        tr_string+='<td><input type="text" name="num'+rows+'" class="key maskit num'+rows+'" value="0" /></td>';
        tr_string+='<td><input type="text" name="price'+rows+'" class="key maskit price price'+rows+'" data="'+rows+'" placeholder="Rials"  value="0" /></td>';
        
        if(decent){
            tr_string+='<td><select name="type'+rows+'" class="select type'+rows+'"><option value=1>Percentage</option><option value=2>Quantitative</option></select></td>';
            tr_string+='<td><input type="text" name="value'+rows+'" class="key maskit value'+rows+'" value="0" /></td>';
        }
         tr_string+='<td><input type="text" name="total'+rows+'" readonly="readonly" class=" maskit total'+rows+'" /></td>';
        if(desc){
            tr_string+='<td><input type="text" name="desc'+rows+'" class="desc'+rows+'" /></td>';
        }
        tr_string+="</tr>";
       $(".table_product tbody").append(tr_string);
       $(".num_rows").val(rows);
       return false;
    });
    sell=<?php echo count((array)$details->setting) ?>;

    $(".add_sell").click(function(){
        sell++;
        sell_string="<tr id='row_sell_"+sell+"'><td><a href='#' class='remove_sell red' id='remove_sell_"+sell+"' data='"+sell+"'>X</a></td>";
        sell_string+='<td><select  id="selltype_'+sell+'" name="selltype_'+sell+'" class="select inc_field" ><option value=1>Increase total</option><option value=2>Decrease Total</option></select></td>';
        sell_string+='<td><input type="text" id="selltitle_'+sell+'" name="selltitle_'+sell+'" value="" /></td>';
        sell_string+='<td><select  class="select inc_field" id="sellunit_'+sell+'" name="sellunit_'+sell+'"  data="'+sell+'" ><option value=1>% of Total price</option><option value=2>Constant value(Rials)</option></select></td>';
        sell_string+="<td><input type='text' class='key maskit inc_input' id='sellinput_"+sell+"' name='sellinput_"+sell+"' value='0'  data='"+sell+"'/></td>";
         sell_string+="<td><input type='text' class='inc_price maskit' id='sellprice_"+sell+"' name='sellprice_"+sell+"' value='0'  data='"+sell+"'/></td>";
        sell_string+="</tr>";
       $("#add_table tbody").append(sell_string);
       $(".sell_rows").val(sell);
       return false;
    });
    

    function cal_overal(){
                // calculate all rows
                overal=0;
                $(".price").each(function(){
                    row=$(this).attr("data");
                    price=$(".price"+row).val();
                    price=unmask(price);
                    num=$(".num"+row).val();
                    num=unmask(num);
                    total_price=price*num;
                   
                  
                    decent=parseInt(<?php echo $file_info->is_decent ?>);
                    $decreased=0;
                    if(decent){
                         type=$(".type"+row).val();
                         value=unmask($(".value"+row).val());
                         if(type == 1){
                             // percentage
                             $decreased=value*total_price/100;
                         }else{
                            //  valued
                            value=unmask(value);
                             $decreased=value;
                         }   
                    }
                    total=parseInt(total_price)- parseInt($decreased);
                     $(".total"+row).val(mask(total));
                    // calculate overl
                    overal+=total;
                });
                
                // set in overal
                $(".overal_price").html(mask(overal));
                $(".overal_input").val(overal);
                // retrun overal
                  return overal;
    }
    
    function cal_add(overal){
                // calculate all rows
                total=0;
                $(".inc_input").each(function(){
                    row=$(this).attr("data");
                    di=$("#selltype_"+row).val();
                    unit=$("#sellunit_"+row).val();
                    value=$("#sellinput_"+row).val();
                    value=unmask(value);
                    if(di==1){
                        co=1;
                    }else{
                        co=-1;
                    }
                    // calculate add
                    if(unit==1){
                        //percentage
                        total_add=(value*overal/100)*co;
                    }else{
                        //valued
                        total_add=(value)*co;
                    }
                    abs=Math.abs(total_add);
                    $("#sellprice_"+row).val(mask(abs));
                    total+=total_add;
                });
                
                // retrun add
                return total;
    }
    
    function cal_final(){
        overal=cal_overal();
        add=cal_add(overal);
        final=overal+add;
        // set it into final
        $(".final_price").html(mask(final));
        $(".final_input").val(final);
        $(".prepayment").val(mask(final/2));
    }

    $("body").on("keyup",".key",function(){
        $(this).maskMoney({thousands:',', precision:0, allowZero: true});
        cal_final(); 
    });
    
    
    $("body").on("change",".select",function(){
       cal_final(); 
    });
    
    $("body").on("click",'.remove_row',function(){
            id=$(this).attr("data");
            $("#row_"+id).remove();
            cal_final(); 
            return false;
    });
    
    $("body").on("click",'.remove_sell',function(){
            id=$(this).attr("data");
            $("#row_sell_"+id).remove();
             cal_final(); 
            return false;
    });
    
    $(".remove_row").click(function(){
             id=$(this).attr("data");
            $("#row_"+id).remove();
            cal_final(); 
            return false;
    });
   
    $('.credit').change(function() {
         text="Account number:  2-3496157-800-207 Card number:  9579-5380-1211-6274 in EN Bank";

         $(".text1").append(text);

     }); 
     
    $("body").on("keyup",".prepayment",function(){
        $(this).maskMoney({thousands:',', precision:0, allowZero: true});
    });
    
    $(".maskit").each(function(){
       val=mask($(this).val());
       $(this).val(val);
    });
    $(".final_price , .overal_price").each(function(){
       val=mask($(this).text());
       $(this).html(val);
    });
});

</script>



<div class="container">
    <br>
     
            <h2 class="titr rtl">Edit file<?php echo $file_info->alias?></h2>
            <hr>
 
                <?php echo form_open(site_url("pages/edit_file/".$file_info->f_id)); ?>           
            
<div class="container-fluid all_contet">
        <ul class="rtl">
	 <?php echo  validation_errors('<li id="err">', '</li>') ; ?>	
	</ul>	
  <div class="row">
     <div class="col-md-6">
         <div class="well">
             <div class="form-group">
		    <label for="name" class="col-sm-3 control-label">Date:</label>
		    <div class="col-sm-9">
                        <input  name="date" value="<?php echo $this->functions->get_date()?>" />
		    </div>
		</div>
             
             <div class="form-group">
		    <label for="top" class="col-sm-3 control-label">Text above:</label>
		    <div class="col-sm-9">
                        سفارش دهنده: <textarea name="top" class="text"><?php echo $details->top; ?>
                        </textarea>
		    </div>
		</div>

         </div>

     </div>
     <div class="col-md-6">
         <div class="well">
                <div class="form-group">
		    <label for="name" class="col-sm-3 control-label">Text below:</label>
		    <div class="col-sm-9">

                        <textarea name="bottom" class="text1"><?php echo $details->bottom ?></textarea>
                        <br>
                        <input type="checkbox" class="credit" value="1" /> &nbsp;
                        Account number
                        <br>
                        <?php 
                        if($file_info->signiture){
                            $checked="checked='checked'";
                        }else{
                            $checked=NULL;
                        }
                        ?>
                        <input type="checkbox" class="sign" name="sign" value="1" <?php echo $checked ?> />&nbsp; 
                               Company seal
                        
		    </div>
		</div>
         </div>
     </div>
  </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Products</h3>
                <table class="table table_product">
                    <thead>
                        <th>#</th><th>title</th><th width='50px'>num</th><th>Price</th>
                        <?php 
                          if($file_info->is_decent){
                              echo "<th>type of decent</th><th>value of decent</th>";
                          }  
                        ?>
                        <th>جمع</th>
                        <?php 
                          if($file_info->is_desc){
                              echo "<th>Description</th>";
                          }  
                        ?>
                        
                    </thead>
                    <tbody>
                        
                        <?php 
                        $i=1;
                        foreach ($file_rows->result() as $value) {
                            echo "<tr id='row_$i'>";
                            echo "  <td>$i <a href='#' class='remove_row red' id='remove_row_$i' data='$i'>X</a></td>";
                            echo "  <td><input type='text' name='title$i' class='title$i' value='$value->title' /> </td>";
                            echo "  <td> <input type='text' name='num$i' class='key maskit num$i' value='$value->num'  autocomplete='off'/></td>";
                            
                            echo "  <td><input type='text' name='price$i' class='key price maskit price$i' autocomplete='off' data='$i' placeholder='Rials'  value='$value->price'  /> </td>";
                            
                                if($file_info->is_decent){
                                   echo "<td>";
                                   $check1=$check2=NULL;
                                   if($value->decent_type == 1){
                                       $check1="selected";
                                   }else{
                                       $check2="selected";
                                   }
                                   echo  "<select name='type$i' class='select type$i' ><option $check1 value=1>Percentage</option><option $check2 value=2>quantitative</option></select>";
                                   echo "</td>";
                                   
                                   echo "<td>";
                                  
                                   echo  "<input type='text' name='value$i' class='key maskit value$i' value='$value->decent'  autocomplete='off'/>";
                                   echo "</td>";
                                   
                                }  
                                echo "
                                <td>
                                    <input type='text' name='total$i' class='total$i maskit' readonly='readonly' value='$value->total'/>
                                </td>";

                                if($file_info->is_desc){
                                   echo "<td>";
                                   echo  "<input type='text' name='desc$i' class='desc$i' value='$value->desc' />";
                                   echo "</td>";
                                }  
                                echo "</tr>";
                              $i++;  
                        }
                        ?>
                        
                       
                    <a class="add_row btn btn-primary" >+</a>
                    </tbody>
                </table>
            
        </div>  
    </div>
    
     <div class="row">
        <div class="col-md-12">
            <div class="overall_price_div">
               Overal: 
                <span class='overal_price label label-info'><?php echo $this->functions->mask($file_info->overal_price) ?></span>
                Rials
            </div>
            <input name="overal_price" class="overal_input" type="hidden" value="<?php echo $file_info->overal_price ?>"/>
        </div>
     </div>
    <hr>
    
     <div class="row">
        <div class="col-md-12">
            <div class="well">
                <h3>Increase/Decrease</h3>
                <table class="table" id="add_table">
                    <thead>
                   <th width='5%'>remove</th><th width='20%'>type</th><th width='20%'>title</th><th width='20%'>calculation</th><th width='14%'>value</th><th>calculated price</th>
                    </thead>
                    <tbody>
                            <?php 
                            
                            $i=1;
                            if($details->setting){
                                foreach ($details->setting as $value) {
                                    echo "<tr id='row_sell_".$i."'>";
                                    echo "<td>";
                                    echo "<a href='#' class='remove_sell red' id='remove_sell_".$i."' data='".$i."'>X</a>";
                                    echo "</td>";
                                    echo "<td>";
                                    $dec=$inc=NULL;
                                    if($value->selltype==1){
                                            $inc="selected='selected'";
                                    }else{
                                            $dec="selected='selected'";
                                    }
                                    echo '<select  id="selltype_'.$i.'" name="selltype_'.$i.'" class="select inc_field" >';

                                            echo "<option value='1' ".$inc.">increase total</option>";
                                            echo "<option value='2' ".$dec.">decrease total</option>";
                                    echo '</select>';
                                    echo "</td>";

                                    echo "<td>";
                                    echo "<input type='text' id='selltitle_".$i."' name='selltitle_".$i."' value='".$value->selltitle."' />";
                                    echo "</td>";

                                    echo "<td>";
                                    echo '<select  class="select inc_field" id="sellunit_'.$i.'" name="sellunit_'.$i.'"  data="'.$i.'" >"';
                                    $per=$con=NULL;
                                    if($value->sellunit==1){
                                            $per="selected='selected'";
                                    }else{
                                            $con="selected='selected'";
                                    }
                                            echo "<option value='1' ".$per.">percentage</option>";
                                            echo "<option value='2' ".$con.">constant</option>";
                                    echo '</select>';
                                    echo "</td>";

                                    echo "<td>";
                                    echo "<input type='text' class='key inc_input maskit'  autocomplete='off' data='".$i."' id='sellinput_".$i."' name='sellinput_".$i."' value='".$value->sellinput."' />";
                                    echo "</td>";
                                    
                                    echo "<td>";
                                   
                                    echo "<input type='text' class='inc_price maskit' readonly data='".$i."' id='sellprice_".$i."' name='sellprice_".$i."' value='$value->sellprice' />";
                                    echo "</td>";
                                    
                                    echo "</tr>";
                                    
                                    $i++;
                             }
                            }
                            


                            ?>
                         
                    <input name="sell_rows" class="sell_rows" type="hidden" value="<?php echo count((array)$details->setting) ?>"/>
                         
                         
                         <a class="add_sell btn btn-default">+</a>
                    </tbody>
	                            		
                </table>
            </div>
        </div>
     </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="final_price_div">
                Total price: 
                <span class='final_price label label-success'><?php echo $this->functions->mask($file_info->final_price) ?></span>
                rials 
                <br> <br>
                <div class="clear"></div>
                <p>
                  Pre-payment:   <input class="prepayment maskit" name="prepayment" value="<?php echo $this->functions->mask($file_info->prepayment) ?>"/>
                </p>
                <p>
                    <?php 
                        if($file_info->fifty){
                            $checked="checked='checked'";
                        }else{
                            $checked=NULL;
                        }
                        ?>
                    
                    <input type="checkbox" name="fifty" value="1" <?php echo $checked ?> />Show 50% prepayment
                </p>
                <?php 
                        if($file_info->remove_total_price){
                            $checked1="checked='checked'";
                        }else{
                            $checked1=NULL;
                        }
                        ?>
                <p>
                    <input type="checkbox" name="remove_total_price" value="1" <?php echo $checked1 ?> />  remove total section
                </p>
            </div>
             <input name="final_price" class="final_input" type="hidden" value="<?php echo $file_info->final_price ?>" />
        </div>
     </div>
    
    
</div>
<br />
<input name="num_rows" class="num_rows" value="<?php echo $file_rows->num_rows() ?>" type="hidden"/>

<div class="container-fluid all_contet" style="text-align: center;">
  <div class="row">
     <div class="col-md-12">
     		
			
			<input name="submit" type="submit" class="btn btn-primary submit_btn" value="Next" />
                        <a href="<?php echo site_url('pages/project/'.$file_info->project_id)?>" class="btn btn-default" > Cancel </a>
     </div>
  </div>
</div>


</div>
     
<?php echo  form_close() ; ?>

	</body>
</html>