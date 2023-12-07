<?php
function ValidateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'form1')
{
   $mailto = 'salah@josianeng.com';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $subject = 'Website form new Contact';
   $message = 'Values submitted from web site form:';
   $success_url = './success.html';
   $error_url = './fail.html';
   $error = '';
   $eol = "\n";
   $max_filesize = isset($_POST['filesize']) ? $_POST['filesize'] * 1024 : 1024000;
   $boundary = md5(uniqid(time()));

   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;
   if (!ValidateEmail($mailfrom))
   {
      $error .= "The specified email address is invalid!\n<br>";
   }

   if (!empty($error))
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $error, $errorcode);
      echo $errorcode;
      exit;
   }

   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $message .= $eol;
   $message .= "IP Address : ";
   $message .= $_SERVER['REMOTE_ADDR'];
   $message .= $eol;
   $logdata = '';
   foreach ($_POST as $key => $value)
   {
      if (!in_array(strtolower($key), $internalfields))
      {
         if (!is_array($value))
         {
            $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
         }
         else
         {
            $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
         }
      }
   }
   $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
   $body .= '--'.$boundary.$eol;
   $body .= 'Content-Type: text/plain; charset=ISO-8859-1'.$eol;
   $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
   $body .= $eol.stripslashes($message).$eol;
   if (!empty($_FILES))
   {
       foreach ($_FILES as $key => $value)
       {
          if ($_FILES[$key]['error'] == 0 && $_FILES[$key]['size'] <= $max_filesize)
          {
             $body .= '--'.$boundary.$eol;
             $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
             $body .= 'Content-Transfer-Encoding: base64'.$eol;
             $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
             $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
          }
      }
   }
   $body .= '--'.$boundary.'--'.$eol;
   if ($mailto != '')
   {
      mail($mailto, $subject, $body, $header);
   }
   header('Location: '.$success_url);
   exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Contact</title>
