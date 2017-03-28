<?php 
function ProductSearch($data){
global $dclass;			
			//$query = "SELECT t1.*,COUNT(*) as t2.rev_cnt FROM tbl_product t1 LEFT JOIN tbl_review ON t1.product_id = t2.product_id WHERE 1";
    	$query = "SELECT t1.*,COUNT(t2.review_id) as rev_id
FROM tbl_product t1
left JOIN tbl_review t2
ON t1.product_id = t2.product_id
WHERE 1 AND status='1' ";
if(isset($_COOKIE['city']) && $_COOKIE['city'] != ''){
$query .= " AND t1.city_id=".$_COOKIE['city']." "; 
}
			
			if(isset($data['cat_id']) && $data['cat_id'] != ''){
				$chkChild = $dclass->select("GROUP_CONCAT(category_id) as cat_id","tbl_category"," AND category_parent_id=".$data['cat_id']." ");
				
				if(count($chkChild) > 0 && $chkChild[0]['cat_id'] != ''){
					 $search_Cat_id  = $data['cat_id'].','.$chkChild[0]['cat_id'];
					$condition_query_string .= ' AND t1.category_id IN ('.$search_Cat_id.')';
				}else{
					$condition_query_string .= ' AND t1.category_id = "'.$data['cat_id'].'"';
				}
			}
			
			if(isset($data['cuisine']) && $data['cuisine'] != ''){
				$chkCuisine = $dclass->select("GROUP_CONCAT(product_id) as pro_id","tbl_product_field"," AND product_option_vname='Cuisine' AND product_option_lvalue like '%".$data['cuisine']."%' ");
				
				if(count($chkCuisine) > 0 && $chkCuisine[0]['pro_id'] != ''){
					
					$condition_query_string .= ' AND t1.product_id IN ('.$chkCuisine[0]['pro_id'].')';
				}
			}
			
			if(isset($data['search_text']) && $data['search_text'] != ''){
				$condition_query_string .= ' AND ( t1.product_name like "%'.$data['search_text'].'%"  OR  t1.product_address_1 like "%'.$data['search_text'].'%" OR  t1.product_address_2 like "%'.$data['search_text'].'%" OR t1.product_desc like "%'.$data['search_text'].'%"  )';
			}
			
			if(isset($data['location_id']) && $data['location_id'] != ''){
				$condition_query_string .= ' AND t1.location_id = "'.$data['location_id'].'"';
			}
			
			$condition_query_string .= " GROUP BY t1.product_id";
			
			if(isset($data['sort_type']) && $data['sort_type'] != ''){
				if($data['sort_type'] == 'product_rating'){
					$condition_query_string .= ' ORDER BY  t1.product_rating desc';
				}else if($data['sort_type'] == 'no_of_review'){
					$condition_query_string .= ' ORDER BY  rev_id desc';
				}else if($data['sort_type'] == 'most_popular'){
					$condition_query_string .= ' ORDER BY  t1.product_id asc';
				}else{
					$condition_query_string .=" ORDER BY  t1.product_id desc"; 					
				}
			}else{
					$condition_query_string .=" ORDER BY  t1.product_id desc"; 					
			}
		
			
            $sql = $query.$condition_query_string; 
           return $sql;
}

function frontEndPagination($reccnt,$pagesize,$start,$link="") {
	if($reccnt>$pagesize) {
		$num_pages=$reccnt/$pagesize;
		$PHP_SELF=$_SERVER['PHP_SELF'];
		$qry_str=$_SERVER['argv'][0];
		$m=$_GET;
		unset($m['start']);
		$qry_str=qry_str($m);
		$j=$start/$pagesize-5;
		if($j<=0) {
			$j=0;
		}
		$k=$j+10;
		if($k>$num_pages)	{
			$k=$num_pages;
		}
		$j=intval($j);
		?>
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
        <td style="width:200px;"><p class="fs11 b">	
            
            	<?php echo totalPerPage($reccnt,$pagesize,$start);?></p></td>
		
            
    
		<td align="right" valign="middle">
    
        <div class="pagination">
            <ul>
        
		
		  <?php
		  $startfrom=$start+1;
		  $end=$start+$pagesize;
			if($reccnt<$end) {
				$end=$reccnt;
			}
			#echo "Showing	$startfrom - $end of Total $reccnt Records";
			?>
             <?php
		 if($start!=0) {
			 $s=$start-$pagesize;
			 $newUrl=$PHP_SELF."".$qry_str."&start=".$s."".$link;
			 $newUrl=str_replace("?&","?",$newUrl);
			 ?>
             
             
			 <li><a href="<?=$newUrl?>" >Prev</a></li>
			 <?php
		 }
		 ?>
            <?php
			
			for($i=$j;$i<$k;$i++) {
				if(($pagesize*$i)!=$start) {
					$s=$pagesize*$i;
					$newUrl=$PHP_SELF."".$qry_str."&start=".$s."".$link;
					$newUrl=str_replace("?&","?",$newUrl);
					?>
					<li><a  href="<?=$newUrl?>" ><?=$i+1?></a></li>
					<?php
				}
				else {
					?>
					<li><a href="javascript:void();"  class="select"><?=$i+1?></a></li>
					<?php
				}
			}
			?>
            
            
            
                 <?
		if($start+$pagesize < $reccnt) {
			 $s=$start+$pagesize;
			 $newUrl=$PHP_SELF."".$qry_str."&start=".$s."".$link;
			 $newUrl=str_replace("?&","?",$newUrl);
			 ?>
			
			<li> <a href="<?=$newUrl?>">Next</a></p></li>
			 <?php
		 }
		 ?></div></td>
		
        

					</tr>
				</table>
		<?php
	}
}

function qry_str($arr, $skip = '') {
	$s = "?";
	$i = 0;
	foreach($arr as	$key =>	$value)	{
		if ($key !=	$skip) {
			if (is_array($value)) {
				foreach($value as $value2) {
					if ($i == 0) {
						$s .= $key . '[]=' . $value2;
						$i = 1;
					}
					else {
						$s .= '&' .	$key . '[]=' . $value2;
					}
				}
			}
			else {
				if ($i == 0) {
					$s .= "$key=$value";
					$i = 1;
				}else {
					$s .= "&$key=$value";
				}
			}
		}
	}
	return $s;
}


function totalPerPage($totalRecord,$pagesize,$start) { if($totalRecord) {
$num_pages=ceil($totalRecord/$pagesize);
	     $from_pages=$start+1;
	     $to_pages=$start+$pagesize;
 if ($to_pages > $totalRecord) {
		     $to_pages = $totalRecord;
	     }
?>
<p class="fl">Result  
            <?php if($totalRecord) { ?><span class="color_primary txt_b"><?php echo $from_pages; ?></span>  to <span class="color_primary txt_b"><?php echo $to_pages;?></span> out of  <span class="color_primary txt_b"><?php echo $totalRecord; } ?></span></p>
 <?php }
 }
 ?>