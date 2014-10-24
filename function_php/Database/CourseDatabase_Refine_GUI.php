<!DOCTYPE html>
<html>
  <head>
    <title>Coure Prerequisite Input GUI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
  </head>

  <body>
  <form action="CourseDatabase_Refine_GUI.php" method="POST">
    <h2>Coure Prerequisite Input GUI</h2>
    <table>
      <tr>
        <td>Course:&emsp;eg. MATH111</td><td><input type="text" id="course"/></td>
      </tr>
      <tr>
        <tr>
          <td>Pre-requsite class:<br>eg. MATH111</td>
          <td id="class"><input type="text" id="prereq1"><button id="or" onclick="or()">Or</button></td>
        </tr>
      </tr>
      <tr>
        <td><input type="submit" name="save" value="Submit"></td>
      </tr>
    </table>
  </form>
      <script>
      var count = 2;
      function or()
      {
        $("#or").remove();
        $("#class").append("<br><td><input type=\"text\" id=\"prereq"+count+"\"><button id=\"or\" onclick=\"or()\">Or</button></td>");
        count++;
      }
    </script>
  </body>
</html>


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

  if(isset($_POST['save'])){
    
    header("http://emorysolutions.org/majorman/function_php/CourseDatabase_Refine_GUI.php");  
  }
  
?>