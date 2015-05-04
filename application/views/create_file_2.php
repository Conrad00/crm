<?php $this->load->view("includes/header") ?>

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
            r=parseFloat(price.toString().replace(/[^0-9-.]/g, ''));
            return r;
        }

        var rows=1;
        $(".add_row").click(function(){
            rows++;
            desc=parseInt(<?php echo $desc?>);
            decent=parseInt(<?php echo $decent?>);

            tr_string="<tr id='row_"+rows+"'><td>"+rows+" <a href='#' class='remove_row red' id='remove_row_"+rows+"' data='"+rows+"'>X</a></td>";
            tr_string+='<td><input type="text" name="title'+rows+'" class="title'+rows+'" /></td>';
            tr_string+='<td><input type="text" name="num'+rows+'" class="key num'+rows+'" value="0" /></td>';
            tr_string+='<td><input type="text" name="price'+rows+'" class="key price price'+rows+'" data="'+rows+'" placeholder="Rials"  value="0" /></td>';

            if(decent){
                tr_string+='<td><select name="type'+rows+'" class="select type'+rows+'"><option value=1>Percentage</option><option value=2>Quantitative</option></select></td>';
                tr_string+='<td><input type="text" name="value'+rows+'" class="key value'+rows+'" value="0" /></td>';
            }
             tr_string+='<td><input type="text" name="total'+rows+'" readonly="readonly" class="total'+rows+'" /></td>';
            if(desc){
                tr_string+='<td><input type="text" name="desc'+rows+'" class="desc'+rows+'" /></td>';
            }
            tr_string+="</tr>";
           $(".table_product tbody").append(tr_string);
           $(".num_rows").val(rows);
           return false;
        });
        sell=<?php echo count((array)$settings) ?>;

        $(".add_sell").click(function(){
            sell++;
            sell_string="<tr id='row_sell_"+sell+"'><td><a href='#' class='remove_sell red' id='remove_sell_"+sell+"' data='"+sell+"'>X</a></td>";
            sell_string+='<td><select  id="selltype_'+sell+'" name="selltype_'+sell+'" class="select inc_field" ><option value=1>Increase total</option><option value=2>Decrease Total</option></select></td>';
            sell_string+='<td><input type="text" id="selltitle_'+sell+'" name="selltitle_'+sell+'" value="" /></td>';
            sell_string+='<td><select  class="select inc_field" id="sellunit_'+sell+'" name="sellunit_'+sell+'"  data="'+sell+'" ><option value=1>% of Total price</option><option value=2>Constant value(Rials)</option></select></td>';
            sell_string+="<td><input type='text' class='key inc_input' id='sellinput_"+sell+"' name='sellinput_"+sell+"' value='0'  data='"+sell+"'/></td>";
            sell_string+="<td><input type='text' class='inc_price' id='sellprice_"+sell+"' name='sellprice_"+sell+"' value='0'  data='"+sell+"'/></td>";
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


                        decent=parseInt(<?php echo $decent?>);
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

        $('.credit').change(function() {
             text="Account number:  2-3496157-800-207 Card number:  9579-5380-1211-6274 in EN Bank";

             $(".text1").append(text);

         }); 

        $("body").on("keyup",".prepayment",function(){
            $(this).maskMoney({thousands:',', precision:0, allowZero: true});
        });

    });

    </script>

<div class="container">
    <br>
     
            <h2 class="titr rtl">Create invoiced <?php echo $project_info->project_title?></h2>
            <hr>
 
                <?php echo form_open(site_url("pages/create_file_2/".$project_info->p_id."/".$type."/".$desc."/".$decent)); ?>           
            
<div class="container-fluid all_contet">
        <ul class="rtl">
	 <?php echo  validation_errors('<li id="err">', '</li>') ; ?>	
	</ul>	
  <div class="row">
     <div class="col-md-6">
         <div class="well">
             <div class="form-group">
		    <label for="name" class="col-sm-3 control-label">Date :</label>
		    <div class="col-sm-9">
                        <input  name="date" value="<?php echo $this->functions->get_date()?>" />
		    </div>
		</div>
             
             <div class="form-group">
		    <label for="top" class="col-sm-3 control-label">Above text:</label>
		    <div class="col-sm-9">
                        Customer: <textarea name="top" class="text"> <?php echo $project_info->name."- ".$project_info->responsible."\n"?><?php 
                        if($project_info->address){
                            echo "Address: ".$project_info->address;
                        }
                        if($project_info->tel){
                            echo "- Tel: ".$project_info->tel;
                        }
                        
                        ?>
                        </textarea>
		    </div>
		</div>

         </div>

     </div>
     <div class="col-md-6">
         <div class="well">
                <div class="form-group">
		    <label for="name" class="col-sm-3 control-label">Below text:</label>
		    <div class="col-sm-9">

                        <textarea name="bottom" class="text1">

