<?php
require_once("../../../config/configuration.php");  
require_once('../../../classes/database.class.php');
$dclass = new database();
$page =  $dclass->select(' * ','tbl_setting'," AND v_name='PAGGING'");
$per_page = $page[0]['l_values'];
$page = $_REQUEST['page'];
$cont_type = $_REQUEST['cont'];
$start = ($page-1)*$per_page;
//Pages listing start
if(isset($cont_type) && $cont_type == 'pages'){
	$label = "pages";
	$sqlQueryPage="select * from tbl_page 
					 WHERE 1 ORDER BY page_id desc ";
	$sql = "select * from tbl_page 
				 WHERE 1 ORDER BY page_id desc  limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['page_id'];?>"></td>
        <td width='7%'>#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['page_id'];?>"><?php echo $rows['page_title'];?></a></td>
        
        <?php $parent_page_name = $dclass->select('*','tbl_page','AND page_id='.$rows['page_parent_id']);
		
		if($rows['page_parent_id']==0)
		{
			$paage_dis="--root--";
		}
		else
		{
			$paage_dis = $parent_page_name[0]['page_title'];			
		}
		
		?>
        
        <td width='31%' align="center"><?php echo $paage_dis;?></td>
        
        
        <td width='15%' align="center"><?php echo $rows['page_status'];?></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['page_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['page_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['page_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['page_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['page_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}
}//  Settings Start 
else if ($cont_type == 'banner'){

	

	

	$label = "banner";

	$sqlQueryPage="select * from tbl_banner ORDER BY banner_id desc ";

	$sql = "select * from tbl_banner ORDER BY banner_id desc limit $start,$per_page";

	$rsd = $dclass->query($sql);

?>
<?php

$index=1;	

while ($rows = mysql_fetch_assoc($rsd))

{

	

?>
<table cellpadding="10" cellspacing=0 width="100%" align="center">
  <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    <td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['ads_id'];?>"></td>
    <td width='7%' align="center">#<?php echo $index;?></td>
    <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['banner_id'];?>"><img src="../upload/banner/<?php echo $rows['banner_filename'] ?>" width="110px" height="80px"  /></a></td>
    <td width="16%" align="center"><?php

            if( $rows['banner_status'] == 'inactive')

			{

				

				?>
      <span> <a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['banner_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> </span>
      <?php 	

			}

			else{

				?>
      <span> <a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['banner_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> </span>
      <?php

			}

			?>
      <span> <a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['banner_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> </span> <span> <a href="<?php echo  $label.'.php?script=delete&id='.$rows['banner_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> </span></td>
  </tr>
</table>
<?php

$index++;

	}



	

}
else if($cont_type == 'setting'){
	$label = "setting";
	$sqlQueryPage="select * from tbl_setting ORDER BY id desc ";
	$sql = "select * from tbl_setting ORDER BY id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['id'];?>"></td>
        <td width='7%'>#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['id'];?>"><?php echo $rows['v_name'];?></a></td>
        
        <td width='31%' align="center"><?php echo $rows['l_values'];?></td>
        
       
        
         <td width="16%" align="center">
         	 <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	

}
else if ($cont_type == 'ads'){
	
	
	$label = "ads";
	$sqlQueryPage="select * from tbl_ads ORDER BY ads_id desc ";
	$sql = "select * from tbl_ads ORDER BY ads_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['ads_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['ads_id'];?>"><?php echo $rows['ads_title'];?></a></td>
        <td  width='31%' align="center">
		<?php if($rows['ads_images'] != '' ){ ?>
         
		<img src="../upload/ads/<?php echo $rows['ads_images'] ?>" width="55px" height="55px"  />
		
		<?php }else{ 	
         
		 	echo $rows['ads_content'];
		 } ?>
         </td>
       
        
         <td width="16%" align="center">
         	<?php
            if( $rows['ads_status'] == 'inactive')
			{
				
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['ads_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['ads_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['ads_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['ads_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
//Adslisting start
else if ($cont_type == 'press'){
	$label = "press";
	$sqlQueryPage="select * from tbl_press ORDER BY press_id desc ";
	$sql = "select * from tbl_press ORDER BY press_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['press_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['press_id'];?>"><?php echo $rows['press_title'];?></a></td>
        <td  width='31%' align="center">
		<?php if($rows['press_images'] != '' ){ ?>
         
		<img src="../upload/press/<?php echo $rows['press_images'] ?>" width="55px" height="55px"  />
		
		<?php }else{ 	
         
		 	echo $rows['press_content'];
		 } ?>
         </td>
       
        
         <td width="16%" align="center">
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['press_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['press_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
//Adslisting start
else if ($cont_type == 'personality'){
	$label = "personality";
	$sqlQueryPage="select * from tbl_personality  ORDER BY personality_id desc ";
	$sql = "select * from tbl_personality ORDER BY personality_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['personality_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['personality_id'];?>"><?php echo $rows['personality_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['personality_status'] == 'inactive')
			{
				
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['personality_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['personality_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['personality_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['personality_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'evening'){
	$label = "evening";
	$sqlQueryPage="select * from tbl_evenings  ORDER BY evenings_id desc ";
	$sql = "select * from tbl_evenings ORDER BY evenings_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['evenings_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['evenings_id'];?>"><?php echo $rows['evenings_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['evenings_status'] == 'inactive')
			{
				
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['evenings_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['evenings_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['evenings_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['evenings_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'education_level'){
	$label = "education_level";
	$sqlQueryPage="select * from tbl_educational_level  ORDER BY educational_level_id desc ";
	$sql = "select * from tbl_educational_level ORDER BY educational_level_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['educational_level_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['educational_level_id'];?>"><?php echo $rows['educational_level_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['educational_level_status'] == 'inactive')
			{
				
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['educational_level_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['educational_level_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['educational_level_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['educational_level_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'cuisine'){
	$label = "favourite_cuisine";
	$sqlQueryPage="select * from tbl_favorite_cuisine  ORDER BY favorite_cuisine_id desc ";
	$sql = "select * from tbl_favorite_cuisine ORDER BY favorite_cuisine_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['favorite_cuisine_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['favorite_cuisine_id'];?>"><?php echo $rows['favorite_cuisine_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['favorite_cuisine_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['favorite_cuisine_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['favorite_cuisine_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['favorite_cuisine_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['favorite_cuisine_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'literature'){
	$label = "literature";
	$sqlQueryPage="select * from tbl_favorite_literature  ORDER BY favorite_literature_id desc ";
	$sql = "select * from tbl_favorite_literature ORDER BY favorite_literature_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['favorite_literature_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['favorite_literature_id'];?>"><?php echo $rows['favorite_literature_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['favorite_literature_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['favorite_literature_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['favorite_literature_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['favorite_literature_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['favorite_literature_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}

else if ($cont_type == 'movie'){
	$label = "movie";
	$sqlQueryPage="select * from tbl_favorite_movie  ORDER BY favorite_movie_id desc ";
	$sql = "select * from tbl_favorite_movie ORDER BY favorite_movie_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['favorite_movie_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['favorite_movie_id'];?>"><?php echo $rows['favorite_movie_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['favorite_movie_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['favorite_movie_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['favorite_movie_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['favorite_movie_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['favorite_movie_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}




















else if ($cont_type == 'user_visit'){
	$label = "user_visit";
	$sqlQueryPage="select * from tbl_visit  ORDER BY visit_id desc ";
	$sql = "select * from tbl_visit ORDER BY visit_id  desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['visit_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        
        <?php 
			$visit_who_name = $dclass->select('*','tbl_user','AND user_id='.$rows['visit_who']);
			$visit_whom_name = $dclass->select('*','tbl_user','AND user_id='.$rows['visit_whom']);
		?>
        
        <td width='20%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['visit_id'];?>"><?php echo $visit_who_name[0]['username'];?></a></td>

        <td width='20%' align="center"><?php echo $visit_whom_name[0]['username'];?></td>
        <td width='20%' align="center"><?php echo $rows['visit_datetime'];?></td>


         <td width="16%" align="center">
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['visit_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['visit_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}













else if ($cont_type == 'user_wink'){
	$label = "user_wink";
	$sqlQueryPage="select * from tbl_winks  ORDER BY winks_id desc ";
	$sql = "select * from tbl_winks ORDER BY winks_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['winks_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        
        <?php 
			$wink_who_name = $dclass->select('*','tbl_user','AND user_id='.$rows['winks_sender_id']);
			$wink_whom_name = $dclass->select('*','tbl_user','AND user_id='.$rows['winks_receiver_id']);
		?>
        
        <td width='20%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['winks_id'];?>"><?php echo $wink_who_name[0]['username'];?></a></td>

        <td width='20%' align="center"><?php echo $wink_whom_name[0]['username'];?></td>
        <td width='20%' align="center"><?php echo $rows['winks_datetime'];?></td>


         <td width="16%" align="center">
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['winks_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['winks_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}










else if ($cont_type == 'user_favourite'){
	$label = "user_favourite";
	$sqlQueryPage="select * from tbl_favourite  ORDER BY id desc ";
	$sql = "select * from tbl_favourite ORDER BY id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        
        <?php 
			$favourite_who_name = $dclass->select('*','tbl_user','AND user_id='.$rows['user_id']);
			$favourite_whom_name = $dclass->select('*','tbl_user','AND user_id='.$rows['favourite_user_id']);
		?>
        
        <td width='20%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['id'];?>"><?php echo $favourite_who_name[0]['username'];?></a></td>

        <td width='20%' align="center"><?php echo $favourite_whom_name[0]['username'];?></td>
        <td width='20%' align="center"><?php echo $rows['favourite_datetime'];?></td>


         <td width="16%" align="center">
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}




















else if ($cont_type == 'interest'){
	$label = "interest";
	$sqlQueryPage="select * from tbl_interests  ORDER BY interests_id desc ";
	$sql = "select * from tbl_interests ORDER BY interests_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['interests_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['interests_id'];?>"><?php echo $rows['interests_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['interests_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['interests_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['interests_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['interests_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['interests_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'irr_pleasure'){
	$label = "irr_pleasure";
	$sqlQueryPage="select * from tbl_irresistible_pleasure  ORDER BY irresistible_pleasure_id desc ";
	$sql = "select * from tbl_irresistible_pleasure ORDER BY irresistible_pleasure_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['irresistible_pleasure_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['irresistible_pleasure_id'];?>"><?php echo $rows['irresistible_pleasure_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['irresistible_pleasure_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['irresistible_pleasure_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['irresistible_pleasure_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['irresistible_pleasure_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['irresistible_pleasure_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'musical_preferences'){
	$label = "musical_preferences";
	$sqlQueryPage="select * from tbl_musical_preferences  ORDER BY musical_preferences_id desc ";
	$sql = "select * from tbl_musical_preferences ORDER BY musical_preferences_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['musical_preferences_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['musical_preferences_id'];?>"><?php echo $rows['musical_preferences_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['musical_preferences_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['musical_preferences_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['musical_preferences_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['musical_preferences_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['musical_preferences_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'nationality'){
	$label = "nationality";
	$sqlQueryPage="select * from tbl_nationality  ORDER BY nationality_id desc ";
	$sql = "select * from tbl_nationality ORDER BY nationality_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['nationality_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['nationality_id'];?>"><?php echo $rows['nationality_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['nationality_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['nationality_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['nationality_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['nationality_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['nationality_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'occupation'){
	$label = "occupation";
	$sqlQueryPage="select * from tbl_occupation  ORDER BY occupation_id desc ";
	$sql = "select * from tbl_occupation ORDER BY occupation_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['occupation_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['occupation_id'];?>"><?php echo $rows['occupation_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['occupation_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['occupation_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['occupation_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['occupation_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['occupation_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'religion'){
	$label = "religion";
	$sqlQueryPage="select * from tbl_religion  ORDER BY religion_id desc ";
	$sql = "select * from tbl_religion ORDER BY religion_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['religion_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['religion_id'];?>"><?php echo $rows['religion_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['religion_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['religion_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['religion_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['religion_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['religion_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'romance'){
	$label = "romance_degree";
	$sqlQueryPage="select * from tbl_romance_degree  ORDER BY romance_degree_id desc ";
	$sql = "select * from tbl_romance_degree ORDER BY romance_degree_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['romance_degree_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['romance_degree_id'];?>"><?php echo $rows['romance_degree_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['romance_degree_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['romance_degree_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['romance_degree_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['romance_degree_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['romance_degree_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'language'){
	$label = "language";
	$sqlQueryPage="select * from tbl_spoken_languages  ORDER BY spoken_languages_id desc ";
	$sql = "select * from tbl_spoken_languages ORDER BY spoken_languages_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['spoken_languages_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['spoken_languages_id'];?>"><?php echo $rows['spoken_languages_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['spoken_languages_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['spoken_languages_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['spoken_languages_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['spoken_languages_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['spoken_languages_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'sports'){
	$label = "sports";
	$sqlQueryPage="select * from tbl_sports  ORDER BY sports_id desc ";
	$sql = "select * from tbl_sports ORDER BY sports_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['sports_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['sports_id'];?>"><?php echo $rows['sports_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['sports_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['sports_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['sports_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['sports_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['sports_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'hint'){
	$label = "hint";
	$sqlQueryPage="select * from tbl_hint  ORDER BY id desc ";
    $sql = "select * from tbl_hint ORDER BY id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['id'];?>"><?php echo $rows['hint_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['hint_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'question'){
	$label = "question";
	$sqlQueryPage="select * from tbl_questions  ORDER BY question_id desc ";
    $sql = "select * from tbl_questions ORDER BY question_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['question_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['question_id'];?>"><?php echo $rows['question'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['question_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['question_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['question_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['question_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['question_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'testimonial_question'){
	$label = "testimonial_question";
	$sqlQueryPage="select * from tbl_testimonial_question  ORDER BY question_id desc ";
    $sql = "select * from tbl_testimonial_question ORDER BY question_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['question_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['question_id'];?>"><?php echo $rows['question_title'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['question_status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['question_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['question_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['question_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['question_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}
else if ($cont_type == 'testimonial_stories'){
	$label = "testimonial_stories";
	$sqlQueryPage="select * from tbl_testimonial_stories  ORDER BY id desc ";
    $sql = "select * from tbl_testimonial_stories ORDER BY id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <?php $muser = $dclass->select('username','tbl_user','AND user_id='.$rows['m_user_id']);
		$fuser = $dclass->select('username','tbl_user','AND user_id='.$rows['f_user_id']);?>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['id'];?>"><?php echo $muser[0]['username'];?></a></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['id'];?>"><?php echo $fuser[0]['username'];?></a></td>
         <td width="16%" align="center">
         	<?php
            if( $rows['status'] == 'inactive')
			{
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}




else if ($cont_type == 'user'){
	$label = "user";
	$sqlQueryPage="select * from tbl_user ORDER BY user_id desc";
	$sql = "select * from tbl_user where user_type!='super_admin' ORDER BY user_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['user_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='20%' align="center"><?php echo $rows['username'];?></td>
        <td width='20%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['user_id'];?>"><?php echo $rows['email'];?></a></td>
        <td width='20%' align="center"><?php echo $rows['age'];?></td>
        
       
        
         <td width="16%" align="center">
         	<?php
            if( $rows['user_status'] == 'inactive')
			{
				
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['user_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['user_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['user_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['user_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}








else if ($cont_type == 'story_get'){
	$label = "story_get";
	$sqlQueryPage="select * from tbl_user_story_send ORDER BY user_story_send_id desc";
	$sql = "select * from tbl_user_story_send ORDER BY user_story_send_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
	
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['user_story_send_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <?php $muser = $dclass->select('username','tbl_user','AND user_id='.$rows['user_male_id']);
		$fuser = $dclass->select('username','tbl_user','AND user_id='.$rows['user_female_id']);?>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['user_story_send_id'];?>"><?php echo $muser[0]['username'];?></a></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['user_story_send_id'];?>"><?php echo $fuser[0]['username'];?></a></td>
        
         <td width="16%" align="center">
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['user_story_send_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}















else if ($cont_type == 'image_approve_admin'){
	$label = "image_approve_admin";
	$sqlQueryPage="select * from tbl_user ORDER BY user_id desc";
	$sql = "select * from tbl_user where user_type!='super_admin' ORDER BY user_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='20%' align="center"><?php echo $rows['username'];?></td>
        
        <?php if($rows['user_image']!=""){?>
        
        <td width='31%' align="center"><a  class="fancybox-buttons" data-fancybox-group="button" href="../upload/users/<?php echo $rows['user_image'] ?>"><img src="../upload/users/<?php echo $rows['user_image'] ?>" width="80px" height="80px"  /></a></td>
        <?php } else {?>

         <td width='31%' align="center"><img src="<?php echo SITE_URL; ?>../../../img/no_photo_<?php echo $rows['gender'];?>.jpg" width="80px" height="80px" title="<?php echo $rows['username'] ?>" /></td>
        
        <?php }?>
         <td width="16%" align="center">
         	<?php
            if( $rows['image_approve_admin'] == 'no')
			{
				
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['user_id'];?>" title="Approve"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Approve"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['user_id'];?>" title="Non-approve"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Non-approve"></a> 
            </span>	    
                <?php
			}
			?>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}









else if ($cont_type == 'prive_image_approve_admin'){
	$label = "prive_image_approve_admin";
	$sqlQueryPage="select * from tbl_user_private_images ORDER BY user_private_images_id desc";
	$sql = "select * from tbl_user_private_images ORDER BY user_private_images_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        
        <?php $uname_dis_main =$dclass->select('*','tbl_user','AND user_id='.$rows['user_id']); ?>
        
        <td width='20%' align="center"><?php echo $uname_dis_main[0]['username'];?></td>
        
        
        
        <td width='31%' align="center"><a  class="fancybox-buttons" data-fancybox-group="button" href="../upload/private_images/<?php echo $rows['user_private_images_name'] ?>"><img src="../upload/private_images/<?php echo $rows['user_private_images_name'] ?>" width="80px" height="80px"  /></a></td>
        
         <td width="16%" align="center">
         	<?php
            if( $rows['prive_image_approve_admin'] == 'no')
			{
				
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['user_private_images_id'];?>" title="Approve"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Approve"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['user_private_images_id'];?>" title="Non-approve"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Non-approve"></a> 
            </span>	    
                <?php
			}
			?>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}













else if($cont_type == 'msg'){
	
	$label = "message1";
	$sqlQueryPage="select * from tbl_message ORDER BY msg_id desc ";
	$sql = "select * from tbl_message ORDER BY msg_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);

$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['msg_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <?php  $sender = $dclass->select("username",'tbl_user','AND user_id="'.$rows['sender_id'].'"'); ?>
        <td width='20%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['msg_id'];?>"><?php echo $sender[0]['username'];?></a></td>
        <?php $receiver = $dclass->select("username",'tbl_user','AND user_id="'.$rows['receiver_id'].'"'); ?>
        <td width='20%' align="center"><?php echo $receiver[0]['username'];?></td>
        <!--   Parent  Id  Name  --> 
        <td width='20%' align="center"><?php echo $rows['msg_datetime'];?></td>
         <td width="16%" align="center">
        
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['msg_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['msg_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}


// template start
else if ($cont_type == 'template'){
	
	
	$label = "template";
	$sqlQueryPage="select * from tbl_template ORDER BY template_id desc ";
	$sql = "select * from tbl_template ORDER BY template_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['template_id'];?>"></td>
        <td width='7%'>#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['template_id'];?>"><?php echo $rows['template_name'];?></a></td>
        
		
         <td  width='31%' align="center">
		<?php echo $rows['template_subject']; ?>
		
         </td>
       
        
         <td width="16%" align="center">
         	<?php
            if( $rows['template_status'] == 'inactive')
			{
				
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['template_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>	
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['template_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['template_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['template_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}


//location listing start
else if($cont_type == 'location'){
	
	$label = "location";
	$sqlQueryPage="select * from tbl_location ORDER BY location_id desc ";
	$sql = "select * from tbl_location ORDER BY location_id desc limit $start,$per_page";
	$rsd = $dclass->query($sql);
?>
<?php
$index=1;	
while ($rows = mysql_fetch_assoc($rsd))
{
	
?>
	<table cellpadding="10" cellspacing=0 width="100%" align="center">
    <tr class="nicetablerow" onMouseOver="this.className='nicetablerow_over'" onMouseOut="this.className='nicetablerow'">
    	<td id="checkbox2" width='6%'><input type="checkbox" class="noborder" name="chk[]" id="chk<?php echo $index;?>" value="<?php echo $rows['page_id'];?>"></td>
        <td width='7%' align="center">#<?php echo $index;?></td>
        <td width='31%' align="center"><a href="<?php echo $label.'.php?script=edit&id='.$rows['location_id'];?>"><?php echo $rows['location'];?></a></td>
        <!--   Parent  Id  Name  --> 
        <?php if ($rows['parent_id'] !=   0 ){
				$sqlParentName = $dclass->query("select * from tbl_location WHERE location_id ='".$rows['parent_id']."'");
				
				if(!empty($sqlParentName)){
					$rowParentName =  mysql_fetch_assoc($sqlParentName);	 
					
					$parentName =  $rowParentName['location'];		
				}else{
					$parentName =  '-';
				}
			}else{
				$parentName =  '-';
					
			}
			
			 ?>       
        
        <td width='13%' align="center"><?php echo $parentName;?></td>
        <td width='31%' align="center"><?php echo $rows['location_status'];?></td>
        
       
        
         <td width="16%" align="center">
         	<?php
            if( $rows['location_status'] == 'inactive')
			{
				
				?>
                <span> 
            	<a href="<?php echo $label;?>.php?script=activate&id=<?php echo $rows['location_id'];?>" title="Active"><img src="<?php echo  IMAGEPATH; ?>deactivate.gif" class="tooltip"  alt="Active"></a> 
            </span>
                <?php 	
			}
			else{
				?>
            	<span> 
            	<a href="<?php echo $label;?>.php?script=deactivate&id=<?php echo $rows['location_id'];?>" title="Deactive"><img src="<?php echo  IMAGEPATH; ?>delete.gif" class="tooltip" alt="Deactive"></a> 
            </span>	    
                <?php
			}
			?>
            <span> 
            	<a href="<?php echo $label;?>.php?script=edit&id=<?php echo $rows['location_id'];?>"><img src="<?php echo  IMAGEPATH; ?>edit.gif" class="tooltip" name="Edit" alt=""></a> 
            </span> 
            <span>
            	<a href="<?php echo  $label.'.php?script=delete&id='.$rows['location_id'];?>" class="delfset" id="del1"> <img src="<?php echo  IMAGEPATH; ?>delete1.gif" class="tooltip" name="Delete" alt=""></a> 
            </span>
           </td>
         </tr>
         </table>
<?php
$index++;
	}

	
}

///  Pagination  Start 	

		$row = $dclass->query($sqlQueryPage); 
		//$per_page = $page[0]['l_values'];
		$count = @mysql_num_rows($row);
		$pages = ceil($count/$per_page);
		
//  Pagination Start  
	if($count >  0 ){	
		if($page == '1')
		{
				if($pages > 1)
				$next='visible';
			else
				$next='hidden';
			
				$prev='hidden';
				$prevId =  $page - 1;
				$nextId =  $page + 1;
				
		}
		elseif($page == $pages)
		{
			$next='hidden';
			$prev='visible';
				$prevId =  $page - 1;
				$nextId =  $page + 1;
			
		}
		else{
			$next='visible';
			$prev='visible';
				$prevId =  $page - 1;
				$nextId =  $page + 1;
		}		
		//  Prev Next Dot Box 
		if($pages > 7  ){			
			$nextDotbox  =  'visible';
		}else {
			$nextDotbox  =  'hidden';
		}
		if($_REQUEST['page']  >=  7){
			$prevDotbox = 'visible';
		}else{
			$prevDotbox = 'hidden';
		}
		
	?>
    <style>
	#paging_button ul li{
	border:1px solid #5A94D5;
	color:#5A94D5;
	margin-left:3px;
	width:auto;
	float:left;
	text-align:center;
	list-style:none;
	min-width:18px;
	
	}
	.current{
	background-color:#5A94D5;
	border:1px solid #5A94D5;
	color:#FFFFFF !important;
	margin-left:3px;
	}
	#paging_button ul li:hover{
		background-color:#5A94D5;
		color:#FFFFFF;
		
		}
    </style>
     <div id="paging_button">
		<ul style="cursor:pointer;margin-top:8px;float:right">
		<?php 
			
			//Show page links
			if ($page >= 7) { 
				$start_loop = $page - 3; 
				if ($pages > $page + 3) 
					$end_loop = $page + 3; 
				else if ($page <= $pages && $page > $pages - 6) { 
						$start_loop = $pages - 6; 
						$end_loop = $pages; 
				} else { 
					$end_loop = $pages; 
				} 
			} else { 
				$start_loop = 1; 
			if ($pages > 7) 
				$end_loop = 7; 
			else 
				$end_loop = $pages; 
			} 
			
				//  Prev Next Link
				if($prev != 'hidden')  
				echo '<li id="'.$prevId.'" style="visibility:'.$prev.';margin-left:3px">Prev</li>';
				if($start_loop >=  4)
				echo '<li id="'.$start_loop.'" style="visibility:'.$prevDotbox.';margin-left:3px">...</li>';
				for($i=$start_loop; $i<=$end_loop; $i++){
					if($i == $page){
					echo '<li id="'.$i.'" class="current" style="margin-left:3px">'.$i.'</li>';
					}else{
						echo '<li id="'.$i.'" style="margin-left:3px">'.$i.'</li>';
					}
				}
				if( $pages  >  $end_loop  )
				echo '<li id="'.$end_loop.'" style="visibility:'.$nextDotbox.';margin-left:3px">...</li>';				
				if($next != 'hidden')  
				echo '<li id="'.$nextId.'" style="visibility:'.$next.';margin-left:3px">Next</li>';
				
			?>
		</ul>
	</div>
    <?php
		}//  Pagination  End  

?>

