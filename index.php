<?php

 $_POST['username'] = 'root';
 $_POST['mysql'] = 'localhost';
 $_POST['db'] = 'chequeplz';
 $_POST['password'] = "";
 $_POST['table'] = "prick";



$result=0;
if (isset($_POST["convert"])) { 
   $filetoname = basename($_FILES['xlsxfile']['name']); 
   if($filetoname==""){$alert = "Select a file";}else{
   $ext = substr($filetoname,-4);
   if($ext!="xlsx"){$alert = "Invalid file type. Select an .xlsx file.";}else{
    $throttle=$_POST['throttle'];
    //$throttle=preg_replace("/[^0-9]/","",$throttle);
   $result = @move_uploaded_file($_FILES['xlsxfile']['tmp_name'], $filetoname); // upload it 
    $file=$filetoname;
    $throttle=$_POST["throttle"];
require_once 'xlsx2csv.php';

$newcsvfile  = str_replace(".xlsx",".csv",$file);
$newcsvfile ="csv/$newcsvfile";


$_POST['csv'] = $newcsvfile;

if(isset($_POST['username'])&&isset($_POST['mysql'])&&isset($_POST['db'])&&isset($_POST['username']))
{
$sqlname=$_POST['mysql'];
$username=$_POST['username'];
$table=$_POST['table'];
if(isset($_POST['password']))
{
$password=$_POST['password'];
}
else
{
$password= '';
}
$db=$_POST['db'];
$file=$_POST['csv'];
echo '<h1>HERE</h1>';
$cons= mysqli_connect("$sqlname", "$username","$password","$db") or die(mysql_error());
$result1=mysqli_query($cons,"select count(*) count from $table");
$r1=mysqli_fetch_array($result1);
$count1=(int)$r1['count'];
//If the fields in CSV are not seperated by comma(,)  replace comma(,) in the below query with that  delimiting character 
//If each tuple in CSV are not seperated by new line.  replace \n in the below query  the delimiting character which seperates two tuples in csv
// for more information about the query http://dev.mysql.com/doc/refman/5.1/en/load-data.html
mysqli_query($cons, '
    LOAD DATA LOCAL INFILE "'.$file.'"
        INTO TABLE '.$table.'
        FIELDS TERMINATED by \',\'
        LINES TERMINATED BY \'\n\'
')or die(mysql_error());
$result2=mysqli_query($cons,"select count(*) count from $table");
$r2=mysqli_fetch_array($result2);
$count2=(int)$r2['count'];
$count=$count2-$count1;
if($count>0)
echo "bongani's algorithm Success";
echo "<b> total $count records have been added to the table $table </b> ";
}
else{
echo "Mysql Server address/Host name ,Username , Database name ,Table name , File name are the Mandatory Fields";
}


//header('Location: index.php?download='.$newcsvfile.'');

}; };};


if(isset($_GET['file'])&&file_exists($_GET['file'])) { $downloadfile=$_GET['file'];
header('Content-disposition: attachment; filename='.$downloadfile.'');
header("Content-length: " . filesize($downloadfile)); 
header('Content-type: application/csv');
readfile(''.$downloadfile.'');
die();
} ;  
 $file="";
 $alert="";
 $redir="";
if(isset($_GET['download'])){  $file = $_GET['download'];
$alert = "Your download will start in <span id='timer'>5</span>
 seconds<br/>or click <a href=$file>this link</a> to start download now:";
 $redir= '<meta http-equiv="refresh" content="5;url=index.php?file='.$file.'">'; };
 
echo '<!DOCTYPE HTML>
<html>
  <head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8"> 
  '.$redir.'
  <title>XLSX2CSV Demo</title>
  <style type="text/css">
  body{
  text-align:center;
  }
  #container{
  width:800px;
  margin:0 auto; 
  margin-top:50px;
  text-align:left;
  }  
  p {
  font-weight:800;
  margin-bottom:5px;
  }
  .alert{
  color:rgb(204,0,0);
  }
  .button {
    font-weight:800;
    padding:3px;
    border: 1px solid rgb(0,0,153);
    background: rgb(204,255,255); 
    margin-top:15px;
    cursor:pointer;
}
  .button:hover {
    border: 1px solid rgb(0,0,102);
    background: rgb(153,204,255);
  
}
   #timer{
   display:inline;
   }
   
   #xlsxfile{
   width:150px;
   }
  
  </style> 
  
   <script type="text/javascript">
  function message(){  
  document.getElementById("msg").innerHTML="Processing<blink>...</blink>";
  
  }  ;
 


/**
* Countdown timer from http://forum.codecall.net/topic/51639-how-to-create-a-countdown-timer-in-javascript/
*/      
var Timer;
var TotalSeconds;
function CreateTimer(TimerID, Time) {   
    Timer = document.getElementById(\'TimerID\');
    TotalSeconds = Time;
    UpdateTimer() ;  
    setTimeout("Tick()", 1000);
} ;

function Tick() {  
    TotalSeconds -= 1;
    UpdateTimer() ;
    setTimeout("Tick()", 1000);
} ;

function UpdateTimer() {    
    document.getElementById(\'timer\').innerHTML = TotalSeconds;   
   
}  ;
 function init(){setTimeout("document.getElementById(\'msg\').innerHTML=\'\'",5500);CreateTimer("timer",5);};   
  window.onload=init; 
  

  </script> 
  </head>
  <body >
<div id="container">
<h1>Bongani\'s XLSX2CSV2MySQL Genius Algorithm</h1> 
<h3 class="alert" id="msg">'.$alert.'</h3>  
<p>Select a XLSX file to store to database:</p>

<form action="#" method="post" 
                        enctype="multipart/form-data">
<input type="file" name="xlsxfile" size="40" />
<input type="hidden" name="convert">
<br />
<input type="text" name="throttle" id="throttle" value="0" onkeyup="this.value=this.value.replace(/[^\d]/,\'\')"  size="5"> # of rows to convert (0 = no limit) <br />
<input type="submit" class="button" name = "upload"  onClick="message()"  value="Convert to CSV then import to MySQL using Bongani\'s genius algorithm.. " />
</form>
</div>
</body>
</html>';


?>