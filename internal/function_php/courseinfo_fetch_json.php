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

  if(isset($_GET['course']))
  {
    $course = $_GET['course'];
    $course = mysql_real_escape_string($course);

    for($i = 0; $i < strlen($course); $i++){ if(is_numeric($course{$i})){ break;}}
    $subject = substr($course, 0, $i);
    $no = substr($course, $i);

    $query = "SELECT * from Course_info WHERE Subject = '".$subject."' AND Course_no = '".$no."'";

    $res = mysql_query($query);
    $row = mysql_fetch_array($res);

    echo json_encode($row);
  }
  else{ return false;}
?>