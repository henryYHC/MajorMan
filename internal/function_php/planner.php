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

  $type = $_GET['type'];
  $key = $_GET['key'];
  if(strcmp($type, "T") == 0)
  {	
  		$query = mysql_query("SELECT * FROM Course_info WHERE Subject = '".$key."'");
      $count = mysql_num_rows($query);  
      if($count == 0){ echo "<div class=\"sidebar-entry\">No result</div>";}
      
      for($j = 0; $j < $count; $j++)
      {
        $row = mysql_fetch_array($query);
        $coursename = $row['Course_name']; 
        if(strlen($coursename) > 20) {
          $coursename = substr($coursename, 0, 20);
          $coursename = $coursename."...";
        }
        echo "<div class=\"sidebar-entry\" id=\"".$row['Subject'].$row['Course_no']."\" data-toggle=\"modal\" data-target=\"#".$row['Subject'].$row['Course_no']."-modal\" onClick=\"courseinfo_fetch('".$row['Subject'].$row['Course_no']."', 0)\">".$row['Subject']." ".$row['Course_no']."<div class='pull-right'>".$coursename."</div></div>";
        /*echo "<div class=\"modal fade\" id=\"".$row['Subject'].$row['Course_no']."-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog\">
                  <div class=\"modal-content\">
                    <div class=\"modal-header\">   
                      <h4 class=\"modal-title\" id=\"myModalLabel\">".$row['Subject'].$row['Course_no']." - ".$row['Course_name']."</h4>
                    </div>
                    <div class=\"modal-body\"><big>Course Info</big><br><br>
                    <strong>Credit:</strong> ".$row['Credit']."<br>
                    <strong>GER:</strong> ".$row['GER']."<br>
                    <strong>PreRequisite:</strong> ".$row['PreReq']."<br>
                    <strong>Crosslisted:</strong> ".$row['Crosslisted']."<br>
                    <strong>Course description:</strong> <br>&emsp;&emsp;".$row['Course_description']."
                    </div>
                    <div class=\"modal-footer\">
                      <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                    </div>
                  </div>
                </div>
              </div>";*/
      }
  }
  else if(strcmp($type, "G") == 0)
  {
      $query = mysql_query("SELECT * FROM Course_info WHERE GER = '".$key."'");
      $count = mysql_num_rows($query); 
      if($count == 0){ echo "<div class=\"sidebar-entry\">No result</div>";}
      for($j = 0; $j < $count; $j++)
      {
        $row = mysql_fetch_array($query);
        $coursename = $row['Course_name']; 
        if(strlen($coursename) > 20) {
          $coursename = substr($coursename, 0, 20);
          $coursename = $coursename."...";
        }
        echo "<div class=\"sidebar-entry\" id=\"".$row['Subject'].$row['Course_no']."\" data-toggle=\"modal\" data-target=\"#".$row['Subject'].$row['Course_no']."-modal\" onClick=\"courseinfo_fetch('".$row['Subject'].$row['Course_no']."', 0)\">".$row['Subject']." ".$row['Course_no']."<div class='pull-right'>".$coursename."</div></div>";
       /* echo "<div class=\"modal fade\" id=\"".$row['Subject'].$row['Course_no']."-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                <div class=\"modal-dialog\">
                  <div class=\"modal-content\">
                    <div class=\"modal-header\">   
                      <h4 class=\"modal-title\" id=\"myModalLabel\">".$row['Subject'].$row['Course_no']." - ".$row['Course_name']."</h4>
                    </div>
                    <div class=\"modal-body\"><big>Course Info</big><br><br>
                    <strong>Credit:</strong> ".$row['Credit']."<br>
                    <strong>GER:</strong> ".$row['GER']."<br>
                    <strong>PreRequisite:</strong> ".$row['PreReq']."<br>
                    <strong>Crosslisted:</strong> ".$row['Crosslisted']."<br>
                    <strong>Course description:</strong> <br>&emsp;&emsp;".$row['Course_description']."
                    </div>
                    <div class=\"modal-footer\">
                      <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                    </div>
                  </div>
                </div>
              </div>";*/
      }
  }
?>