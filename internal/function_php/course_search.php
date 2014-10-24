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
  $more = $_GET['more'];
  if(isset($_GET['kw']) && $_GET['kw'] != '')
  {
    $kws = $_GET['kw'];
    $kws = mysql_real_escape_string($kws);
    $query = "SELECT * from Course_info WHERE (Subject like '".$kws."%' OR Course_no like '%".$kws."%' OR CONCAT(Subject, ' ', Course_no) LIKE '".$kws."%' OR CONCAT(Subject, '', Course_no) LIKE '".$kws."%')";
    $res = mysql_query($query);
    $count = mysql_num_rows($res);
    $i = 0;

    if($count > 0)
    {
      while($row = mysql_fetch_array($res))
      {
        $coursename = $row['Course_name']; 
        if(strlen($coursename) > 25) {
          $coursename = substr($coursename, 0, 25);
          $coursename = $coursename."...";
        }
        echo "<div class=\"sidebar-entry\" id=\"".$row['Subject'].$row['Course_no']."\" data-toggle=\"modal\" data-target=\"#".$row['Subject'].$row['Course_no']."-modal\" onClick=\"courseinfo_fetch('".$row['Subject'].$row['Course_no']."', 0)\">".$row['Subject']." ".$row['Course_no']."<div class='pull-right'>".$coursename."</div></div>";

        $i++;
        if($i > 4 && $more == 0){
         echo "<div align=\"center\"><a href=\"#\" onclick=\"course_search_more()\">See more</a></div>";
         break;
        }
        else if($i == $count) break;
      }
    }
    else
    {
      echo "<div id='no_result'>No result found !</div>";
    }
  }
?>