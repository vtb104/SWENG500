<?php
    require_once('phpcommon.php');
    if(!$auth->authenticate()){
 	header("location: login.php"); 
    }

?>
<html>
<head>
  <script type="text/javascript">
   if(typeof Muse == "undefined") window.Muse = {}; window.Muse.assets = {"required":["jquery-1.8.3.min.js", "museutils.js", "jquery.watch.js", "index.css"], "outOfDate":[]};
</script>
  
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
  <meta name="generator" content="2014.0.0.264"/>
  <title>View</title>
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="css/site_global.css?475048684"/>
  <link rel="stylesheet" type="text/css" href="css/index.css?291268769" id="pagesheet"/>
  <!-- Other scripts -->
  <script type="text/javascript">
   //ocument.documentElement.className += ' js';
   </script>


<!-- Page JavaScript -->
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true&libraries=weather"></script>
<script src="sharedJS.js"></script>
<script src="ic.js"></script>
<script src="messages.js"></script>
<script>
<?php if(isset($_SESSION['userid']))
	{
		echo "userID = " . $_SESSION['userid'] . ";";
	}else{
		echo "userID = 0";
	}	
?>
var doFirst = function(){
	timer = setInterval(function(){checkMessages(userID);}, 1000);
	getMessage(userID);
};

</script>

<style>

ul
{
list-style-type:none;
margin:2;
padding:2;
overflow:hidden;
}
li
{
float:inherit;
text-align: left;
}
a:link,a:visited
{
display:inline;
width:180px;
font-weight:bold;
color:#FFFFFF;
background-color:#232323;
text-align:left;
padding:4px;
text-decoration:none;
text-transform:uppercase;
}
a:hover,a:active
{
	color: #4C507E;
}
body {
font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12; color: #ECEDF3; background-color: #232323;
}
#logo{position: fixed; right: 0px; top: 30px; width: 150px; z-index: 20}

#menubar a{
	padding: 4px;
	background-color: #494242;	
	border: 1px solid #847272;
	border-radius: 3px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
}
#menubar a:hover{
	background-color: #ccc;
}

#floatNote{
	position: fixed;
	right: 0px;
	bottom: 300px;
	color: red;	
	z-Index: 50;
	Padding: 3px;
    }

#messageHeaderTable{
	position: absolute;
    background-color: black;
	font-family: Georgia, Palatino, Palatino Linotype, Times, Times New Roman, serif;
	left: 25px;
	top: 200px;
	right: 25px;;
	min-width: 600px;
	border-spacing: px;
	border: 1px solid black;
	border-collapse: collapse;
     }
	 
.messageclass{
	margin-top: 2px;
	margin-bottom: 2px;
}
.messageread{
	background-coolor: #222;	
}
.messageunread{
	background-coolor: #555;	
}
#messageoutput td{
    padding-left: 2px;
	padding-right: 2px;
	padding-top: 4px;
	padding-bottom: 4px;
	height: 40px;
}
#messageoutput tr:hover{
	cursor: pointer;
	background-color: #555;	
}

.msg1{width: 40px; text-align: center;}
.msg2{width: 110px;}	
.msg3{width: 110px;}
.msg4{width: 220px;}
.msg5{width: 172px;}

</style>
</head>

