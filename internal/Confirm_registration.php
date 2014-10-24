<!DOCTYPE html>
<html>
  <head>
    <title>Receive URL Info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>

  <body>
  	<from method=GET action='receiveurlinfo.php'>
  	ID:
  		<?php
        $salt ='emorysolutions';
        function decrypt($text)
        {
          return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        }

        
  			$username=$_GET['id'];
  			
        echo decrypt($username);
  		?>
  	</from>
  </body>