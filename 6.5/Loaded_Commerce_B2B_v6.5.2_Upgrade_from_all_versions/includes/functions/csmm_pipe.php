#!/usr/bin/php
<?php
/*

  Setup a piping in your hosting control panel like the following (for cPanel)
  (for Plesk it ouwl be something like /var/www/vhosts/domainname.com/httpdocs):

  If your catalog is in the root do like this
  
    |/home/user/public_html/includes/functions/csmm_pipe.php

  If your catalog is in a folder or sub-domain do like this

    |/home/user/public_html/catalog/includes/functions/csmm_pipe.php

*/

  include('includes/configure.php');

  // read from stdin
  $fd = fopen("php://stdin", "r");
  $email = "";
  while (!feof($fd)) {
    $email .= fread($fd, 1024);
  }
  fclose($fd);
  
  // handle email
  $lines = explode("n", $email);
  
  // empty vars
  $from = "";
  $subject = "";
  $headers = "";
  $message = "";
  $splittingheaders = true;
  
  for ($i=0; $i<count($lines); $i++) {
    if ($splittingheaders) {
      // this is a header
      $headers .= $lines[$i]."n";
      // look out for special headers
      if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
        $subject = $matches[1];
      }
      if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
        $from = $matches[1];
      }
    } else {
      // not a header, but message
      $message .= $lines[$i]."n";
    }  
    if (trim($lines[$i])=="") {
      // empty line, header section has ended 
      $splittingheaders = false;
    }
  }
  
  // Info to Send to database
  $username = DB_SERVER_USERNAME;
  $password = DB_SERVER_PASSWORD;
  $database = DB_DATABASE;
  $server   = DB_SERVER;
  $time = date("jS F Y");
  //$query = "INSERT INTO shoutbox(id,name,message,time, time_time)VALUES('NULL','ebrescue','<br>$subject<br><$from><br>$message','$time', ".time().")";

  // Do the Database stuff  
  //mysql_connect($server,$username,$password);
  //@mysql_select_db($database) or die( "Unable to select database");
  //mysql_query($query);
  //mysql_close();
  
  //For Testing Purposes
  mail("gerald@contributioncentral.com", $subject, $message);

?>