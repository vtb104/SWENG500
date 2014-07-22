<?php
	include_once("phpcommon.php");
	$user = array("username"=> 'Error');
	if(isset($_SESSION['userid']))
	{
		$user = $db->get_user($_SESSION['userid'], false);
	}	
?>
 <head>

  <script type="text/javascript">
   if(typeof Muse == "undefined") window.Muse = {}; window.Muse.assets = {"required":["jquery-1.8.3.min.js", "museutils.js", "jquery.watch.js", "webpro.js", "send.css"], "outOfDate":[]};
</script>
  
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
  <meta name="generator" content="2014.0.0.264"/>
  <title>Send</title>
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="css/site_global.css?475048684"/>
  <link rel="stylesheet" type="text/css" href="css/send.css?4014180867" id="pagesheet"/>
  <!-- Other scripts -->
  <script type="text/javascript">
   document.documentElement.className += ' js';
</script>
<!-- Page JavaScript -->
<script src="ic.js"></script>
<script src="messages.js"></script>
<script src="cookies.js"></script>


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
	bottom: 0px;
	color: red;	
	z-Index: 50;
	Padding: 3px;
}
</style>
<script>
<?php if(isset($_SESSION['userid']))
	{
		echo "var userID = " . $_SESSION['userid'];
	}else{
		echo "var userID = 0";
	}	
?>
</script>
</head>

<body onLoad="initialize()">
<div id="pagewrapper">
    <div id="main"></div>
    <div id="menubar">
        <a href="ic.php">Home</a>
    	<a href="fu.php">Field Unit</a>
        <a id="logoutbutton" href="logout.php">Log Out</a>
        <button onclick="sendNewMessage()">TEST GET MSGS</button>
    </div>
    
      <div id="content">
          <h1 style="text-align: center;">Search and Rescue</h1>

   </head>
 <body>

  <div class="clearfix" id="page"><!-- group -->
   <div class="clearfix grpelem" id="pbuttonu144"><!-- column -->
       <a class="nonblock nontext Button rounded-corners clearfix colelem" id="buttonu144" href="Messages.php"><!-- container box --><div class="clearfix grpelem" id="u145-4"><!-- content --><p>View Messages</p></div></a>
    <form class="form-grp clearfix colelem" id="widgetu257" method="post" enctype="multipart/form-data" action="scripts/form-u257.php"><!-- none box -->
     <div class="fld-grp clearfix grpelem" id="widgetu264" data-required="true"><!-- none box -->
      <label class="fld-label actAsDiv clearfix grpelem" id="u265-4" for="widgetu264_input"><!-- content -->
       <span class="actAsPara">To:</span>
      </label>
      <span class="fld-input NoWrap actAsDiv clearfix grpelem" id="u266-4"><!-- content --><select class="wrapped-input" type="text" spellcheck="false" id="widgetu264_input" name="To" tabindex="1">
      <?php
	  		//List all current users for a to field
			$users = $db->list_users(false);
			foreach($users as $one){
				echo "<option value='" . $one['userID'] . "'>" . $one['username'] . "</option>";
			}
	  
	  ?>
      </select><label class="wrapped-input fld-prompt" id="widgetu264_prompt" for="widgetu264_input"><span class="actAsPara">Enter Name</span></label></span>
     </div>
     <div class="fld-grp clearfix grpelem" id="widgetu268" data-required="true"><!-- none box -->
      <label class="fld-label actAsDiv clearfix grpelem" id="u270-4" for="widgetu268_input"><!-- content -->
       <span class="actAsPara">Subject:</span>
      </label>
      <span class="fld-input NoWrap actAsDiv clearfix grpelem" id="u271-4"><!-- content --><input class="wrapped-input" type="text" spellcheck="false" id="widgetu268_input" name="Subject" tabindex="3"/><label class="wrapped-input fld-prompt" id="widgetu268_prompt" for="widgetu268_input"><span class="actAsPara">Enter Subject</span></label></span>
     </div>
     <div class="clearfix grpelem" id="u272-4"><!-- content -->
      <p>Submitting Form...</p>
     </div>
     <div class="clearfix grpelem" id="u262-4"><!-- content -->
      <p>The server encountered an error.</p>
     </div>
     <div class="clearfix grpelem" id="u273-4"><!-- content -->
      <p>Form received.</p>
     </div>
     <input class="submit-btn NoWrap grpelem" id="u263-17"  tabindex="6"/><!-- state-based BG images -->
     <div class="fld-grp clearfix grpelem" id="widgetu258" data-required="true"><!-- none box -->
      <label class="fld-label actAsDiv clearfix grpelem" id="u261-4" for="widgetu258_input"><!-- content -->
       <span class="actAsPara">Message:</span>
      </label>
      <span class="fld-textarea actAsDiv clearfix grpelem" id="u259-4"><!-- content --><textarea class="wrapped-input" id="widgetu258_input" name="Body" tabindex="5"></textarea><label class="wrapped-input fld-prompt" id="widgetu258_prompt" for="widgetu258_input"><span class="actAsPara">Enter Your Message</span></label></span>
     </div>
     <div class="fld-grp clearfix grpelem" id="widgetu274" data-required="true"><!-- none box -->
      <label class="fld-label actAsDiv clearfix grpelem" id="u275-4" for="widgetu274_input"><!-- content -->
       <span class="actAsPara">From:</span>
      </label>
      <span class="fld-input NoWrap actAsDiv clearfix grpelem" id="u276-4"><!-- content --><input class="wrapped-input" type="text" id="widgetu274_input" name="From" tabindex="2" value="<?php echo $user [0]['username'];?>"/><label class="wrapped-input fld-prompt" id="widgetu274_prompt" for="widgetu274_input"><span class="actAsPara">Enter Name</span></label></span>
     </div>
     <div class="fld-grp clearfix grpelem" id="widgetu278" data-required="true"><!-- none box -->
      <label class="fld-label actAsDiv clearfix grpelem" id="u279-4" for="widgetu278_input"><!-- content -->
       <span class="actAsPara">Urgency:</span>
      </label>
      <span class="fld-input NoWrap actAsDiv clearfix grpelem" id="u280-4"><!-- content --><input class="wrapped-input" type="text" id="widgetu278_input" name="Urgency" tabindex="4"/><label class="wrapped-input fld-prompt" id="widgetu278_prompt" for="widgetu278_input"><span class="actAsPara">Enter Urgency</span></label></span>
     </div>
    </form>
   </div>
   <div class="clearfix grpelem" id="u143-4"><!-- content -->
    <p>Message Center</p>
   </div>
   <a class="nonblock nontext Button ButtonSelected rounded-corners clearfix grpelem" id="buttonu146" href="send.php"><!-- container box --><div class="clearfix grpelem" id="u147-4"><!-- content --><p>Compose Message</p></div></a>
   <div class="verticalspacer"></div>
  </div>
  <div class="preload_images">
   <img class="preload" src="images/u263-17-r.png" alt=""/>
   <img class="preload" src="images/u263-17-m.png" alt=""/>
   <img class="preload" src="images/u263-17-fs.png" alt=""/>
  </div>
  <!-- JS includes -->
  <script type="text/javascript">
   if (document.location.protocol != 'https:') document.write('\x3Cscript src="http://musecdn2.businesscatalyst.com/scripts/4.0/jquery-1.8.3.min.js" type="text/javascript">\x3C/script>');
