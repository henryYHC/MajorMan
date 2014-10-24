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

  $subjectKeys = array(
    "AAS", "AFS", "AMST", "ANCMD", "ANT", "ARAB", "ARTHIST", "ARTVIS", "BIOL", "CBSC", "CHEM", "CHN", "CL", "CPLT", 
    "CS", "DANC", "EAS", "ECON", "ECS", "EDS", "ENG", "ENGCW", "ENVS", "FILM", "FREN", "GER", "GHCS", "GRK", "HEBR",
    "HIST", "HLTH", "HNDI", "IDS", "ITAL", "JPN", "JRNL", "JS", "KRN", "LACS", "LAT", "LING", "MATH", "MESAS", "MUS",
    "NBB", "PACE", "PE", "PERS", "PHIL", "PHYS", "POLS", "PORT", "PSYC", "QTM", "REES", "SOC", "SPAN", "TBT", "THEA", "WGS", "YDD");

  $query = "SELECT No FROM Course_info ORDER BY No DESC LIMIT 1";
  $rowno = mysql_result(mysql_query($query), 0);

  for($i = 1; $i < $rowno+1; $i++)
  {
    $query = "SELECT * FROM Course_info WHERE No = ".$i;
    $row = mysql_fetch_array(mysql_query($query), MYSQL_ASSOC);

    if($row == null || !preg_match('/[0-9]{3,3}/', $row['Course_description']) || !preg_match('/Pre/', $row['Course_description'])) continue;
   /* 
    for($j = 0; $j < count($subjectKeys); $j++)
    {
      $key = "/".$subjectKeys[$j]."/";*/
      //if(preg_match($key, $row['Course_description'])){
          echo $row['Subject']." ".$row['Course_no']."<br>".$row['Course_description']."<br>";
     /* }
    }*/
  }
?>