<?php $this->load->view("includes/header") ?>

<script src="<?php echo base_url()?>front_theme/js/printThis.js">
</script>
<script>
$(function(){
    $(".print_this").click(function(){
         $(".letter").printThis({
            debug: false,               // show the iframe for debugging
            importCSS: true,            // import page CSS
            printContainer: true,       // grab outer container as well as the contents of the selector
           // loadCSS: "path/to/my.css",  // path to additional css file
            pageTitle: "",              // add title to print page
            removeInline: false,        // remove all inline styles from print elements
            printDelay: 333,            // variable print delay
            header: null,               // prefix to html
            formValues: true            // preserve input/form values
        });
    });
    
});
</script>
            <?php
            $detail_array= json_decode($show_file->details);
            
            ?>
    <div class="clear"></div>
            <div class="container">
                <div class="top_button">
                    <ul>
                        <?php
                            if($show_file->status == 1){
                                // not accepted
                                $link=  site_url('pages/accept_file/'.$show_file->f_id);
                                $delete=  site_url("pages/delete_file/".$show_file->f_id);
                                echo '<li><a href="'.$link.'" onClick="return confirm(\' Are you sure? \')" class="btn btn-success"   >Approve</a></li>';
                               
                                 echo '<li><a href="'.$delete.'" class="btn btn-danger">Delete</a></li>';
                                
                            }elseif ($show_file->status == 2) {
                                // accepted
                                $link=  site_url('pages/will_pay/'.$show_file->f_id);
                                echo '<li><a href="#" class="print_this btn btn-warning">Print</a></li>';
                                echo '<li><a href="'.$link.'" onClick="return confirm(\' Are you sure? \')" class="btn btn-success"   >Paid</a></li>';
                             }else if($show_file->status == 3){
                                 $link=  site_url('pages/make_factor/'.$show_file->f_id);
                                echo '<li><a href="#" class="print_this btn btn-warning">Print</a></li>';
                                if($show_file->type ==1){
                                   echo '<li><a href="'.$link.'" onClick="return confirm(\' Are you sure?\')" class="btn btn-success"   >convert to second invoice</a></li>';    
                                }
                             }
                             if($show_file->type ==1){
                                  echo '<li><a href="'.  site_url('pages/edit_file/'.$show_file->f_id).'" class="btn btn-info">Edit</a></li>';
                             }elseif($show_file->type ==2){
                                  echo '<li><a href="'.  site_url('pages/edit_file/'.$show_file->prefactor).'" class="btn btn-info">Edit</a></li>';
                             }
                            
                        ?>
                    
                    </ul>
                    <a href="<?php echo site_url('pages/project/'.$show_file->project_id)?>" class="back-btn btn btn-default">Back</a>
                </div>
                <div class="clear"></div>
               
                    <?php
                    $date='
                        <div class="top_left">
                            <p>
                                 '. $this->functions->pwdate($show_file->date).'        
                                <br>
                                  '. $show_file->alias .'
                                <br>
                                  No
                            </p>

                        </div>
                        <div class="clear"></div>';
                     $string='    
                        <div class="title_letter">
                           
                            <h3>'. $this->functions->title_letter($show_file->type);
                          
                                if($show_file->status ==1){
                                    $string.= "<span class='label label-danger'>(Not approved)</span>";
                                }
                    if($show_file->type ==1)       {
                        $person="Customer: ";
                    }elseif($show_file->type ==2){
                        $person="Customer: ";
                    }
                    $string.='</h3> 
                        </div>

                        <div class="top_text">
                            <h4>
                                '.$person.' '.nl2br($detail_array->top) .'
                            </h4>

                        </div>
                        <br>
                        <div class="product_table">
                            <table class="table">
                                <thead>
                                    <th>#</th><th>Title</th><th>Number</th><th>Price</th>';
                                  
                                        if($show_file->is_decent){
                                             $string.= "<th>Decent</th>";
                                        }
                                    $string.=' 
                                    <th>sub-total</th>';
                                    
                                     if($show_file->is_desc){
                                           $string.= "<th>Description</th>";
                                     }
                                 $string.=' 
                                </thead>
                                <tbody>';
                                        $i=1;
                                        foreach ($file_rows->result() as $value) {
                                            $string.= "<tr>";
                                            $string.= "<td class='num_row'>";
                                            $string.= $i;
                                            $string.= "</td>";
                                            $string.= "<td>";
                                            $string.= $value->title;
                                            $string.= "</td>";

                                            $string.= "<td>";
                                            $string.= $value->num;
                                            $string.= "</td>";

                                            
                                            $string.= "<td>";
                                            $string.= number_format($value->price);
                                            $string.= "</td>";

                                            
                                            if($show_file->is_decent){
                                                $string.= "<td>";
                                                $string.= number_format($value->decent);
                                                if($value->decent_type == 1){
                                                    $string.= "%";
                                                }else{
                                                    $string.= " Rials";
                                                }
                                                $string.= "</td>";
                                            }

                                            $string.= "<td>";
                                            $string.= number_format($value->total);
                                            $string.= "</td>";

                                            if($show_file->is_desc){
                                                $string.= "<td>";
                                                $string.= $value->desc;
                                                $string.= "</td>";
                                            }

                                            $string.= "</tr>";
                                            $i++;
                                         }
                                   $string.=' 
                                </tbody>

                            </table>

                        </div>';
                                 
                           ?>   
                    
                    <?php
                    $price_string='
                        
                    <div class="price_wrapper">
                        <div class="overal_div">  
                            Overal  : '. number_format($show_file->overal_price)   .' Rials
                            <hr>
                           ' ;
                    if(isset($detail_array->setting)){
                         foreach ($detail_array->setting as $value) {
                                         $price_string.= "<p>";
                                         $price_string.= $value->selltitle;
                                         $price_string.= " : ";
                                         if($value->selltype == 1){
                                             $price_string.= " Increase ";
                                         }else{
                                             $price_string.= " Decrease ";
                                         }
                                         $price_string.= $value->sellinput;
                                         if($value->sellunit ==1){
                                             $price_string.= "% - Equal ";
                                             $price_string.= number_format($value->sellprice)   ;
                                             $price_string.= " Rial ";
                                         }else{
                                             $price_string.= " Rial ";
                                         }
                                         
                                         $price_string.= "</p>";
                                      }
                    }
                                    
                        $price_string.='
                        </div>
                        <div class="final_div">  
                        <br>
                        <table class="table">
                            
                        ';
                                            
                                       
                                            if($show_file->type == 1){  
                                                 $price_string.='<tr><td class="num_row">  Total value: </td><td><b>'. number_format($show_file->final_price)  .'</b> Rial</td></tr>';
                                                 $pay_price=$show_file->prepayment;
                                                 
                                                 $price_string.= "<tr><td class='num_row'>Pre-payment :  </td><td><b>".number_format($pay_price)."</b> Rial";
                                                 if($show_file->fifty){
                                                     $price_string.= " (50% of Total price)";
                                                 }
                                                 $price_string.= "</td></tr>";
                                            }else{
                                                $price_string.='<tr><td class="num_row"> Total value: </td><td><b>'. number_format($show_file->should_pay)  .'</b> Rial</td></tr>';
                                            }
                                        $price_string.='
                                            </table>
                        </div>
                        <div class="clear"></div>
                    </div>
                     
                        
                        <hr>';
                        $other='
                        <div class="bottom_text">
                            
                            <p>
                                '. nl2br($detail_array->bottom) .'
                            </p> 
                            


                        </div>
                        
                         <div class="container-fluid">
                            <div class="row">
                                <div class="righted">
                                    Customer signature :
                                    
                                </div>
                                <div class="lefted">
                                    <p>
                                       Seller signature :
                                    </p>';
                        
                                if($show_file->signiture){
                                    $other.='<img src="'. base_url().'front_theme/mohr.png" width="150"/>';
                                }
                                
                           $other.='         
                                </div>
                            </div>
                         </div>';
                      ?>
                
                 <div class="letter">
                    
                     <?php
                        if($show_file->remove_total_price){
                            $price_string=NULL;
                        }
                     
                        if($file_rows->num_rows()<=23){
                            echo '<div class="wrapper"><div class="wrapper2">';
                            echo $date;
                            echo $string;
                            echo $price_string;
                            echo $other;
                            echo '</div> </div>';
                        }elseif($file_rows->num_rows()<=40 && $file_rows->num_rows()>23){
                            echo '<div class="wrapper"><div class="wrapper2">';
                            echo $date;
                            echo $string;
                            echo $price_string;
                            echo '</div>  </div>';
                            echo ' <div class="wrapper"><div class="wrapper2">';
                            echo $date;
                            echo "<br><br><br><br>";
                            echo $other;
                            echo '</div> </div>';
                        }else{
                            echo '<div class="wrapper"><div class="wrapper2">';
                            echo $date;
                            echo $string;
                            echo '</div>  </div>';
                            echo ' <div class="wrapper"><div class="wrapper2">';
                            echo $date;
                            echo "<br><br><br><br>";
                            echo $price_string;
                            echo $other;
                            echo '</div> </div>';
                        }
                     ?>
                    
                </div>
                
            </div>
            
     
	</body>
</html>