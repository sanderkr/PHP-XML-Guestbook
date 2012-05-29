<!doctype html>
<head>
  <meta charset="utf-8">
  <title>PHP/XML Guestbook</title>
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="add/style.css">
  <script type="text/javascript" src="add/validate.min.js"></script> <!-- JS form validation -->
</head>
<body>
<?
  if(!isset($_GET['page'])) {$_GET['page'] = 1;}
  $page = $_GET['page'];   
  $xmlsrc = "comments.xml";
  $xml = simplexml_load_file($xmlsrc);
  $counted = count($xml); 
  function getComments(){
    global $page;
    global $xmlsrc;
    global $xml;
    $i = 0; // comments index   
    foreach ($xml->comment as $comment) {
      ++$i;       
      if( (($page-1)*3) < $i && $i <= ($page*3) ) { // match page number to comments we want to show
        $gravatar_img = 'http://www.gravatar.com/avatar/?gravatar_id='.md5(strtolower($comment->email)).'&amp;default=monsterid&amp;size=75'; // get gravatar img for each email   
        	echo '
          <div class="commentwrap">
                  <img class="gravatar" alt="Gravatar - " src="'.$gravatar_img.'" />
                  <div class="author"><h3>';
                    if($comment->email != "") {echo '<a href="mailto:'.htmlspecialchars($comment->email).'">'.htmlspecialchars($comment->name).'</a>';} // check if author supplied email - if so, show mailto: link
                    else { echo htmlspecialchars($comment->name); }; // else show only name 
                  echo '</h3></div>
                  <div class="comment">'.nl2br(htmlspecialchars($comment->message)).'</div>' // strip HTML - but preserve line breaks 
          .'</div>';
      } else {
          if($i > ($page*3)) { // add next page link (if more comments exist)
             echo "<a class=\"pageplus\" href=\"?page="; echo $page+1; echo "#comments\">Newer comments &raquo;</a>"; break; 
           } // end next page link
      }  
    }  // end foreach 
    if ($page > 1) {echo "<a class=\"pageminus\" href=\"?page="; echo $page-1; echo "#comments\">&laquo; Older comments</a>"; } // add link to previous pages (if not on page 1)
} // end getComments
?>


<div id="header">  
  <h1><a href="index.php">Guestbook</a></h1>
  <ul>
    <li>Using <a href="comments.xml" target="_blank">XML file</a> for storing comments</li>
    <li>No db, just copy/paste on server</li>
    <li>Minimal server requirements</li>
  </ul> 
</div>  
  
   
<div id="wrap">  
  <h2>Add comment</h2>
  <form name="addcomment" action="submit.php" method="post"> 
    <label for="fname">Name:</label> <input type="text" id="fname" name="name" /> 
    <label for="email">Email:</label> <input type="text" id="email" name="email" />
    <label for="fmessage">Message:</label> <textarea rows="4" cols="15" id="fmessage" name="message"></textarea>
    <input id="submit" type="submit" value="Send" />
    <script type="text/javascript">
                var fname = new LiveValidation('fname'); // validate email 
                fname.add( Validate.Presence );
                var fmessage = new LiveValidation('fmessage'); // validate message
                fmessage.add( Validate.Presence );                
  </script> 
  </form>
  
  <hr /><a name="comments"></a>
  
  <h2><?php echo $counted; ?> Comments</h2>
  <?php
    getComments();
  ?>

</div>

<div id="footer">
  Footer
</div>

</body>
</html>