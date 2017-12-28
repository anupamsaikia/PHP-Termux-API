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
    $response=shell_exec("$command $param");
  }
  else{
    $response=shell_exec("$command");
  }

  echo $response;
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
* {
  box-sizing: border-box;
}
.btn{
  background-color:#f5f5f5;
  display: inline-block;
  color: #555;
  text-align: center;
  border: solid 1px #bbb;
  border-radius:3px;
}
.btn:hover{
  cursor: pointer;
  background-color: #ddd;
}
pre{
  padding:8px 12px;
  overflow-x:scroll;
  border: 1px solid #e8e8e8;
  border-radius:3px;
  background-color:#fdfdfd;
  color:#777;
  font-size:14px
}
code{
  border: 0;
  padding-right: 0;
  padding-left: 0;
}
input{
  font-family:MonoSpace;
  color:#555;
  border:solid 1px #bbb;
  border-radius:4px;
  padding:4px;
  outline:none
}
.commands {
  max-width:992px;
  margin:auto;
  padding-left:10px;
  padding-right:10px;
}
.commands a {
  background-color:#f5f5f5;
  display: inline-block;
  color: #555;
  text-align: center;
  border: solid 1px #bbb;
  border-radius:3px;
  font-size:14px;
  color: #555;
  padding: 2px;
  margin:2px;
  text-decoration: none;
}
.commands a:hover{
  cursor: pointer;
  background-color: #ddd;
}
.execute{
  padding:4px;
  margin-top:10px;
}
.main {
  margin:auto;
  margin-top:5px;
  max-width:992px;
  padding:5px 20px;
  background:#f5f5f5;
  min-height:350px;
}
#photo-link{
  padding:4px;
  margin-top:5px;
  margin-bottom:50px;
  text-decoration:none;
}