All of products have guarantee until 30 months
Payment due time is 1 month after the invoiced date
Project delivery schedule: after receiving an advance payment , until 15 working days after approval form is signed

</textarea>
                        <br>
                        <input type="checkbox" class="credit" value="1" /> &nbsp;
                         Set Bank account number
                        <br>
                        <input type="checkbox" class="sign" name="sign" value="1" checked="checked" />&nbsp; 
                              Set Company seal 
                        
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
                        <th>#</th><th>title</th><th width='50px'>number</th><th>Price</th>
                        <?php 
                          if($decent){
                              echo "<th>type of decent</th><th>value of decent</th>";
                          }  
                        ?>
                        <th>sub-total</th>
                        <?php 
                          if($desc){
                              echo "<th>Description</th>";
                          }  
                        ?>
                        
                    </thead>
                    <tbody>
                        <tr id='tr1'>
                            <td>
                                1 
                            </td>
                            <td>
                                <input type="text" name="title1" class="title1" />
                                    
                            </td>
                             <td>
                                <input type="text" name="num1" class="key num1" value="0"  autocomplete="off"/>
                            </td>
                            <td>
                                <input type="text" name="price1" class="key price price1" autocomplete="off" data="1" placeholder="Rials"  value="0"  />
                            </td>
                           
                            <?php 
                                if($decent){
                                   echo "<td>";
                                   echo  '<select name="type1" class="select type1" ><option value=1>Percentage</option><option value=2>quantitative</option></select>';
                                   echo "</td>";
                                   
                                   echo "<td>";
                                   echo  '<input type="text" name="value1" class="key value1" value="0"  autocomplete="off"/>';
                                   echo "</td>";
                                   
                                }  
                            ?>
                            <td>
                                <input type="text" name="total1" class="total1" readonly="readonly"/>
                            </td>
                            <?php 
                                if($desc){
                                   echo "<td>";
                                   echo  '<input type="text" name="desc1" class="desc1" />';
                                   echo "</td>";
                                 
                                }  
                            ?>
                        </tr>
                    <a class="add_row btn btn-primary" >+</a>
                    </tbody>
                </table>
            
        </div>  
    </div>
    
     <div class="row">
        <div class="col-md-12">
            <div class="overall_price_div">
                Overal: 
                <span class='overal_price label label-info'>0</span>
                Rials
            </div>
            <input name="overal_price" class="overal_input" type="hidden" value="0"/>
        </div>
     </div>
    <hr>
    
     <div class="row">
        <div class="col-md-12">
            <div class="well">
                <h3>increase/decrease</h3>
                <table class="table" id="add_table">
                    <thead>
                    <th width='5%'>remove</th><th width='20%'>type</th><th width='20%'>title</th><th width='20%'>calculation</th><th width='14%'>value</th><th>calculated price</th>
                    </thead>
                    <tbody>
                            <?php 
                            $i=1;
                            foreach ($settings as $value) {
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
                                    echo "<input type='text' class='key inc_input'  autocomplete='off' data='".$i."' id='sellinput_".$i."' name='sellinput_".$i."' value='".$value->sellinput."' />";
                                    echo "</td>";
                                    
                                    echo "<td>";
                                   
                                    echo "<input type='text' class='inc_price' readonly data='".$i."' id='sellprice_".$i."' name='sellprice_".$i."' value='0' />";
                                    echo "</td>";
                                    
                                    echo "</tr>";
                                    
                                    $i++;
                            }


                            ?>
                         
                    <input name="sell_rows" class="sell_rows" type="hidden" value="<?php echo count((array)$settings) ?>"/>
                         
                         
                         <a class="add_sell btn btn-default">+</a>
                    </tbody>
	                            		
                </table>
            </div>
        </div>
     </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="final_price_div">
                total: 
                <span class='final_price label label-success'>0</span>
                Rials 
                <br> <br>
                <div class="clear"></div>
                <p>
                  Pre-payment:   <input class="prepayment" name="prepayment" value="0"/>
                </p>
                <p>
                    <input type="checkbox" name="fifty" value="1" checked="checked" /> Show 50% pre-payment
                </p>
                <p>
                    <input type="checkbox" name="remove_total_price" value="1"  />  remove total section
                </p>
            </div>
             <input name="final_price" class="final_input" type="hidden" value="0" />
        </div>
     </div>
    
    
</div>
<br />
<input name="num_rows" class="num_rows" value="1" type="hidden"/>

<div class="container-fluid all_contet" style="text-align: center;">
  <div class="row">
     <div class="col-md-12">
     		
			
			<input name="submit" type="submit" class="btn btn-primary submit_btn" value="Next" />
                        <a href="<?php echo site_url('pages/project/'.$project_info->p_id)?>" class="btn btn-default" > cancel </a>
     </div>
  </div>
</div>


</div>
     
<?php echo  form_close() ; ?>

	</body>
</html>