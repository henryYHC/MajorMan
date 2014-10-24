<?php
  session_start();
  $_SESSION['session'] = 1;
  $link = mysql_connect ('localhost', 'emorysolutions', 'henrychenniharparikh', 'majorman') or die (mysql_error());

  if(!@mysql_select_db('majorman', $link))
  {
    echo "<p>This is an error message: System cannot connect to database.</p>";
    echo "<p><strong>" . mysql_error() . "</strong></p>";
    echo "Please email emorysolutions@emorysolutions.org for support.";
  }

  if(($_GET['username'] != '') && ($_GET['password'] != ''))
  {
    $em = $_GET['em'];
  	$username = $_GET['username'];
  	$password = $_GET['password'];

  	$query = "SELECT Registration_no From Login_info Where User_name = '".$username."' AND Password = '".md5($password)."'";
  	$regno = mysql_result(mysql_query($query), 0);

	  if($regno == null){ echo 0;}
    else
  	{ 
  		setcookie('regno', $regno, time()+3600, '/', '.emorysolutions.org');

      if($em == 1)
      { 
        if(!isset($_COOKIE['username'])){ setcookie('username', $username, time()+60*60*24*365, '/', '.emorysolutions.org');}
      }
      else if($em == 0){ setcookie('username', NULL, false, '/', '.emorysolutions.org');}

      date_default_timezone_set('America/New_York');
      $datetime = date('Y-m-d H:i:s');
      $query = "SELECT Login_time FROM Login_info WHERE Registration_no = ". $regno;
      $loginTimes = mysql_result(mysql_query($query), 0);
      $query = "SELECT Tutorial FROM User_info WHERE Registration_no = ". $regno;
      $Tutorial = mysql_result(mysql_query($query), 0);
      $query = "UPDATE Login_info SET Login_time = Login_time + 1, Last_login = '".$datetime."' WHERE Registration_no = ".$regno;
      $update = mysql_query($query);

      if($loginTimes == 0 || $Tutorial == 0) echo -1;
  		else                 echo $regno;
  	}
  }
  else{ echo 0;}
 ?>