<meta name="generator" content="WYSIWYG Web Builder 11 - http://www.wysiwygwebbuilder.com">
<style type="text/css">
div#container
{
   width: 800px;
   position: relative;
   margin: 0 auto 0 auto;
   text-align: left;
}
body
{
   background-color: #EEEEEE;
   color: #000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   line-height: 1.1875;
   margin: 0;
   text-align: center;
}
a
{
   color: #0000FF;
   text-decoration: underline;
}
a:visited
{
   color: #800080;
}
a:active
{
   color: #FF0000;
}
a:hover
{
   color: #0000FF;
   text-decoration: underline;
}
#wb_Text6 
{
   background-color: transparent;
   background-image: none;
   border: 0px #000000 solid;
   padding: 0;
   margin: 0;
   text-align: left;
}
#wb_Text6 div
{
   text-align: left;
}
#Layer4
{
   background-color: #0B497C;
   background: -moz-linear-gradient(bottom,#0B497C 0%,#1F5788 100%);
   background: -webkit-linear-gradient(bottom,#0B497C 0%,#1F5788 100%);
   background: -o-linear-gradient(bottom,#0B497C 0%,#1F5788 100%);
   background: -ms-linear-gradient(bottom,#0B497C 0%,#1F5788 100%);
   background: linear-gradient(bottom,#0B497C 0%,#1F5788 100%);
}
#wb_Text2 
{
   background-color: transparent;
   background-image: none;
   border: 0px #000000 solid;
   padding: 0;
   margin: 0;
   text-align: left;
}
#wb_Text2 div
{
   text-align: left;
}
#wb_CssMenu1
{
   border: 0px #DCDCDC solid;
   background-color: transparent;
}
#wb_CssMenu1 ul
{
   list-style-type: none;
   margin: 0;
   padding: 0;
}
#wb_CssMenu1 li
{
   float: left;
   margin: 0;
   padding: 0px 0px 0px 0px;
   width: 80px;
}
#wb_CssMenu1 a
{
   display: block;
   float: left;
   color: #696969;
   border: 0px #C0C0C0 solid;
   background-color: #F4F3F3;
   background: -moz-linear-gradient(bottom,#F4F3F3 0%,#F4F3F3 100%);
   background: -webkit-linear-gradient(bottom,#F4F3F3 0%,#F4F3F3 100%);
   background: -o-linear-gradient(bottom,#F4F3F3 0%,#F4F3F3 100%);
   background: -ms-linear-gradient(bottom,#F4F3F3 0%,#F4F3F3 100%);
   background: linear-gradient(bottom,#F4F3F3 0%,#F4F3F3 100%);
   font-family: Arial;
   font-weight: bold;
   font-size: 15px;
   font-style: normal;
   text-decoration: none;
   width: 70px;
   height: 70px;
   padding: 0px 5px 0px 5px;
   vertical-align: middle;
   line-height: 70px;
   text-align: center;
}
#wb_CssMenu1 li:hover a, #wb_CssMenu1 a:hover, #wb_CssMenu1 .active
{
   color: #696969;
   background-color: #B9B9B9;
   background: -moz-linear-gradient(bottom,#B9B9B9 0%,#EEEEEE 100%);
   background: -webkit-linear-gradient(bottom,#B9B9B9 0%,#EEEEEE 100%);
   background: -o-linear-gradient(bottom,#B9B9B9 0%,#EEEEEE 100%);
   background: -ms-linear-gradient(bottom,#B9B9B9 0%,#EEEEEE 100%);
   background: linear-gradient(bottom,#B9B9B9 0%,#EEEEEE 100%);
   border: 0px #C0C0C0 solid;
}
#wb_CssMenu1 li.firstmain
{
   padding-left: 0px;
}
#wb_CssMenu1 li.lastmain
{
   padding-right: 0px;
}
#wb_CssMenu1 br
{
   clear: both;
   font-size: 1px;
   height: 0;
   line-height: 0;
}
#wb_Form1
{
   background-color: #FFFFFF;
   background-image: none;
   border: 1px #CCCCCC solid;
}
#Label1
{
   border: 0px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Label2
{
   border: 0px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Editbox2
{
   border: 1px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Editbox2:focus
{
   border-color: #66AFE9;
   -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   outline: 0;
}
#Label3
{
   border: 0px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Editbox3
{
   border: 1px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Editbox3:focus
{
   border-color: #66AFE9;
   -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   outline: 0;
}
#Label4
{
   border: 0px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Editbox4
{
   border: 1px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Editbox4:focus
{
   border-color: #66AFE9;
   -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   outline: 0;
}
#Label5
{
   border: 0px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Editbox5
{
   border: 1px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Editbox5:focus
{
   border-color: #66AFE9;
   -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   outline: 0;
}
#Button1
{
   border: 1px #2E6DA4 solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: #3370B7;
   background-image: none;
   color: #FFFFFF;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
}
#Label6
{
   border: 0px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: transparent;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Editbox6
{
   border: 1px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   vertical-align: middle;
}
#Editbox6:focus
{
   border-color: #66AFE9;
   -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   outline: 0;
}
#TextArea1
{
   border: 1px #CCCCCC solid;
   -moz-border-radius: 4px;
   -webkit-border-radius: 4px;
   border-radius: 4px;
   background-color: #FFFFFF;
   background-image: none;
   color :#000000;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   padding: 4px 4px 4px 4px;
   text-align: left;
   overflow: auto;
   resize: none;
}
#TextArea1:focus
{
   border-color: #66AFE9;
   -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(102,175,233,0.60);
   outline: 0;
}
#wb_Text1 
{
   background-color: transparent;
   background-image: none;
   border: 0px #000000 solid;
   padding: 0;
   margin: 0;
   text-align: left;
}
#wb_Text1 div
{
   text-align: left;
}
#wb_Text11 
{
   background-color: transparent;
   background-image: none;
   border: 0px #000000 solid;
   padding: 0;
   margin: 0;
   text-align: center;
}
#wb_Text11 div
{
   text-align: center;
}
#Image2
{
   border: 0px #000000 solid;
   padding: 0px 0px 0px 0px;
   left: 0;
   top: 0;
   width: 100%;
   height: 100%;
}
</style>
<script type="text/javascript">
function Validatecontact(theForm)
{
   var regexp;
   regexp = /^[A-Za-zA?A????CEEEE??II????O???U?UU???ΰ?β????ηθικλ??ξο????τ???ω?ϋό??? \t\r\n\f]*$/;
   if (!regexp.test(theForm.Editbox6.value))
   {
      alert("Please fill you name");
      theForm.Editbox6.focus();
      return false;
   }
   if (theForm.Editbox6.value == "")
   {
      alert("Please fill you name");
      theForm.Editbox6.focus();
      return false;
   }
   if (theForm.Editbox6.value.length < 3)
   {
      alert("Please fill you name");
      theForm.Editbox6.focus();
      return false;
   }
   return true;
}
</script>
</head>
<body>
<div id="container">

<div id="wb_Text6" style="position:absolute;left:9px;top:14px;width:269px;height:37px;z-index:13;text-align:left;">
<span style="color:#05467E;font-family:'Trebuchet MS';font-size:29px;"><strong>Josian Engineering</strong></span></div>
<div id="wb_Text2" style="position:absolute;left:36px;top:194px;width:214px;height:37px;z-index:14;text-align:left;">
<span style="color:#05467E;font-family:'Trebuchet MS';font-size:29px;"><strong>Contact Us</strong></span></div>
<div id="wb_CssMenu1" style="position:absolute;left:480px;top:2px;width:567px;height:70px;z-index:15;">
<ul>
<li class="firstmain"><a href="./index.html" target="_self" title="Home">Home</a>
</li>
<li><a href="./Projects.html" target="_self" title="Projects">Projects</a>
</li>
<li><a href="./Services.html" target="_self" title="Services">Services</a>
</li>
<li><a class="active" href="./Contact.php" target="_self" title="Contact">Contact</a>
</li>
</ul>
</div>
<div id="wb_Form1" style="position:absolute;left:98px;top:267px;width:548px;height:451px;z-index:16;">
<form name="contact" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" id="Form1" onsubmit="return Validatecontact(this)">
<input type="hidden" name="formid" value="form1">
<label for="Text1" id="Label1" style="position:absolute;left:9px;top:188px;width:76px;height:18px;line-height:18px;z-index:0;">Requirement:</label>
<label for="Editbox2" id="Label2" style="position:absolute;left:9px;top:47px;width:49px;height:18px;line-height:18px;z-index:1;">Address:</label>
<input type="text" id="Editbox2" style="position:absolute;left:76px;top:47px;width:243px;height:18px;line-height:18px;z-index:2;" name="address" value="">
<label for="Editbox3" id="Label3" style="position:absolute;left:9px;top:80px;width:49px;height:18px;line-height:18px;z-index:3;">City:</label>
<input type="text" id="Editbox3" style="position:absolute;left:76px;top:80px;width:243px;height:18px;line-height:18px;z-index:4;" name="city" value="">
<label for="Editbox4" id="Label4" style="position:absolute;left:9px;top:113px;width:49px;height:18px;line-height:18px;z-index:5;">Phone:</label>
<input type="text" id="Editbox4" style="position:absolute;left:76px;top:113px;width:243px;height:18px;line-height:18px;z-index:6;" name="phone" value="">
<label for="Editbox5" id="Label5" style="position:absolute;left:9px;top:146px;width:49px;height:18px;line-height:18px;z-index:7;">Email:</label>
<input type="text" id="Editbox5" style="position:absolute;left:76px;top:146px;width:243px;height:18px;line-height:18px;z-index:8;" name="email" value="">
<input type="text" id="Editbox6" style="position:absolute;left:76px;top:14px;width:243px;height:18px;line-height:18px;z-index:9;" name="name" value="">
<input type="submit" id="Button1" name="" value="Send" style="position:absolute;left:226px;top:410px;width:96px;height:25px;z-index:10;">
<textarea name="Requirement" id="TextArea1" style="position:absolute;left:77px;top:214px;width:241px;height:140px;z-index:11;" rows="7" cols="37"></textarea>
</form>
</div>
<label for="Text1" id="Label6" style="position:absolute;left:108px;top:283px;width:49px;height:18px;line-height:18px;z-index:17;">Name:</label>
<div id="wb_Text1" style="position:absolute;left:108px;top:733px;width:523px;height:16px;z-index:18;text-align:left;">
<span style="color:#000000;font-family:Arial;font-size:13px;">For Urgent requirements please contact Mr. Salah Awad at +962 7 9589 3328</span></div>
<div id="wb_Text11" style="position:absolute;left:7px;top:867px;width:780px;height:16px;text-align:center;z-index:19;">
<span style="color:#FFFFFF;font-family:'Trebuchet MS';font-size:11px;">Copyright © 2005 by &quot;Josian Engineering&quot;&nbsp; ·&nbsp; All Rights reserved&nbsp; ·</span></div>
<div id="wb_Image2" style="position:absolute;left:288px;top:7px;width:60px;height:57px;z-index:20;">
<img src="images/Logo_g.png" id="Image2" alt=""></div>
</div>
<div id="Layer4" style="position:absolute;text-align:center;left:0px;top:845px;width:100%;height:55px;z-index:21;">
<div id="Layer4_Container" style="width:800px;position:relative;margin-left:auto;margin-right:auto;text-align:left;">
</div>
</div>
</body>
</html>