</script>
  <script type="text/javascript">
   window.jQuery || document.write('\x3Cscript src="scripts/jquery-1.8.3.min.js" type="text/javascript">\x3C/script>');
</script>
  <script src="scripts/museutils.js?212726984" type="text/javascript"></script>
  <script src="scripts/webpro.js?114405901" type="text/javascript"></script>
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
Muse.Utils.initWidget('#widgetu257', function(elem) { new WebPro.Widget.Form(elem, {validationEvent:'submit',errorStateSensitivity:'high',fieldWrapperClass:'fld-grp',formSubmittedClass:'frm-sub-st',formErrorClass:'frm-subm-err-st',formDeliveredClass:'frm-subm-ok-st',notEmptyClass:'non-empty-st',focusClass:'focus-st',invalidClass:'fld-err-st',requiredClass:'fld-err-st',ajaxSubmit:true}); });/* #widgetu257 */
Muse.Utils.fullPage('#page');/* 100% height page */
Muse.Utils.showWidgetsWhenReady();/* body */
Muse.Utils.transformMarkupToFixBrowserProblems();/* body */
} catch(e) { if (e && 'function' == typeof e.notify) e.notify(); else Muse.Assert.fail('Error calling selector function:' + e); }});

$(function(){
	$("#u263-17").click(function(){
		sendNewMessage();
	});
	
});
</script>
   </body>
</html>
