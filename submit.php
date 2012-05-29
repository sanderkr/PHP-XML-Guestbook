<?php
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];
  	
  $name = htmlspecialchars($name);
	$email = htmlspecialchars($email);
	$message = htmlspecialchars($message);
  
  if ($name == "" or $message == "") { die ("Name and message can't be empty. <a href=\"javascript: history.go(-1)\">Go back to where you came from!</a>"); }
  
	$xmlsrc = "comments.xml";
	
	$xml = simplexml_load_file($xmlsrc) or die ("Couldn't find XML file <strong>$xmlsrc</strong>");
	$time = time();
  
	$newComment = $xml->addChild('comment');	
	$newComment->addAttribute('time',$time);
  $newComment->addChild('email',$email);
	$newComment->addChild('name',$name);
	$newComment->addChild('message',$message);

	file_put_contents($xmlsrc, $xml->asXML());
  header("Location: index.php"); // Go back
?>