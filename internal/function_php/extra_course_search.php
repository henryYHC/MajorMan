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

  if(isset($_GET['ExtraCourseNo']))
  {
    $no = intval($_GET['ExtraCourseNo']);

    $query = "SELECT * from Extra_Course WHERE No = " . $no;

    $res = mysql_query($query);
    $row = mysql_fetch_array($res);

    $row['Subject'] = ($row['Subject'] == "None")? "None" : $row['Subject'];
    $row['Course_no'] = ($row['Course_no'] == "None" || $row['Subject'] == "None")? "" : " ".$row['Course_no'];

    echo "<div class=\"modal fade\" id=\"ExtraCourse".$row['No']."-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog\">
                  <div class=\"modal-content\">
                    <div class=\"modal-header\">   
                      <h4 class=\"modal-title\" id=\"myModalLabel\">Extra Course Information</h4>
                    </div>
                    <div class=\"modal-body\">
                    <strong>Course title:</strong> ".$row['Course_title']."<br>
                    <strong>Equivalent class:</strong> ". $row['Subject'].$row['Course_no']."<br>
                    <strong>GER:</strong> ".$row['GER']."<br>
                    <strong>Credit:</strong> ".$row['Credit']."<br>
                    <strong>Course description:</strong> <br>&emsp;&emsp;".$row['Course_description']."
                    </div>
                    <div class=\"modal-footer\">
                      <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                    </div>
                  </div>
                </div>
              </div>";
  }
  else{ return false;}
?>