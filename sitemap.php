<?php
//  Configuration  
include('config/configuration.php');
$load  =  new loader();	
$objages = $load->includeclasses('pages');	
$label = "register";
$dclass   =  new  database();
$gnrl =  new general;
$load->includeother('header');

?>
<!-------------------------------header-end----------------------------------->
<div class="pageheading"> 
<div class="container">
<div class="row">
<div class="span12">Site Map</div>
</div>
</div>
</div>
<!-----------------------------////content////---------------------------------->
<div class="container">
<div class="content">
<div class="site-map">
<div class="row">
<div class="span3">
<?php $category = $dclass->select("*","tbl_category"," AND category_publish='1' AND category_parent_id ='0' ");  ?>

<?php 

$ind = 0;
foreach($category as $cat){
if($ind % 4 == 0 && $ind != '0'){	echo '</div><div class="span3">';
}
	?>
<div class="heading"><h3><?php echo $cat['category_name']; ?></h3></div>
<?php $chkSub = $dclass->select("*","tbl_category"," AND category_publish='1' AND category_parent_id=".$cat['category_id']);  
if(count($chkSub) > 0){
?>

<ul class="list-style1">
<?php 
$jr = 1;
foreach($chkSub as $csub){ 
if($jr % 5 == 0 && $jr != '0'){	echo '</ul></div><div class="span3"><ul>';
}

?>
<li>
	<?php $chkSubSub = $dclass->select("*","tbl_category"," AND category_publish='1' AND category_parent_id=".$csub['category_id']);  
		if(count($chkSubSub) > 0){ ?>
		<div class="heading"><h3><?php echo $csub['category_name']; ?></h3></div>
        <ul class="list-style1">
        	<?php 
			$sr  = 0;
			foreach($chkSubSub as $cksubsub){
				if($sr % 5 == 0 && $sr != '0'){	echo '</ul></li></ul></div><div class="span3"><ul><li><ul>';
				}
				?>
        	<li><a href="<?php echo SITE_URL; ?>category.php?cat_id=<?php echo $cksubsub['category_id']; ?>"><?php echo $cksubsub['category_name']; ?></a></li>
            <?php 
			$jr++;
			} ?>
        </ul>
		<?php }else{
			?>
		<a href="<?php echo SITE_URL; ?>category.php?cat_id=<?php echo $csub['category_id']; ?>"><?php echo $csub['category_name']; ?></a>
<?php } ?> </li>
<?php 
} ?>

</ul>
<?php } ?>
<?php 
$ind++;
} ?>

<?php /*?><div class="heading"><h3>Bars & Nightclubs</h3></div>
<ul class="list-style1">
<li><a href="#">Bars</a></li>
<li><a href="#">Nightclubs</a></li>
</ul>


<div class="heading"><h3>Shopping</h3></div>
<ul class="list-style1">
<li><a href="#">Shopping Center</a></li>
<li><a href="#">Convenient Store</a></li>
<li><a href="#">Florist</a></li>
<li><a href="#">Hardware Shop</a></li>
</ul>


<div class="heading"><h3>Local Services</h3></div>
<ul class="list-style1">
<li><a href="#">Postal</a></li>
<li><a href="#">Courier</a></li>
<li><a href="#">Laundry</a></li>
<li><a href="#">Mover & Logistic</a></li>
<li><a href="#">Utility Provider</a></li>
<li><a href="#">Astrologer</a></li>
<li><a href="#">Tailor</a></li>
<li><a href="#">Money Lenders</a></li>
<li><a href="#">Funeral Services</a></li>
</ul>
</div>

<div class="span3">
<div class="heading"><h3>Medical Services</h3></div>
<ul class="list-style1">
<li><a href="#">Clinic</a></li>
<li><a href="#">Dentist</a></li>
<li><a href="#">Hospital</a></li>
<li><a href="#">Traditional Medicine Practioner</a></li>
<li><a href="#">Pharmacy</a></li>
</ul>


<div class="heading"><h3>Home Services</h3></div>
<ul class="list-style1">
<li><a href="#">Plumber</a></li>
<li><a href="#">Electrician</a></li>
<li><a href="#">Pest Control</a></li>
<li><a href="#">Cable TV</a></li>
<li><a href="#">Maid Agency</a></li>
<li><a href="#">Gardener</a></li>
</ul>


<div class="heading"><h3>Hotels & Travels</h3></div>
<ul class="list-style1">
<li><a href="#">Hotels</a></li>
<li><a href="#">Travels</a></li>
</ul>


<div class="heading"><h3>Arts & Entertainment</h3></div>
<ul class="list-style1">
<li><a href="#">Arts</a></li>
<li><a href="#">Entertainment</a></li>
</ul>
</div>


<div class="span3">
<div class="heading"><h3>Sports & Leisure</h3></div>
<ul class="list-style1">
<li><a href="#">Gym</a></li>
<li><a href="#">Recreation Club</a></li>
<li><a href="#">Golf Club</a></li>
<li><a href="#">Badminton Court</a></li>
</ul>


<div class="heading"><h3>Public Services & Government</h3></div>
<ul class="list-style1">
<li><a href="#">Public Services</a></li>
<li><a href="#">Government</a></li>
</ul>


<div class="heading"><h3>Education</h3></div>
<ul class="list-style1">
<li><a href="#">Nursery</a></li>
<li><a href="#">Kindergarten</a></li>
<li><a href="#">Primary</a></li>
<li><a href="#">Secondary</a></li>
<li><a href="#">College</a></li>
<li><a href="#">University</a></li>
</ul>


<div class="heading"><h3>Pets</h3></div>
<ul class="list-style1">
<li><a href="#">Pets</a></li>
</ul>


<div class="heading"><h3>Professional Services</h3></div>
<ul class="list-style1">
<li><a href="#">Accountants</a></li>
<li><a href="#">Lawyers</a></li>
<li><a href="#">Auditor</a></li>
<li><a href="#">Tax Agent</a></li>
<li><a href="#">Company Secretary</a></li>
<li><a href="#">Architect</a></li>
</ul>
</div>


<div class="span3">
<div class="heading"><h3>Religious Services</h3></div>
<ul class="list-style1">
<li><a href="#">Mosque</a></li>
<li><a href="#">Temple</a></li>
<li><a href="#">Church</a></li>
</ul>


<div class="heading"><h3>Financial Services</h3></div>
<ul class="list-style1">
<li><a href="#">Bank</a></li>
<li><a href="#">Insurance</a></li>
</ul>


<div class="heading"><h3>Property</h3></div>
<ul class="list-style1">
<li><a href="#">Real Estate</a></li>
</ul>


<div class="heading"><h3>Event Planning</h3></div>
<ul class="list-style1">
<li><a href="#">Wedding Planner</a></li>
<li><a href="#">Event Organizer</a></li>
</ul>

<div class="heading"><h3>Mass Media</h3></div>
<ul class="list-style1">
<li><a href="#">Mass Media</a></li>
</ul>
</div><?php */?>
</div>
</div>
</div>
</div>
</div>
<!-----------------------------////content-end////---------------------------------->
<?php $load->includeother('footer'); ?>