<?php

// Configration
include("config/configuration.php");

$dclass   =  new  database();

$data = $dclass->select('t1.*, t2.*, t3.sub_title, t3.sub_duration_type', 'tbl_user_subscrib_detail t1 LEFT JOIN tbl_user t2 ON t1.user_id = t2.user_id LEFT JOIN tbl_subscription_plan t3 ON t3.sub_id=t1.sub_plan_id', "AND t1.id =".$_GET['pid']);

$file_name = 'Screenshot_2.png';
$type = pathinfo('img/' . $file_name, PATHINFO_EXTENSION);

$d = file_get_contents('img/' . $file_name);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($d);

if(!$gnrl->checkUserLogin()){
    $gnrl->redirectTo("index.php?msg=logfirst");
}
// uncomment after client confirmation
if(!$gnrl->checkpaymentstatus()){
    $gnrl->redirectTo("plan");
}

if($_SESSION['user_type'] == 'employee'){
    $gnrl->redirectTo("view");
}

// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$html = '
<html>
<head>
    <style>
        .info-block{
            width: 50%;
            padding-top: 50px;
        }
        .info-block li span:first-child{
            width: 50%;
            font-weight: 700;
        }
               
        .info-block li span{
            display: inline-block;
        }
        
        .info-block ul{
            list-style-type: none;
            padding: 0;
            margin-bottom: 50px;
        }
        body{
            padding-left: 40px;
            padding-right: 40px;
        }
        .footer-block p{
            margin-top: 0;
            margin-bottom: 0;
        }
        .footer-block h4{
            margin-bottom: 0;
        }
    </style>
    
</head>
    <body>
    <div style="text-align: right">
        <img width="220" src="'.$base64.'">
    </div>
        <h3 style="margin-bottom: 50px">Receipt from Smartsheet Subscription</h3>
        <div style="border-bottom: 2px solid black">
            <div style="display: inline-block; width: 49%; font-weight: 700">Client Account</div>
            <div style="display: inline-block; width: 49%; font-weight: 700">Payment Date</div>
        </div>
        <div style="display: inline-block; width: 49%; margin-top: 5px">'.$data[0]['company_name'].'</div>
        <div style="display: inline-block; width: 49%; margin-top: 5px">'.$data[0]['subscrib_date'].'</div>
        
        <div class="info-block">
            <ul class="first-block">
                <li>
                    <span>Company Name:</span>
                    <span>'.$data[0]['company_name'].'</span>
                </li>
                <li>
                    <span>Address:</span>
                    <span>'.$data[0]['address'].'</span>
                </li>
                <li>
                    <span>City:</span>
                    <span>'.$data[0]['city'].'</span>
                </li>
                <li>
                    <span>State:</span>
                    <span>'.$data[0]['state'].'</span>
                </li>
            </ul>
            <ul class="second-block">
                <li>
                    <span>Transaction ID:</span>
                    <span>'.$data[0]['id'].'</span>
                </li>
                <li>
                    <span>Plan:</span>
                    <span>'.$data[0]['sub_title'].'</span>
                </li>
                <li>
                    <span>Plan Term:</span>
                    <span>'.$data[0]['sub_duration_type'].'</span>
                </li>
                <li>
                    <span>Total Payment:</span>
                    <span>$'.$data[0]['subscrib_price'].'</span>
                </li>
                <li>
                    <span>Status:</span>
                    <span>'.$data[0]['payment_status'].'</span>
                </li>
            </ul>
        </div>
        <div class="footer-block">
            <div style="margin-bottom: 30px">
                <h4>Lower your rate with an Annual plan!</h4>
                <p>Sign up for a year and receive two months for free. Choose the annual plan option after clicking on Account in the upper left corner and selecting
                    Account Admin > Plan & Billing Info > Upgrade/Change Plan.
                </p>
            </div>
            <div style="margin-bottom: 30px">
                <p>Thanks for choosing Smartsheet!</p>
            </div>
            <div style="margin-bottom: 30px">
                <h4>Smartsheet.com, Inc.</h4>
                <p>10500 NE 8th Street, Suite 1300</p>
                <p>Bellevue, WA 98004-4369 USA</p>
                <p>United States Tax ID#: 20-2954357</p>
                <p>(VAT Details: Smartsheet is a U.S. based service provider and is exempt from charging or collecting Value Added Tax.)</p>
            </div>
            <div style="margin-bottom: 30px">
                <p>Questions about this receipt? Email or call:</p>
                <p>finance@smartsheet.com</p>
                <p>+1-425-283-1870</p>
            </div>
        </div>
    </body>
</html>

';

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

$output = $dompdf->stream($data[0]['company_name'].'.pdf');

