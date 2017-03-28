<?php 
require_once("../config/configuration.php");
require_once("../classes/database.class.php");
$dclass = new database();
include_once("../classes/general.class.php");
$gnrl =  new general();

function object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}
if(isset($_POST['country_id']) && $_POST['country_id'] != '' )
{ 
			$country_title = $dclass->select("*","tbl_country","AND country_id=".$_POST['country_id']);
			$city_title = $dclass->select("*","tbl_city","AND city_id=".$_POST['city_id']);
			
			$address =   $_POST['area'].','.$country_title[0]['city_name'].','.$country_title[0]['country_name'];
			//$address =   "Nava Vadaj, Ahmedabad,India";
			$zipcodeUrl  = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=true';
	
			  // $zipcodeUrl  = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$_REQUEST['comp_zipcode'].'&sensor=true';
						//echo "<br>Test".$abc = file_get_contents($zipcodeUrl);
			$option =   array('Content-type: application/json'); 
			$ch = 	curl_init($zipcodeUrl);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch,CURLOPT_HTTPHEADER,$option);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$output = curl_exec($ch);       
					curl_close($ch);
					$obj =  json_decode($output);  
					
					$obj1 = object_to_array($obj);
	
				
				 	$latitude = $obj1['results'][0]['geometry']['location']['lat'];
					$longitude = $obj1['results'][0]['geometry']['location']['lng'];
					echo  $returnValue = $latitude.":::".$longitude; 
					/*if($latitude != '' && $longitude != '' ){
					$nid = db_insert('zips') // Table name no longer needs {}
									->fields(array(
									  	"zip_code"=>$_REQUEST['comp_zipcode'],	
										"lat"=>$latitude,							
										"lon"=>$longitude,
										"city"=>$city,								
									))
									->execute();
					
					}else{
						continue;
					}*/
	
}
?>