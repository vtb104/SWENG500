<?php
    require_once('phpcommon.php');
    if(!$auth->authenticate()){
 	header("location: login.php?"); 
    }
?>
<html>
<head>
<meta charset="utf-8">
  <script type="text/javascript">
   if(typeof Muse === "undefined") window.Muse = {}; window.Muse.assets = {"required":["jquery-1.8.3.min.js", "museutils.js", "webpro.js", "musewpslideshow.js", "jquery.museoverlay.js", "touchswipe.js", "jquery.watch.js", "compose.css"], "outOfDate":[]};
</script>
  
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
  <meta name="generator" content="2014.0.0.264"/>
  <title>Compose</title>
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="css/site_global.css?4091224983"/>
  <link rel="stylesheet" type="text/css" href="css/compose.css?4238584783" id="pagesheet"/>
  <!-- Other scripts -->
  <script type="text/javascript">
   document.documentElement.className += ' js';
   <script type="text/javascript">
   if (document.location.protocol != 'https:') document.write('\x3Cscript src="http://musecdn2.businesscatalyst.com/scripts/4.0/jquery-1.8.3.min.js" type="text/javascript">\x3C/script>');
</script>
  <script type="text/javascript">
   window.jQuery || document.write('\x3Cscript src="scripts/jquery-1.8.3.min.js" type="text/javascript">\x3C/script>');
</script>
<!-- Other scripts -->
  <script type="text/javascript">
   $(document).ready(function() { try {
(function(){var a={},b=function(a){if(a.match(/^rgb/))return a=a.replace(/\s+/g,"").match(/([\d\,]+)/gi)[0].split(","),(parseInt(a[0])<<16)+(parseInt(a[1])<<8)+parseInt(a[2]);if(a.match(/^\#/))return parseInt(a.substr(1),16);return 0};(function(){$('link[type="text/css"]').each(function(){var b=($(this).attr("href")||"").match(/\/?css\/([\w\-]+\.css)\?(\d+)/);b&&b[1]&&b[2]&&(a[b[1]]=b[2])})})();(function(){$("body").append('<div class="version" style="display:none; width:1px; height:1px;"></div>');
for(var c=$(".version"),d=0;d<Muse.assets.required.length;){var f=Muse.assets.required[d],g=f.match(/([\w\-\.]+)\.(\w+)$/),k=g&&g[1]?g[1]:null,g=g&&g[2]?g[2]:null;switch(g.toLowerCase()){case "css":k=k.replace(/\W/gi,"_").replace(/^([^a-z])/gi,"_$1");c.addClass(k);var g=b(c.css("color")),h=b(c.css("background-color"));g!=0||h!=0?(Muse.assets.required.splice(d,1),(g!=a[f]>>>24||h!=(a[f]&16777215))&&Muse.assets.outOfDate.push(f)):d++;c.removeClass(k);break;case "js":k.match(/^jquery-[\d\.]+/gi)&&typeof $!=
"undefined"?Muse.assets.required.splice(d,1):d++;break;default:throw Error("Unsupported file type: "+g);}}c.remove();(Muse.assets.outOfDate.length||Muse.assets.required.length)&&alert("Some files on the server may be missing or incorrect. Clear browser cache and try again. If the problem persists please contact website author.")})()})();
/* body */
Muse.Utils.transformMarkupToFixBrowserProblemsPreInit();/* body */
Muse.Utils.prepHyperlinks(true);/* body */
Muse.Utils.initWidget('#pamphletu139', function(elem) { new WebPro.Widget.ContentSlideShow(elem, {contentLayout_runtime:'stack',event:'click',deactivationEvent:'none',autoPlay:false,displayInterval:3000,transitionStyle:'fading',transitionDuration:0,hideAllContentsFirst:false,shuffle:false,enableSwipe:true,resumeAutoplay:true,resumeAutoplayInterval:3000,playOnce:false}); });/* #pamphletu139 */
Muse.Utils.fullPage('#page');/* 100% height page */
Muse.Utils.showWidgetsWhenReady();/* body */
Muse.Utils.transformMarkupToFixBrowserProblems();/* body */
} catch(e) { if (e && 'function' == typeof e.notify) e.notify(); else Muse.Assert.fail('Error calling selector function:' + e); }});
</script>




<title>Command Center</title>

<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=weather"></script>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="scripts/museutils.js?212726984" type="text/javascript"></script>
<script src="scripts/webpro.js?114405901" type="text/javascript"></script>
<script src="scripts/musewpslideshow.js?3797706250" type="text/javascript"></script>
<script src="scripts/jquery.museoverlay.js?254250306" type="text/javascript"></script>
<script src="scripts/touchswipe.js?12843993" type="text/javascript"></script>
<script src="scripts/jquery.watch.js?4125239756" type="text/javascript"></script>

<style>

ul
{
	list-style-type: none;
	margin: 2;
	padding: 2;
	overflow: hidden;
	text-align: right;
}
li
{
	float: inherit;
	text-align: left;
}
a:link,a:visited
{
display:inline;
width:180px;
font-weight:bold;
color:#FFFFFF;
background-color:#232323;
text-align:center;
padding:4px;
text-decoration:none;
text-transform:uppercase;
}
a:hover,a:active
{
	background-color: #232323;
	color: #4C507E;
	font-size: 14px;
}
body {
	font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12; color: #ECEDF3;
	
}

#main{
	position: absolute;
	right: 0px;
	top: 0px;
	bottom: 0px;
	width: 40%;
	min-width: 300px;
	padding: 5px;
	z-index: 11;
	background-color: #232323;
}

