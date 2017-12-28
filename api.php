<?php
if(isset($_REQUEST['command']) && isset($_REQUEST['param'])){
  /* Check if Termux:API is available */
  if($_REQUEST['command']=="check"){
    $test = shell_exec('termux-battery-status -h');
    if (substr($test, 0, 5) === 'Usage'){
      die("Termux:API is available");
    }
    else{
      die("Termux:API is not available");
    }
  }

  $command=$_REQUEST['command'];
  $param=$_REQUEST['param'];
  if(!$param==""){
    $reply=shell_exec("$command $param");
  }
  else{
    $reply=shell_exec("$command");
  }

  echo $reply;
  exit();
}
?>
