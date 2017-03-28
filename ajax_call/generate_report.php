<?php 
require_once("../config/dbconfig.php");
require_once('../classes/database.class.php');
$dclass = new database();
include_once("../classes/general.class.php");
$gnrl =  new general();
session_start();

/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
// file name for download
$filename = "website_data_" . date('Ymd') . ".xls";


//header("Content-Disposition: attachment; filename=\"$filename\"");
//header("Content-Type: application/vnd.ms-excel");



if(isset($_POST['tsk']) && $_POST['tsk'] != '' ){ 
	if($_POST['tsk'] == 'generate_report'){

		/*$query = "select t1.*,t2.* from tbl_task t1 LEFT JOIN tbl_task_operator t2 ON t1.t_id = t2.task_id WHERE 1 
		AND t1.t_status = 'open' AND t1.t_company_user_id = '".$_SESSION['company_id']."' ";
		$condition_query_string = '';*/
                    
                $query = "select t1.*,t2.*,pro.pr_title as pro_title,team.tm_title as team_title,concat(user.fname,' ',user.lname) as employee"
                        ." from tbl_task as t1 LEFT JOIN tbl_task_operator as t2 ON t1.t_id = t2.task_id "
                        . "inner join tbl_project as pro on t1.t_pr_id = pro.pr_id "
                        ." inner join tbl_user as user on t1.t_operator_id = user.user_id "
                        ." inner join tbl_team as team on t1.t_team_id = team.tm_id WHERE 1 "
                        ." AND t1.t_company_user_id = '".$_SESSION['company_id']."' ";
		$condition_query_string = '';
		
		// if user select start date and end date
                if(isset($_POST['report_start_date']) && $_POST['report_start_date'] != '' && isset($_POST['report_end_date']) && $_POST['report_end_date'] != '' ){
                    $startDate = date('Y-m-d',strtotime($_POST['report_start_date']));
                    $enddDate = date('Y-m-d',strtotime($_POST['report_end_date']));
                    
                    $condition_query_string .= ' AND DATE(t1.t_start_datetime) >=  "'.$startDate.'" AND  DATE(t1.t_end_datetime) <=  "'.$enddDate.'" ';
		}
                
                // if user select project
		if(isset($_POST['project_id']) && $_POST['project_id'] != '' && $_POST['project_id'] != 'all'){
			$condition_query_string_project = ' AND t1.t_pr_id =  "'.$_POST['project_id'].'" ';
		}
                // if user checked all project 
                $condition_query_string_project = ' ';
		if(isset($_POST['project_id']) && $_POST['project_id'] == 'all' ){                    
                    $ProjectList = $dclass->select("GROUP_CONCAT(t1.pr_id) as pr_ids","tbl_project t1 LEFT JOIN tbl_client t2 ON t1.pr_cl_id = t2.cl_id"," AND t2.cl_comp_user_id = ".$_SESSION['company_id']);
                    if($ProjectList[0]['pr_ids'] != ''){                            
                            //$condition_query_string .= ' OR t1.t_pr_id IN ("'.$ProjectList[0]['pr_ids'].'") ';
                    }
		}
                $condition_query_string .= $condition_query_string_project;
                
                // if user select team 
		if(isset($_POST['team_id']) && $_POST['team_id'] != '' && $_POST['team_id'] != 'all' ){
			$condition_query_string_team = ' AND t1.t_team_id =  "'.$_POST['team_id'].'" ';
		}
                
                $condition_query_string .= $condition_query_string_team;
                
    // if user checked all team 
                $condition_query_string_team = ' ';
		if(isset($_POST['team_id']) && $_POST['team_id'] == 'all' ){
			$condition_query_string_team = ' ';
			$teamList = $dclass->select("GROUP_CONCAT(t1.tm_id) as tm_ids","tbl_team "," AND company_user_id  = ".$_SESSION['company_id']);
			if($teamList[0]['tm_ids'] != ''){
                            
				//$condition_query_string .= ' OR t1.t_team_id IN ("'.$teamList[0]['tm_ids'].'") ';
			}
		}
                $condition_query_string .= $condition_query_string_team;
                
                // if user select employee
		if(isset($_POST['user_id']) && $_POST['user_id'] != '' && $_POST['user_id'] != 'all'){
			//$condition_query_string .= ' AND t2.user_id =  "'.$_POST['user_id'].'" ';
                    $condition_query_string .= ' AND t1.t_operator_id =  "'.$_POST['user_id'].'" ';
		}
                
                // if user checked full database
		/*if(isset($_POST['full_database']) && $_POST['full_database'] == 'yes' ){
			$condition_query_string .= ' ';
			//$condition_query_string .= ' OR t1.t_company_user_id ="'.$_SESSION['company_id'].'" ';
		}*/
		
		$condition_query_string .=' GROUP BY t1.t_id ORDER BY t1.t_id desc';
		
		 $sql = $query.$condition_query_string;
		

		$getData = $dclass->query($sql);
		
		$file_path = '../download/report/';
		$filename = "task_".time().".xlsx";
                   
                
                /*$data = array();
                while($row = mysql_fetch_assoc($getData)){
                    
                    $duration = str_replace(' hours','', $row['t_duration']);
                    $duration = number_format((float)$duration, 2).' hr';
                
                        
                        $data[] = array("Employee" => $row['employee'],
                            "Project" => $row['pro_title'],
                            "Team" => $row['team_title'],
                            "Task Title" => $row['t_title'],
                            "Description" => $row['t_description'],
                            "Status" => $row['t_status'],
                            "Priority" => $row['t_priority'],
                            "Start date" => $row['t_start_datetime'],
                            "End date" => $row['t_end_datetime'],
                            "Duration" => $duration);
                       
                }
                

                function cleanData(&$str)
                {
                  $str = preg_replace("/\t/", "\\t", $str);
                  $str = preg_replace("/\r?\n/", "\\n", $str);
                  if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
                }   

                

                $flag = false;
                $xlsdata  = '';
                foreach($data as $row) {
                  if(!$flag) {
                    // display field/column names as first row
                      
                      $title = array_keys($row);
                      array_walk($title, function(&$item) { $item = ''.$item.''; });
                      $xlsdata .= implode("\t", $title) . "\n";
                    
                    $flag = true;
                  }
                  array_walk($row, 'cleanData');
                  $xlsdata .= implode("\t", array_values($row)) . "\n";
                }

                  
                //echo $xlsdata;
                
                $handle = fopen($file_path.$filename, 'w+');
                fwrite($handle, $xlsdata);
                fclose($handle);
                
                 echo $downloadlinkMember = 'Please <a href="download/report/'.$filename.'" download> Click here </a> For download Report';
		 exit;
		/*$handle = fopen($file_path.$filename, 'w+');
		fputcsv($handle, array('Employee','Project','Team','Task Title','Description','Status','Priority','Start date','End date','Duration'));
		
		while($row = mysql_fetch_assoc($getData)){
                    
                    $duration = str_replace(' hours','', $row['t_duration']);
                    $duration = number_format((float)$duration, 2).' hr';
			
			fputcsv($handle, array($row['employee'],$row['pro_title'],$row['team_title'],$row['t_title'],$row['t_description'],$row['t_status'],$row['t_priority'],$row['t_start_datetime'],$row['t_end_datetime'],$duration));
			$handle = str_replace(",", "\t", $handle);
		}
                
		fclose($handle);*/
                 
         	$rootpath = str_replace('ajax_call','',dirname(__FILE__));       
         	include($rootpath .'PHPExcel/Classes/PHPExcel.php');
        	//include(str_replace('ajax_call','',dirname(__FILE__)) .'PHPExcel/Classes/PHPExcel.php');
        
            $head = array('Employee','Project','Team','Task Title','Description','Status','Priority','Start date','End date','Duration');
            //$row = "15";
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();

            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Capacity administrator")
                                                                     ->setLastModifiedBy("Admin")
                                                                     ->setTitle("Task Reports")
                                                                     ->setSubject("Task Reports")
                                                                     ->setDescription("Generate Task Reports based on filter")
                                                                     ->setKeywords("office 2007 openxml php")
                                                                     ->setCategory("Capacity Task");


            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'Employee')
                        ->setCellValue('B1', 'Project')
                        ->setCellValue('C1', 'Team')
                        ->setCellValue('D1', 'Task Title')
                        ->setCellValue('E1', 'Description')
                        ->setCellValue('F1', 'Status')
                        ->setCellValue('G1', 'Priority')
                        ->setCellValue('H1', 'Start date')
                        ->setCellValue('I1', 'End date')
                        ->setCellValue('J1', 'Duration');
            
            $i = 2;
            while($row = mysql_fetch_assoc($getData))
            {  
               $duration = str_replace(' hours','', $row['t_duration']);
                $duration = number_format((float)$duration, 2).' hr';
                
                 $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $row['employee'])
                        ->setCellValue('B'.$i, $row['pro_title'])
                        ->setCellValue('C'.$i, $row['team_title'])
                        ->setCellValue('D'.$i, $row['t_title'])
                        ->setCellValue('E'.$i, $row['t_description'])
                        ->setCellValue('F'.$i, $row['t_status'])
                        ->setCellValue('G'.$i, $row['t_priority'])
                        ->setCellValue('H'.$i, $row['t_start_datetime'])
                         ->setCellValue('I'.$i, $row['t_end_datetime'])
                        ->setCellValue('J'.$i, $duration);
                 $i++;
                 
                
            }
            
            $objPHPExcel->getActiveSheet()->setTitle('Report');


            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);