#map_canvas{
	position: absolute;
	left: 0px;
	bottom: 0px;
	height: 100%;
	width: 60%;
	min-width: 700px;
	z-index: 10;
}

#floatNote{
	position: fixed;
	right: 0px;
	bottom: 0px;
	color: red;	
	z-Index: 50;
	Padding: 3px;
}

#searchform{
	float: right;
}

#Messages{
        position: fixed;
        right: ;
        left: ;
        bottom: 20px;
        
}

</style>
</head>
<body onLoad="initialize()">

<div id="main">
	<div id="searchform" action="#"><input type="text" id="searchbox"/><button id="searchnow">Map Search</button></div>

  <img src="med_logo.png" />
  <div id="content">
  <h1 style="text-align: center;">Search and Rescue</h1>
  <h2 style="text-align: center;">Command Central</h2>

  <div id="info">Info Here</div> 
  <button id="pantorecent" disabled=true>Pan to Recent Point</button>	
  </div>
</div>

    <div id="Messages">
  <div class="clearfix" id="page"><!-- group -->
   <div class="PamphletWidget clearfix grpelem" id="pamphletu139"><!-- none box -->
    <div class="popup_anchor" id="u153popup">
     <div class="ContainerGroup clearfix" id="u153"><!-- stack box -->
      <div class="Container clearfix grpelem" id="u164"><!-- column -->
       <div class="clearfix colelem" id="u321-4"><!-- content -->
        <p>Message All</p>
       </div>
       <div class="clearfix colelem" id="u406-4"><!-- content -->
        <p><span id="u406">Enter message here</span></p>
       </div>
       <div class="Button rounded-corners clearfix colelem" id="buttonu409"><!-- container box -->
        <div class="clearfix grpelem" id="u411-4"><!-- content -->
         <p>Send</p>
        </div>
       </div>
      </div>
      <div class="Container invi clearfix grpelem" id="u159"><!-- column -->
       <div class="clearfix colelem" id="u322-4"><!-- content -->
        <p>Team Message</p>
       </div>
       <div class="clearfix colelem" id="u407-4"><!-- content -->
        <p><span id="u407">Enter message here</span></p>
       </div>
       <div class="Button rounded-corners clearfix colelem" id="buttonu412"><!-- container box -->
        <div class="clearfix grpelem" id="u413-4"><!-- content -->
         <p>Send</p>
        </div>
       </div>
      </div>
      <div class="Container invi clearfix grpelem" id="u154"><!-- column -->
       <div class="clearfix colelem" id="u323-4"><!-- content -->
        <p>Individual Message</p>
       </div>
       <div class="clearfix colelem" id="u408-4"><!-- content -->
        <p><span id="u408">Enter message here</span></p>
       </div>
       <div class="Button rounded-corners clearfix colelem" id="buttonu414"><!-- container box -->
        <div class="clearfix grpelem" id="u415-4"><!-- content -->
         <p>Send</p>
        </div>
       </div>
      </div>
      <div class="Container invi clearfix grpelem" id="u353"><!-- column -->
       <div class="clearfix colelem" id="u402-4"><!-- content -->
        <p>View Messages</p>
       </div>
       <div class="colelem" id="u403"><!-- simple frame --></div>
      </div>
     </div>
    </div>
    <div class="ThumbGroup clearfix grpelem" id="u144"><!-- none box -->
     <div class="popup_anchor">
      <div class="Thumb popup_element clearfix" id="u145"><!-- group -->
       <div class="clearfix grpelem" id="u146-4"><!-- content -->
        <p>Message All</p>
       </div>
      </div>
     </div>
     <div class="popup_anchor">
      <div class="Thumb popup_element clearfix" id="u149"><!-- group -->
       <div class="clearfix grpelem" id="u150-4"><!-- content -->
        <p>Team Message</p>
       </div>
      </div>
     </div>
     <div class="popup_anchor">
      <div class="Thumb popup_element clearfix" id="u147"><!-- group -->
       <div class="clearfix grpelem" id="u148-4"><!-- content -->
        <p>Individual Message</p>
       </div>
      </div>
     </div>
     <div class="popup_anchor">
      <div class="Thumb popup_element clearfix" id="u372"><!-- group -->
       <div class="clearfix grpelem" id="u376-4"><!-- content -->
        <p>View Messages</p>
       </div>
      </div>
     </div>
    </div>
   </div>
   <div class="clearfix grpelem" id="u129-4"><!-- content -->
    <p>Message Center</p>
   </div>
   <div class="verticalspacer"></div>
  </div>
  </div>
    
<div id="map_canvas"></div>
<div id="floatNote">Test</div>
</body>
</html>
