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
    $on_planner = $_GET['placement'];
    $course = mysql_real_escape_string($course);

    for($i = 0; $i < strlen($course); $i++){ if(is_numeric($course{$i})){ break;}}
    $subject = substr($course, 0, $i);
    $no = substr($course, $i);

    $query = "SELECT * from Course_info WHERE Subject = '".$subject."' AND Course_no = '".$no."'";

    $res = mysql_query($query);
    $row = mysql_fetch_array($res);
    if($on_planner == 1) {
    	echo "<div class=\"modal fade\" id=\"".$row['Subject'].$row['Course_no']."-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog\">
                  <div class=\"modal-content\">
                    <div class=\"modal-header\">   
                      <h4 class=\"modal-title\" id=\"myModalLabel\">".$row['Subject'].$row['Course_no']." - ".$row['Course_name']."</h4>
                    </div>
                    <div class=\"modal-body\"><h3>Course Information</h3>
                    <strong>Credit:</strong> ".$row['Credit']."<br>
                    <strong>GER:</strong> ".$row['GER']."<br>
                    <strong>PreRequisite:</strong> ".$row['PreReq']."<br>
                    <strong>Crosslisted:</strong> ".$row['Crosslisted']."<br>
                    <strong>Course description:</strong> ".$row['Course_description']."
                    <hr>
                    <h4>Friends Taking This Class With You</h4>
                    	<div style='margin-bottom:5px; color:#007fff; padding:10px;'>Fei Gao, Henry Chen, Jason Tsai</div>
                    <h4>Class Statistics</h4>
                    	<div class='row'>
                    		<div class='col-xs-6'><div class='col-xs-2'><span style='color:#007fff; font-size:25px'>65</span></div><div class='col-xs-10'>students have this class on their planner</div></div>
                    		<div class='col-xs-6'><div class='col-xs-2'><span style='color:#007fff; font-size:25px'>20</span></div><div class='col-xs-10'>students have this class on their planner in the same semester</div></div>
                    </div>
                    <div class=\"modal-footer\">
                      <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                    </div>
                  </div>
                </div>
              </div>";
    }
    else if($on_planner == 0) {
    	echo "<div class=\"modal fade\" id=\"".$row['Subject'].$row['Course_no']."-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog\">
                  <div class=\"modal-content\">
                    <div class=\"modal-header\">   
                      <h4 class=\"modal-title\" id=\"myModalLabel\">".$row['Subject'].$row['Course_no']." - ".$row['Course_name']."</h4>
                    </div>
                    <div class=\"modal-body\"><h3>Course Information</h3>
                    <strong>Credit:</strong> ".$row['Credit']."<br>
                    <strong>GER:</strong> ".$row['GER']."<br>
                    <strong>PreRequisite:</strong> ".$row['PreReq']."<br>
                    <strong>Crosslisted:</strong> ".$row['Crosslisted']."<br>
                    <strong>Course description:</strong> ".$row['Course_description']."
                    </div>
                    <div class=\"modal-footer\">
                      <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                    </div>
                  </div>
                </div>
              </div>";
    }
    else if($on_planner == 2) {
      echo "<div class=\"modal fade\" id=\"".$row['Subject'].$row['Course_no']."-section-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog\">
                  <div class=\"modal-content\">
                    <div class=\"modal-header\">   
                      <h4 class=\"modal-title\" id=\"myModalLabel\">".$row['Subject'].$row['Course_no']." - ".$row['Course_name']."</h4>
                    </div>
                    <div class=\"modal-body\"><h3>Course Information</h3>
                    <strong>Credit:</strong> ".$row['Credit']."<br>
                    <strong>GER:</strong> ".$row['GER']."<br>
                    <strong>PreRequisite:</strong> ".$row['PreReq']."<br>
                    <strong>Crosslisted:</strong> ".$row['Crosslisted']."<br>
                    <strong>Course description:</strong> ".$row['Course_description']."
                    <hr>
                    <h4>Friends Taking This Class With You</h4>
                      <div style='margin-bottom:5px; color:#007fff; padding:10px;'>Fei Gao, Henry Chen, Jason Tsai</div>
                    <h4>Class Statistics</h4>
                      <div class='row'>
                        <div class='col-xs-6'><div class='col-xs-2'><span style='color:#007fff; font-size:25px'>65</span></div><div class='col-xs-10'>students have this class on their planner</div></div>
                        <div class='col-xs-6'><div class='col-xs-2'><span style='color:#007fff; font-size:25px'>20</span></div><div class='col-xs-10'>students have this class on their planner in the same semester</div></div>
                    </div>
                    <div class=\"modal-footer\">
                      <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                    </div>
                  </div>
                </div>
              </div>";
    }
  }
  else{ return false;}
?>