@media only screen and (max-width:992px) {
  .commands, .main{
    width:100%;
  }
}
</style>
</head>
<body style="color:#555;font-family:Consolas, MonoSpace;margin:0px;">
  <h2 style="text-align:center">Termux API Control Panel</h2>
  <div class="commands">
    Commands :
    <a onclick="cmd(0)" href=#>Check</a><a onclick="cmd(1)" href=#>Battery</a><a onclick="cmd(2)" href=#>Camera Info</a><a onclick="cmd(3)" href=#>Camera Click</a><a onclick="cmd(4)" href=#>Clipboard Get</a><a onclick="cmd(5)" href=#>Clipboard Set</a><a onclick="cmd(6)" href=#>Contacts</a><a onclick="cmd(7)" href=#>Dialog</a><a onclick="cmd(8)" href=#>Download</a><a onclick="cmd(9)" href=#>IR Info</a><a onclick="cmd(10)" href=#>IR Send</a><a onclick="cmd(11)" href=#>Location</a><a onclick="cmd(12)" href=#>Notification</a><a onclick="cmd(13)" href=#>Share</a><a onclick="cmd(14)" href=#>SMS Read</a><a onclick="cmd(15)" href=#>SMS Send</a><a onclick="cmd(16)" href=#>Cell Info</a><a onclick="cmd(17)" href=#>Device Info</a><a onclick="cmd(18)" href=#>Toast</a><a onclick="cmd(19)" href=#>TTS Engines</a><a onclick="cmd(20)" href=#>TTS Speak</a><a onclick="cmd(21)" href=#>Vibrate</a>
  </div>
  <div class="main">
    <div id="command" style="color:#666;font-weight:bold;font-size:20px"></div>
    <pre style="white-space:pre-wrap;"><code id= "documentation"></code></pre>
    <div>
      <div id="param" style="margin-top:10px">
        <span style="">Enter parameters : </span>
        <input id="paramInput">
      </div>
      <div onclick="execute()" class="execute btn">Execute</div>
    </div>
    <div style="border-top:solid 1px #ccc;width:100%;height:1px;margin:auto;margin-top:15px;margin-bottom:5px;"></div>
    <pre><code id= "response"></code></pre>
  </div>

  <script>
  var api = {
    0:{command:"check",doc:"This command will check if the Termux:API is available on the device.",sampleParam:""},
    1:{command:"termux-battery-status",doc:"Usage: termux-battery-status\nGet the status of the device battery.",sampleParam:""},
    2:{command:"termux-camera-info",doc:"Usage: termux-camera-info\nGet information about device camera(s).",sampleParam:""},
    3:{command:"termux-camera-photo",doc:"Usage: termux-camera-photo [-c camera-id] output-file\nTake a photo and save it to a file in JPEG format.\n\n  -c camera-id  ID of the camera to use (see termux-camera-info), default: 0",sampleParam:"-c 0 photo.jpg"},
    4:{command:"termux-clipboard-get",doc:"Usage: termux-clipboard-get\nGet the system clipboard text.",sampleParam:""},
    5:{command:"termux-clipboard-set",doc:"Usage: termux-clipboard-set [text]\nSet the system clipboard text. The text to set is either supplied as arguments or read from stdin if no arguments are given.",sampleParam:"Hello"},
    6:{command:"termux-contact-list",doc:"Usage: termux-contact-list\nList all contacts.",sampleParam:""},
    7:{command:"termux-dialog",doc:"Usage: termux-dialog [-i hint] [-m] [-p] [-t title]\nShow a text entry dialog.\n\n  -i hint   the input hint to show when the input is empty\n  -m        use a textarea with multiple lines instead of a single\n  -p        enter the input as a password\n  -t title  the title to show for the input prompt",sampleParam:"-t Enter Something"},
    8:{command:"termux-download",doc:"Usage: termux-download [-d description] [-t title] url-to-download\nDownload a resource using the system download manager.\n\n  -d description  description for the download request notification\n  -t title        title for the download request notification",sampleParam:"-t Anupam http://g.jpg"},
    9:{command:"termux-infrared-frequencies",doc:"Usage: termux-infrared-frequencies\nQuery the infrared transmitter's supported carrier frequencies.",sampleParam:""},
    10:{command:"termux-infrared-transmit",doc:"Usage: termux-infrared-transmit -f frequency pattern\nTransmit an infrared pattern. The pattern is specified in comma-separated on/off intervals, such as '20,50,20,30'. Only patterns shorter than 2 seconds will be transmitted.\n\n  -f frequency  IR carrier frequency in Hertz",sampleParam:"-f 30000 20,50,20,30"},
    11:{command:"termux-location",doc:"usage: termux-location [-p provider] [-r request]\nGet the device location.\n\n  -p provider  location provider [gps/network/passive] (default: gps)\n  -r request   kind of request to make [once/last/updates] (default: once)",sampleParam:"-p gps -r once"},
    12:{command:"termux-notification",doc:"Usage: termux-notification [-c content] [-i id] [-t title] [-u url]\nDisplay a system notification.\n\n  -c content notification content to show\n  -i id      notification id (will overwrite any previous notification\n               with the same id)\n  -t title   notification title to show\n  -u url     notification url when clicking on it",sampleParam:"-c This is a notification -t Notification"},
    13:{command:"termux-share",doc:"Usage: termux-share [-a action] [-c content-type] [-d] [-t title] [file]\nShare a file specified as argument or the text received on stdin if no file argument is given.\n\n  -a action        which action to performed on the shared content:\n                     edit/send/view (default:view)\n  -c content-type  content-type to use (default: guessed from file extension,\n                     text/plain for stdin)\n  -d               share to the default receiver if one is selected\n                     instead of showing a chooser\n\n  -t title         title to use for shared content (default: shared file name)",sampleParam:"-a view photo.jpg"},
    14:{command:"termux-sms-inbox",doc:"Usage: termux-sms-inbox [-d] [-l limit] [-n] [-o offset]\nList received SMS messages.\n\n  -d         show dates when messages were created\n  -l limit   offset in sms list (default: 10)\n  -n         show phone numbers\n  -o offset  offset in sms list (default: 0)",sampleParam:"-l 10 -n -o 0"},
    15:{command:"termux-sms-send",doc:"Usage: termux-sms-send -n number[,number2,number3,...] [text]\nSend a SMS message to the specified recipient number(s). The text to send is either supplied as arguments or read from stdin if no arguments are given.\n\n  -n number(s)  recipient number(s) - separate multiple numbers by commas",sampleParam:"-n 1234567890 Hello"},
    16:{command:"termux-telephony-cellinfo",doc:"Usage: termux-telephony-cellinfo\nGet information about all observed cell information from all radios on the device including the primary and neighboring cells.",sampleParam:""},
    17:{command:"termux-telephony-deviceinfo",doc:"Usage: termux-telephony-deviceinfo\nGet information about the telephony device.",sampleParam:""},
    18:{command:"termux-toast",doc:"Usage: termux-toast [-s] [text]\nShow text in a Toast (a transient popup). The text to show is either supplied as arguments or read from stdin if no arguments are given.\n\n -s  only show the toast for a short while",sampleParam:"-s This is a toast"},
    19:{command:"termux-tts-engines",doc:"Usage: termux-tts-engines\nGet information about the available text-to-speech (TTS) engines. The name of an engine may be given to the termux-tts-speak command using the -e option.",sampleParam:""},
    20:{command:"termux-tts-speak",doc:"Usage: termux-tts-speak [-e engine] [-l language] [-p pitch] [-r rate] [-s stream] [text-to-speak]\nSpeak text with a system text-to-speech (TTS) engine. The text to speak is either supplied as arguments or read from stdin if no arguments are given.\n\n  -e engine    TTS engine to use (see termux-tts-engines)\n  -l language  language to speak in (may be unsupported by the engine)\n  -p pitch     pitch to use in speech. 1.0 is the normal pitch,\n                 lower values lower the tone of the synthesized voice,\n                 greater values increase it.\n  -r rate      speech rate to use. 1.0 is the normal speech rate,\n                 lower values slow down the speech\n                 (0.5 is half the normal speech rate)\n                 while greater values accelerates it\n                 (2.0 is twice the normal speech rate).\n  -s stream    audio stream to use (default:NOTIFICATION), one of:\n                 ALARM, MUSIC, NOTIFICATION, RING, SYSTEM, VOICE_CALL",sampleParam:"-p 1.3 Hello How are you"},
    21:{command:"termux-vibrate",doc:"Usage: termux-vibrate [-d duration] [-f]\nVibrate the device.\n\n  -d duration  the duration to vibrate in ms (default:1000)\n  -f           force vibration even in silent mode",sampleParam:"-d 500"}
  }

  //default values
  var command = api[0].command;
  var param = "";
  var doc = api[0].doc;
  var msg = "Output will appear here";

  document.getElementById("param").hidden= true;
  document.getElementById("response").innerHTML = msg;
  document.getElementById("command").innerHTML = command;
  document.getElementById("documentation").innerHTML = doc;

