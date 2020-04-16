<?php
/**
infos.php
version 2014-10-10
*/


/***************************************************************************************
CONFIGURATION
****************************************************************************************/
//ENTER YOUR DATABASE CONNECTION INFO BELOW:
$hostname="localhost";
$database="BASE_DE_DONNÉE";
$username="UTILISATEUR";
$password="MOT_DE_PASSE";

$count = 0;


/***************************************************************************************
Services Port CHECK
****************************************************************************************/
$services = array(
                  "http"=>"80",
		  "mysql"=>"3306",
		  "smtp"=>"25",
		  "imap"=>"143",
		  "dns"=>"53",
		  "ftp"=>"21",
			);
			
foreach($services as $x=>$x_value) {
if (!$socket = @fsockopen("localhost", $x_value, $errno, $errstr, 30))
{
  $count = $count+1;
}
else 
{
fclose($socket);
}

}

/***************************************************************************************
RBL CHECK
****************************************************************************************/
function dnsbllookup($ip,$count){
$dnsbl_lookup=array(
    "dnsbl.dronebl.org",
    "zen.spamhaus.org",
    "b.barracudacentral.org",
    "cbl.abuseat.org",
    "spamsources.fabel.dk"
    ); 
    
if($ip){
$reverse_ip=implode(".",array_reverse(explode(".",$ip)));
foreach($dnsbl_lookup as $host){
if(checkdnsrr($reverse_ip.".".$host.".","A")){
$count = $count+1;
$listed.='<font color="red">RBL</font> >> '.$host.'<br />';
}
}
}
if($listed){
echo $listed;
}
return($count);
}



if(filter_var($_SERVER[SERVER_ADDR],FILTER_VALIDATE_IP)){
$count = dnsbllookup($ip,$count);
}


/***************************************************************************************
MySQL Connection CHECK
****************************************************************************************/

$db = new mysqli($hostname, $username, $password, $database);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
$count = $count+1;
}
$db->close();




if ($count!=0) {echo "<p>erreur</p>";}
else {echo "<p>online</p>";}
?>