<body onLoad="doFirst()">
<div id="pagewrapper">
    <div id="menubar">
        <a href="ic.php">Home</a>
    	<a href="fu.php">Field Unit</a>
        <a id="logoutbutton" href="logout.php">Log Out</a>
    </div>
    
    <div id="content">
    	<h1 style="text-align: center;">Search and Rescue</h1>

  		<div class="clearfix" id="page"><!-- group -->
      		<a class="nonblock nontext Button ButtonSelected rounded-corners clearfix grpelem" id="buttonu160" href="Messages.php"><!-- container box --><div class="clearfix grpelem" id="u161-4"><!-- content --><p>Fetch Messages</p></div></a>
			<a class="nonblock nontext Button rounded-corners clearfix grpelem" id="buttonu162" href="send.php"><!-- container box --><div class="clearfix grpelem" id="u163-4"><!-- content --><p>Compose Message</p></div></a>


      		<div class="clearfix grpelem" id="pu159-4"><!-- column -->
    			<div class="clearfix colelem" id="u159-4"><!-- content -->
     				<p>Message Center</p>
    			</div>
    			<div class="clearfix colelem" id="u164-4"><!-- content -->
     				<p>Received Messages</p>
    			</div>
                <div id="messageHeaderTable">
                <div id="info"> </div>
                	<table style="width: 100%" id="messageoutput">  
                    	
                        
                	</table>
                    <br/>
                    <div id="testline"></div>
                    <br/>
                   	<div id="testoutput"></div>
            	</div>
   			</div>
 
  		</div><!-- Page -->
	</div><!-- Content -->
</div><!-- Page wrapper -->
          
  <!-- JS includes -->
  <script type="text/javascript">
   if (document.location.protocol != 'https:') document.write('\x3Cscript src="http://musecdn2.businesscatalyst.com/scripts/4.0/jquery-1.8.3.min.js" type="text/javascript">\x3C/script>');
</script>
  <script type="text/javascript">
   window.jQuery || document.write('\x3Cscript src="scripts/jquery-1.8.3.min.js" type="text/javascript">\x3C/script>');
</script>
  <script src="scripts/museutils.js?212726984" type="text/javascript"></script>
  <script src="scripts/jquery.watch.js?4125239756" type="text/javascript"></script>
  <!-- Other scripts -->
  <script type="text/javascript">
   $(document).ready(function() { try {
(function(){var a={},b=function(a){if(a.match(/^rgb/))return a=a.replace(/\s+/g,"").match(/([\d\,]+)/gi)[0].split(","),(parseInt(a[0])<<16)+(parseInt(a[1])<<8)+parseInt(a[2]);if(a.match(/^\#/))return parseInt(a.substr(1),16);return 0};(function(){$('link[type="text/css"]').each(function(){var b=($(this).attr("href")||"").match(/\/?css\/([\w\-]+\.css)\?(\d+)/);b&&b[1]&&b[2]&&(a[b[1]]=b[2])})})();(function(){$("body").append('<div class="version" style="display:none; width:1px; height:1px;"></div>');
for(var c=$(".version"),d=0;d<Muse.assets.required.length;){var f=Muse.assets.required[d],g=f.match(/([\w\-\.]+)\.(\w+)$/),k=g&&g[1]?g[1]:null,g=g&&g[2]?g[2]:null;switch(g.toLowerCase()){case "css":k=k.replace(/\W/gi,"_").replace(/^([^a-z])/gi,"_$1");c.addClass(k);var g=b(c.css("color")),h=b(c.css("background-color"));g!=0||h!=0?(Muse.assets.required.splice(d,1),(g!=a[f]>>>24||h!=(a[f]&16777215))&&Muse.assets.outOfDate.push(f)):d++;c.removeClass(k);break;case "js":k.match(/^jquery-[\d\.]+/gi)&&typeof $!=
"undefined"?Muse.assets.required.splice(d,1):d++;break;default:throw Error("Unsupported file type: "+g);}}c.remove();(Muse.assets.outOfDate.length||Muse.assets.required.length)&&alert("Some files on the server may be missing or incorrect. Clear browser cache and try again. If the problem persists please contact website author.")})()})();
/* body */
Muse.Utils.transformMarkupToFixBrowserProblemsPreInit();/* body */
Muse.Utils.prepHyperlinks(true);/* body */
Muse.Utils.fullPage('#page');/* 100% height page */
Muse.Utils.showWidgetsWhenReady();/* body */
Muse.Utils.transformMarkupToFixBrowserProblems();/* body */
} catch(e) { if (e && 'function' == typeof e.notify) e.notify(); else Muse.Assert.fail('Error calling selector function:' + e); }});
</script>
</body>
</html>
