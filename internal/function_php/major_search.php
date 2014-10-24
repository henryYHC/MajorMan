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
  $major = $_GET['major'];
  if(isset($_GET['kw']) && $_GET['kw'] != '')
  {
    $kws = $_GET['kw'];
    $kws = mysql_real_escape_string($kws);

    if( $major == 1 ) $query = "SELECT * from Majors_minors WHERE Major_name like '".$kws."%' AND Major = 1";
    else             $query = "SELECT * from Majors_minors WHERE Major_name like '".$kws."%' AND Minor = 1";
    
    $res = mysql_query($query);
    $count = mysql_num_rows($res);
    $i = 0;
    $viewlimit = 5;

    if($count > 0)
    {
      while($row = mysql_fetch_array($res))
      {
        if($major == 1) echo "<div class=\"search_results\">".$row['Major_name']."<button class=\"btn-primary btn-xs pull-right\" onclick=\"addMajor('".$row['Major_no']."')\">Add</button></div>";
        else            echo "<div class=\"search_results\">".$row['Major_name']."<button class=\"btn-primary btn-xs pull-right\" onclick=\"addMinor('".$row['Major_no']."')\">Add</button></div>";
        $i++;
        if($i == $count || $i == 5) break;
      }
    }
    else
    {
      echo "No result found!";
    }
  }
?>