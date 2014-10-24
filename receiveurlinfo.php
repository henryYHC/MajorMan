<!DOCTYPE html>
<html>
  <head>
    <title>Receive URL Info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>

  <body>
  	<from method=POST action='receiveurlinfo.php'>
  	<strong>Hello!</strong>
  		<?php
  			$id=$_REQUEST['id'];
  			echo $id;
  		?>
  	</from>
  </body>