$styleArray = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
  );

$objPHPExcel->getActiveSheet()->getStyle(
    'A1:J'. 
    $objPHPExcel->getActiveSheet()->getHighestRow()
)->applyFromArray($styleArray);
            
            
            // Redirect output to a client’s web browser (Excel2007)
           /* header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');*/
           
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            //header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            //header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0*/
            
            echo $downloadlinkMember = 'Please <a href="download/report/'.$filename.'" id="dwnreport" download> Click here </a> For Download Report';
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($file_path.$filename);
            
		
	}
        else if($_POST['tsk'] == 'get_employee'){

         $getOperator = $dclass->select("GROUP_CONCAT(user_id) as uid","tbl_team_detail"," AND tm_id = ".$_POST['team_id']);
         $operator = $dclass->select("user_id,fname,lname","tbl_user"," AND user_id IN (".$getOperator[0]['uid'].") ");
         
          $html = '<select id="user_id" name="user_id" class="selectpicker validate[required]" data-live-search="true" title="Choose Employee"><option>Choose Employee</option>';
                     foreach($operator as $ui){
                        $html .='<option value="'.$ui['user_id'].'">'.$ui['fname'].' '.$ui['lname'].'</option>'; 
                     }
                        
                    $html .='</select>';
            echo $html;       
 
  
  
  
  }
}
?>

