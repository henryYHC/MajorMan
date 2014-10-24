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

  $regno = $_COOKIE['regno'];
  $query = "SELECT planner FROM User_info WHERE Registration_no = ".$regno;
  $result = mysql_result(mysql_query($query), 0);

  echo $result;

?>