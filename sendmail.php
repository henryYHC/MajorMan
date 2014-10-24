<?php
/*$salt ='emorysolutions';
function encrypt($text)
{
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}
function decrypt($text)
{
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}
$temp = encrypt("yuhsinchen19940823@gmail.com");
echo decrypt($temp);*/

$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "To: Yu-Hsin <yu-hsin.chen@emory.edu>\r\n";
	$headers .= "From: Emory Solutions <noreply@emorysolutions.org>\r\n"; 
	$headers .= "Reply-To: Emory Solutions <emorysolutions@emorysolutions.org>\r\n"; 
  	$headers .= "Return-Path: Emory Solutions <emorysolutions@emorysolutions.org>\r\n"; 
	$headers .= "Organization: Emory Solutions\r\n";
  	//$headers .= "X-Priority: 3\r\n";
  	//$headers .= "X-Mailer: PHP". phpversion() ."\r\n";
$to = "yu-hsin.chen@emory.edu";
$subject = "Welcome to Emory Solutions";
$content = 
"
Dear Yu-Hsin,<br>
<p style=\"text-indent: 2.5em;\">
Thank you for your registration at Major man. Please click on the link below to activate your account:<br>
http://emorysolutions.org<br>
</p>
Emory Solutions<br>
(Please do not reply to this email directly. If you have any question, please email emorysolutions@emorysolutions.org)
";



$check = mail($to, $subject, $content, $headers);
if($check){ echo "Sucess";}
else{ echo "Failed";}
?>