/** Function to select command and sampleParam*/
  function cmd(id=0){
    command = api[id].command;
    var doc = api[id].doc;
    var sampleParam = api[id].sampleParam;
    document.getElementById("documentation").innerHTML = doc;
    document.getElementById("command").innerHTML = command;
    document.getElementById("response").innerHTML = msg;
    var photoLink = document.getElementById("photo-link");
    if(photoLink){
      photoLink.parentNode.removeChild(photoLink);
    }
    if(sampleParam==""){
      document.getElementById("param").hidden = true;
      document.getElementById("paramInput").value = "";
    }
    else{
      document.getElementById("param").hidden = false;
      document.getElementById("paramInput").value = sampleParam;
    }
  }

/** Function to send AJAX Request with command and param, then show the response*/
  function execute(){
    param = document.getElementById("paramInput").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if(this.readyState == 1){
        document.getElementById("response").innerHTML = "server connection established";
      }
      if(this.readyState == 2){
        document.getElementById("response").innerHTML = "request received";
      }
      if(this.readyState == 3){
        document.getElementById("response").innerHTML = "processing request";
      }
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("response").innerHTML = this.responseText;
        if(command=="termux-camera-photo"){
          var name = param.substring(5);
          var l = document.createElement("a");
          var text = document.createTextNode("View Image");
          l.appendChild(text);
          l.setAttribute("href",name);
          l.setAttribute("target","_blank");
          l.setAttribute("id","photo-link");
          l.setAttribute("class","btn");
          document.getElementById("response").parentElement.parentElement.appendChild(l);
        }
      }
    };
    xhttp.open("POST", "<?php echo basename(__FILE__);?>", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("command=" + command + "&param=" + param);
  }
  </script>
</body>
</html>
