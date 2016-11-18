<?php 
$DBHOST='localhost';
$DBUSER='9b0684d711aa';
$DBPASS='36ec9448d376176a';
$DBNAME='jobatschool';

$db = new PDO("mysql:host=".$DBHOST.";port='';dbname=".$DBNAME, $DBUSER, $DBPASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$listings_per_page = '10';
$offset=0;
$keywords                 =$_GET['keywords'];
$location                   =$_GET['location'];
$district                      =$_GET['dist'];
$listings_per_page  = '10';
$page                         = $_GET['page'];
if($page>1) {
        $offset = $page * 10;
    }


$stmt=$db->query('SELECT * FROM listings WHERE keywords LIKE "%'.$keywords.'%" AND Location LIKE "%'.$location.'%" AND Location_State="'.$district.'" AND  listing_type_sid="6" LIMIT '.$offset.',10');
if($district=='00') {
	$stmt=$db->query('SELECT * FROM listings WHERE keywords LIKE "%'.$keywords.'%" AND Location LIKE "%'.$location.'%"  listing_type_sid="6" LIMIT '.$offset.',10');
}
$jobs=$stmt->fetchAll();
       if($jobs!=TRUE) {
             $data=array( 'function_result'=>'failed' );
		     $login=json_encode($data);
             echo $login;        
           }
		 else {
			 foreach($jobs as $job)
{
    $jcstmt=$db->query('SELECT country_name FROM countries WHERE sid="'.$job['Location_Country'].'" LIMIT 1');
    $jcountry=$jcstmt->fetchColumn();
    $jsstmt=$db->query('SELECT state_name FROM states WHERE sid="'.$job['Location_State'].'" LIMIT 1');
    $jstate=$jsstmt->fetchColumn();
   $ustmt=$db->query('SELECT * FROM users WHERE sid="'.$job['user_sid'].'" and user_group_sid="41" LIMIT 1');
   $company=$ustmt->fetch(PDO::FETCH_ASSOC);
   $logo=$company['Logo'];
   $lstmt=$db->query('SELECT file_name FROM uploaded_files WHERE id="'.$logo.'" LIMIT 1');
   $logoname=$lstmt->fetchColumn();
   if($logoname==FALSE)
   {
	   $logoname='';
   }
    $cstmt=$db->query('SELECT country_name FROM countries WHERE sid="'.$company['Location_Country'].'" LIMIT 1');
    $country=$cstmt->fetchColumn();
    $sstmt=$db->query('SELECT state_name FROM states WHERE sid="'.$company['Location_State'].'" LIMIT 1');
    $state=$sstmt->fetchColumn();
   //echo $company['email'];
   $lstmt=$db->query('SELECT * FROM locations WHERE city="'.$company['Location_City'].'" LIMIT 1');
   $cords= $lstmt->fetch(PDO::FETCH_ASSOC);
    //echo $cords['latitude']; */
$rowdate=explode(' ',$job['activation_date']);
$dates=$rowdate[0];
  //echo $dates;
        //echo $job['email'];
$company_address=$job['Location_City'].','.$jstate;
        $dat[]=array(
		                                                'Job_Title'=>$job['Title'],
														'Comoany_address'=>$company_address,
		                                               'Company_Name'=>$company['CompanyName'],
													   'id'=>$job['sid'],
													   'posted_date'=>$dates,
													   );
  
}
		$data=$dat;

	
	//print_r($data);
       $login=json_encode($data);
        echo $login;
     //echo "<pre>"; print_r($data); echo "</pre>"; 
        // $da=json_decode($dat);
        //echo $da->function_result->User->FirstName;
	$login=json_decode('[{"Job_Title":"DenInfotech Driver","Comoany_address":"Vaikom,Kottayam","Company_Name":"DenInfotech","id":"47","posted_date":"2016-11-18"},{"Job_Title":"Driver","Comoany_address":"Vaikom,Kottayam","Company_Name":"DenInfotech","id":"48","posted_date":"2016-11-18"}]');
	echo "<br><br><pre>";
		print_r($login);
		echo "</pre>"; 
			 
		 }
