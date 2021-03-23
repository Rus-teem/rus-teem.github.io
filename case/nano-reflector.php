<?php
function ValidateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'footer-contact')
{
   $mailto = 'hv.rustem@yandex.ru';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $subject = 'Форма контактов';
   $message = 'Данные - Кейс "Nano reflector",  footer';
   $success_url = '';
   $error_url = '';
   $eol = "\n";
   $error = '';
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $boundary = md5(uniqid(time()));
   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;

   try
   {
      if (!ValidateEmail($mailfrom))
      {
         $error .= "The specified email address (" . $mailfrom . ") is invalid!\n<br>";
         throw new Exception($error);
      }
      $message .= $eol;
      $message .= "IP Address : ";
      $message .= $_SERVER['REMOTE_ADDR'];
      $message .= $eol;
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
      $body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
      $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
      $body .= $eol.stripslashes($message).$eol;
      if (!empty($_FILES))
      {
         foreach ($_FILES as $key => $value)
         {
             if ($_FILES[$key]['error'] == 0)
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
   }
   catch (Exception $e)
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $e->getMessage(), $errorcode);
      echo $errorcode;
   }
   exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Кейс: "Nano Reflector"</title>
<meta name="author" content="Хуснутдинов Рустем">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
<link href="favicon-32x32.png" rel="icon" sizes="32x32" type="image/png">
<link href="apple-touch-icon.png" rel="icon" sizes="180x180" type="image/png">
<link href="android-chrome-192x192.png" rel="apple-touch-icon" sizes="192x192">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500&subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400&subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Arial" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Calibri" rel="stylesheet">
<link href="css/Rustem-porfolio.css" rel="stylesheet">
<link href="css/nano-reflector.css" rel="stylesheet">
<script src="jquery-1.12.4.min.js"></script>
<script src="transition.min.js"></script>
<script src="collapse.min.js"></script>
<script src="dropdown.min.js"></script>
<script src="wb.validation.min.js"></script>
<link rel="stylesheet" href="fancybox/jquery.fancybox-1.3.4.css">
<script src="fancybox/jquery.easing-1.3.pack.js"></script>
<script src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script src="fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script>
$(document).ready(function()
{
   $(document).on('click','.ThemeableMenu1-navbar-collapse.in',function(e)
   {
      if ($(e.target).is('a') && ($(e.target).attr('class') != 'dropdown-toggle')) 
      {
         $(this).collapse('hide');
      }
   });
   $("a[data-rel='PhotoGallery1']").attr('rel', 'PhotoGallery1');
   $("a[rel^='PhotoGallery1']").fancybox({});
   $("#Footer-contact").submit(function(event)
   {
      var isValid = $.validate.form(this);
      return isValid;
   });
   $("#phone").validate(
   {
      required: false,
      bootstrap: true,
      type: 'custom',
      param: ,
      color_text: '#000000',
      color_hint: '#00FF00',
      color_error: '#FF0000',
      color_border: '#808080',
      nohint: false,
      font_family: 'Arial',
      font_size: '13px',
      position: 'topleft',
      offsetx: 0,
      offsety: 0,
      effect: 'none',
      error_text: ''
   });
});
</script>

<!-- Подключение библиотеки jQuery -->
<script src="jquery.js"></script>
<!-- Подключение jQuery плагина Masked Input -->
<script src="jquery.maskedinput.min.js"></script>

<script>
//Код jQuery, установливающий маску для ввода телефона элементу input
//1. После загрузки страницы,  когда все элементы будут доступны выполнить...
$(function(){
  //2. Получить элемент, к которому необходимо добавить маску
  $(".phone").mask("+7(999) 999-99-99");
});
</script>
</head>
<body>
<div id="wb_LayoutGrid1">
<div id="LayoutGrid1">
<div class="col-1">
<div id="wb_ThemeableMenu1" style="display:inline-block;width:100%;z-index:1002;">
<div id="ThemeableMenu1" class="ThemeableMenu1" style="width:100%;height:auto !important;">
<div class="container">
<a id="ThemeableMenu1-logo" href="#"><img alt="" title="" src="images/logo32.jpg"></a>
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".ThemeableMenu1-navbar-collapse">
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
</div>
<div class="ThemeableMenu1-navbar-collapse collapse">
<ul class="nav navbar-nav">
<li class="">
<a href="./../index.html">Главная</a>
</li>
<li class="">
<a href="#Kontakt">Контакты</a>
</li>
</ul>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid2">
<div id="LayoutGrid2">
<div class="col-1">
<div id="wb_Image1" style="display:inline-block;width:100%;height:auto;z-index:0;">
<img src="images/unnamed-logo.png" id="Image1" alt="">
</div>
</div>
<div class="col-2">
<div id="wb_Text1">
<span style="color:#FFFFFF;"><strong>Nano Reflector<br><br>Универсальное супергидрофобное покрытие,<br>результат последних достижений в области<br>нанотехнологий<br>Отдельным направлением можно выделить строительство бань и саун от средних до премиум класса.</strong></span>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid3">
<div id="LayoutGrid3">
<div class="col-1">
<div id="wb_Image2" style="display:inline-block;width:303px;height:508px;z-index:6;">
<img src="images/unnamed.png" id="Image2" alt="">
</div>
</div>
<div class="col-2">
<div id="wb_Text2">
<span style="color:#000000;">&#1062;&#1077;&#1083;&#1080;:</span>
</div>
<div id="wb_Text6">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">- Упаковка предложения для франчайзи</span>
</div>
<div id="wb_Text4">
<span style="color:#000000;">Задачи:</span>
</div>
<div id="wb_Text3">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">- Упаковка продукта для прямого потребителя <br>- Разработка сайта</span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid5">
<div id="LayoutGrid5">
<div class="col-1">
<div id="wb_Text7">
<span style="color:#000000;"><strong>РЕШЕНИЕ</strong></span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid4">
<div id="LayoutGrid4">
<div class="col-1">
<div id="wb_Text8">
<span style="color:#000000;">Выявление потребностей</span>
</div>
<div id="wb_Text5">
<span style="color:#000000;">Для достижения максимального вовлечения в продукт необходимо выделить сегмент целевой аудитории с наибольшим потенциалом. <br>Для выявления максимальной целевой аудитории были использован:<br><br><strong>Ключевые запросы из систем:<br></strong> - Яндекс WordStat<br> - Google keyword tools<br>- SemRush (для выявления ключевых запросов конкурентов)<br><br><strong>Для выявления сопутствующих трендов </strong><br>- Google Trends<br><br><strong>Для выявления гендерных и возрастных параметров было использовано:</strong><br>- Top Mail ru<br>- Также открытые счетчики конкурентов&nbsp; <br><br><strong>На основании пользовательских данных был составлен</strong><br>- User map<br>- Разработан сценарий взаимодействия с пользователем <br></span>
</div>
<div id="wb_Text10">
<span style="color:#000000;"><strong>&#1059; &#1085;&#1072;&#1089; &#1087;&#1086;&#1083;&#1091;&#1095;&#1080;&#1083;&#1080;&#1089;&#1100; &#1089;&#1083;&#1077;&#1076;&#1091;&#1102;&#1097;&#1080;&#1077; &#1082;&#1072;&#1090;&#1077;&#1075;&#1086;&#1088;&#1080;&#1080; &#1087;&#1086;&#1083;&#1100;&#1079;&#1086;&#1074;&#1072;&#1090;&#1077;&#1083;&#1077;&#1081;</strong></span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid6">
<div id="LayoutGrid6">
<div class="col-1">
<div id="wb_Text13">
<span style="color:#000000;"><strong>Потребности</strong></span>
</div>
<div id="wb_Text14">
<span style="color:#000000;">Пользователи, которые ищут дешевые автомойки<br>Пользователи, которые ищут средства конкурентов<br>Пользователи, которые ищут автохимию</span>
</div>
</div>
<div class="col-2">
<div id="wb_Text19">
<span style="color:#000000;"><strong>Пол и возраст:</strong></span>
</div>
<div id="wb_Text20">
<span style="color:#000000;">Мужчины 25-34 – 90%<br>Мужчины другие возраста ~ 10%</span>
</div>
</div>
<div class="col-3">
<div id="wb_Image4" style="display:inline-block;width:100%;height:auto;z-index:19;">
<img src="images/r3auxwzf.bmp" id="Image4" alt="">
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid7">
<div id="LayoutGrid7">
<div class="col-1">
<div id="wb_Text11">
<span style="color:#000000;">Создание структуры сайта</span>
</div>
<div id="wb_Text12">
<span style="background-color:#FFFFFF;color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Данная структура была обусловлена тем, что на момент выхода на рынок количество витальных запросов, а также запросов, связанных с брендом, было недостаточно для запуска рекламных компаний по бренду в силу данного обстоятельства необходимо было исходить из «боли» пользователя с демонстрацией ее решения:<br><br>- Самая частая «боль» – объедение с клиентом на уровне потребностей<br>- Усиливаем «боль» и расширяем <br>- Решение боли <br>- Зацыкливание: «активация боли» - решение</span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid8">
<div id="LayoutGrid8">
<div class="col-1">
<div id="wb_Text9">
<span style="color:#000000;"><strong>&#1057;&#1094;&#1077;&#1085;&#1072;&#1088;&#1080;&#1081;:<br><br></strong>1. &#1069;&#1082;&#1088;&#1072;&#1085; &#1042;&#1099;&#1076;&#1077;&#1083;&#1103;&#1077;&#1084; &#1089;&#1072;&#1084;&#1091;&#1102; &#1095;&#1072;&#1089;&#1090;&#1091;&#1102; &#1087;&#1088;&#1086;&#1073;&#1083;&#1077;&#1084;&#1091; &#1082;&#1083;&#1080;&#1077;&#1085;&#1090;&#1072;<br><br>2. &#1069;&#1082;&#1088;&#1072;&#1085; &#1056;&#1072;&#1089;&#1096;&#1080;&#1088;&#1103;&#1077;&#1084; &#0171;&#1073;&#1086;&#1083;&#1100;&#0187; &#1082;&#1083;&#1080;&#1077;&#1085;&#1090;&#1072; - &#1090;&#1077;&#1084; &#1089;&#1072;&#1084;&#1099;&#1084; &#1091;&#1089;&#1080;&#1083;&#1080;&#1074;&#1072;&#1103; &#1082;&#1072;&#1082; &#1086;&#1089;&#1085;&#1086;&#1074;&#1085;&#1091;&#1102; &#0171;&#1073;&#1086;&#1083;&#1100;&#0187; &#1090;&#1072;&#1082; &#1091;&#1089;&#1080;&#1083;&#1080;&#1074;&#1072;&#1077;&#1084; &#1077;&#1077; &#1076;&#1088;&#1091;&#1075;&#1080;&#1084;&#1080;<br><br>3. &#1069;&#1082;&#1088;&#1072;&#1085; &#1056;&#1077;&#1096;&#1077;&#1085;&#1080;&#1077; &#0171;&#1073;&#1086;&#1083;&#1080;&#0187; - &#1085;&#1072;&#1075;&#1083;&#1103;&#1076;&#1085;&#1086; &#1076;&#1077;&#1084;&#1086;&#1085;&#1089;&#1090;&#1088;&#1080;&#1088;&#1091;&#1077;&#1084; &#1082;&#1072;&#1082; &#1084;&#1086;&#1078;&#1085;&#1086; &#1080;&#1079;&#1073;&#1072;&#1074;&#1080;&#1090;&#1089;&#1103; &#1086;&#1090; &#1073;&#1086;&#1083;&#1080; &#1089; &#1087;&#1088;&#1080;&#1084;&#1077;&#1088;&#1072;&#1084;&#1080;<br><br>4. &#1069;&#1082;&#1088;&#1072;&#1085; &#1047;&#1072;&#1082;&#1088;&#1099;&#1090;&#1080;&#1077; &#1085;&#1072; &#1089;&#1076;&#1077;&#1083;&#1082;&#1091; - &#1087;&#1088;&#1077;&#1076;&#1083;&#1086;&#1078;&#1077;&#1085;&#1080;&#1077; &#1082; &#1087;&#1086;&#1090;&#1088;&#1077;&#1073;&#1083;&#1077;&#1085;&#1080;&#1102; <br><br>5. &#1069;&#1082;&#1088;&#1072;&#1085; &#1055;&#1086;&#1103;&#1089;&#1085;&#1077;&#1085;&#1080;&#1077; &#1087;&#1088;&#1080;&#1084;&#1077;&#1085;&#1077;&#1085;&#1080;&#1103; &#8211; &#1076;&#1077;&#1084;&#1086;&#1085;&#1089;&#1090;&#1088;&#1080;&#1088;&#1091;&#1077;&#1084; &#1087;&#1088;&#1086;&#1089;&#1090;&#1086;&#1090;&#1091; &#1087;&#1088;&#1080;&#1084;&#1077;&#1085;&#1077;&#1085;&#1080;&#1103; &#1080; &#1072;&#1083;&#1075;&#1086;&#1088;&#1080;&#1090;&#1084; &#1080;&#1089;&#1087;&#1086;&#1083;&#1100;&#1079;&#1086;&#1074;&#1072;&#1085;&#1080;&#1103;<br><br>6. &#1069;&#1082;&#1088;&#1072;&#1085; &#1042;&#1086;&#1079;&#1086;&#1073;&#1085;&#1086;&#1074;&#1083;&#1103;&#1077;&#1084; &#1076;&#1077;&#1084;&#1086;&#1085;&#1089;&#1090;&#1088;&#1072;&#1094;&#1080;&#1102; &#1073;&#1086;&#1083;&#1080; &#8211; &#1054;&#1073;&#1098;&#1077;&#1076;&#1080;&#1085;&#1103;&#1077;&#1084; &#0171;&#1073;&#1086;&#1083;&#1100;&#0187; &#1082;&#1083;&#1080;&#1077;&#1085;&#1090;&#1072; &#1080; &#1087;&#1088;&#1077;&#1076;&#1086;&#1089;&#1090;&#1072;&#1074;&#1083;&#1103;&#1077;&#1084; &#1087;&#1091;&#1090;&#1100; &#1082; &#1087;&#1086;&#1090;&#1088;&#1077;&#1073;&#1083;&#1077;&#1085;&#1080;&#1102; &#0171;&#1080;&#1079;&#1073;&#1072;&#1074;&#1083;&#1077;&#1085;&#1080;&#1077; &#1086;&#1090; &#1073;&#1086;&#1083;&#1080;&#0187;<br><br>7. &#1069;&#1082;&#1088;&#1072;&#1085; &#1047;&#1072;&#1082;&#1088;&#1099;&#1090;&#1080;&#1077; &#1089;&#1086;&#1084;&#1085;&#1077;&#1085;&#1080;&#1080; &#8211; &#1044;&#1077;&#1084;&#1086;&#1085;&#1089;&#1090;&#1088;&#1072;&#1094;&#1080;&#1103; &#1076;&#1086;&#1082;&#1091;&#1084;&#1077;&#1085;&#1090;&#1086;&#1074; &#1080; &#1089;&#1077;&#1088;&#1090;&#1080;&#1092;&#1080;&#1082;&#1072;&#1090;&#1086;&#1074; &#1085;&#1072; &#1087;&#1088;&#1086;&#1076;&#1091;&#1082;&#1094;&#1080;&#1102;<br><br>8. &#1069;&#1082;&#1088;&#1072;&#1085; &#1044;&#1077;&#1084;&#1086;&#1085;&#1089;&#1090;&#1088;&#1072;&#1094;&#1080;&#1103; &#1088;&#1077;&#1096;&#1077;&#1085;&#1080;&#1077; &#0171;&#1073;&#1086;&#1083;&#1080;&#0187; - &#1044;&#1077;&#1084;&#1086;&#1085;&#1089;&#1090;&#1088;&#1080;&#1088;&#1091;&#1077;&#1084; &#0171;&#1073;&#1086;&#1083;&#1100;&#0187; &#1089; &#1077;&#1075;&#1086; &#1088;&#1077;&#1096;&#1077;&#1085;&#1080;&#1077;&#1084; &#1095;&#1077;&#1088;&#1077;&#1079; &#1087;&#1088;&#1080;&#1079;&#1084;&#1091; &#1086;&#1089;&#1085;&#1086;&#1074;&#1085;&#1086;&#1075;&#1086; &#1092;&#1072;&#1082;&#1090;&#1086;&#1088;&#1072; &#1074;&#1099;&#1073;&#1086;&#1088;&#1072; (&#1089;&#1090;&#1086;&#1080;&#1084;&#1086;&#1089;&#1090;&#1100; &#8211; &#1078;&#1077;&#1083;&#1072;&#1085;&#1080;&#1077; &#1089;&#1101;&#1082;&#1086;&#1085;&#1086;&#1084;&#1080;&#1090;&#1100;) <br><br>9. &#1069;&#1082;&#1088;&#1072;&#1085; &#1047;&#1072;&#1103;&#1074;&#1083;&#1077;&#1085;&#1080;&#1077; &#1086; &#1075;&#1072;&#1088;&#1072;&#1085;&#1090;&#1080;&#1080; &#8211; &#1091;&#1074;&#1077;&#1083;&#1080;&#1095;&#1080;&#1074;&#1072;&#1077;&#1084; &#1076;&#1086;&#1074;&#1077;&#1088;&#1080;&#1077;&#1084; &#1082; &#1087;&#1088;&#1086;&#1076;&#1091;&#1082;&#1090;&#1091;<br><br>10. &#1069;&#1082;&#1088;&#1072;&#1085; &#1056;&#1072;&#1079;&#1084;&#1077;&#1097;&#1077;&#1085;&#1080;&#1077; &#1082;&#1086;&#1085;&#1090;&#1072;&#1082;&#1090;&#1086;&#1074; &#8211; &#1076;&#1083;&#1103; &#1076;&#1077;&#1084;&#1086;&#1085;&#1089;&#1090;&#1088;&#1072;&#1094;&#1080;&#1080; &#1086;&#1090;&#1082;&#1088;&#1099;&#1090;&#1086;&#1089;&#1090;&#1080; &#1082;&#1086;&#1084;&#1087;&#1072;&#1085;&#1080;&#1080;<br></span>
</div>
<a id="Button1" href="https://www.figma.com/proto/sCpNfxr7HjZEI08CRQ6lxb/NANO-REFLECTOR?node-id=1%3A2&viewport=521%2C282%2C0.2662522792816162&scaling=scale-down-width" target="_blank" style="display:block;width: 100%;;height:38px;z-index:23;">Посмотреть сайт</a>
</div>
<div class="col-2">
<div id="wb_PhotoGallery1" style="display:inline-block;width:100%;z-index:24;">
<div id="PhotoGallery1">
   <div class="thumbnails">
      <figure class="thumbnail">
         <a href="images/Screen-1.jpg" data-rel="PhotoGallery1"><img alt="" src="images/Screen-1.jpg"></a>
      </figure>
      <figure class="thumbnail">
         <a href="images/Screen-2.jpg" data-rel="PhotoGallery1"><img alt="" src="images/Screen-2.jpg"></a>
      </figure>
      <div class="clearfix visible-col2"></div>
      <figure class="thumbnail">
         <a href="images/Screen-3.jpg" data-rel="PhotoGallery1"><img alt="" src="images/Screen-3.jpg"></a>
      </figure>
      <figure class="thumbnail">
         <a href="images/Screen-4.jpg" data-rel="PhotoGallery1"><img alt="" src="images/Screen-4.jpg"></a>
      </figure>
      <div class="clearfix visible-col2"></div>
      <figure class="thumbnail">
         <a href="images/Screen-5.jpg" data-rel="PhotoGallery1"><img alt="" src="images/Screen-5.jpg"></a>
      </figure>
      <figure class="thumbnail">
         <a href="images/Screen-6.jpg" data-rel="PhotoGallery1"><img alt="" src="images/Screen-6.jpg"></a>
      </figure>
      <div class="clearfix visible-col2"></div>
      <figure class="thumbnail">
         <a href="images/Screen-7.jpg" data-rel="PhotoGallery1"><img alt="" src="images/Screen-7.jpg"></a>
      </figure>
      <figure class="thumbnail">
         <a href="images/Screen-8.jpg" data-rel="PhotoGallery1"><img alt="" src="images/Screen-8.jpg"></a>
      </figure>
      <div class="clearfix visible-col2"></div>
      <figure class="thumbnail">
         <a href="images/Screen-9.jpg" data-rel="PhotoGallery1"><img alt="" src="images/Screen-9.jpg"></a>
      </figure>
      <figure class="thumbnail">
         <a href="images/Screen-10.jpg" data-rel="PhotoGallery1"><img alt="" src="images/Screen-10.jpg"></a>
      </figure>
      <div class="clearfix visible-col2"></div>
   </div>
</div>
</div>
</div>
</div>
</div>

<div id="wb_Kontakt">
<div id="Kontakt">
<div class="row">
<div class="col-1">
<div id="wb_Footer-contact">
<form name="Footer_contact" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="Footer-contact">
<input type="hidden" name="formid" value="footer-contact">
<div class="col-1">
<div id="wb_Text33">
<span style="color:#000000;font-family:Ubuntu;font-size:24px;"><strong>Вы хотите связаться со мной?</strong></span>
</div>
<div id="wb_Text32">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;letter-spacing:2.07px;line-height:23px;">Вам перезвонить?</span>
</div>
<input type="text" id="Editbox1" style="display:block;width: 100%;height:40px;z-index:27;" name="Имя" value="" spellcheck="false" placeholder="&#1042;&#1072;&#1096;&#1077; &#1080;&#1084;&#1103;">
<div id="wb_phone" style="display:inline-block;width:100%;z-index:28;">
<input type="text" id="phone" style="" name="Номер телефона" value="" spellcheck="false" placeholder="&#1053;&#1086;&#1084;&#1077;&#1088; &#1090;&#1077;&#1083;&#1077;&#1092;&#1086;&#1085;&#1072;" class="phone">
</div>
<a id="Button4" href="" style="display:block;width: 100%;;height:38px;z-index:29;">Перезвоните мне</a>
<a id="Button10" href="https://wa.me/79600562977?text=%D0%9F%D1%80%D0%B8%D0%B2%D0%B5%D1%82%3A))%20%D0%9F%D0%B8%D1%88%D1%83%20%D1%82%D0%B5%D0%B1%D0%B5%20%D1%81%20%D1%82%D0%B2%D0%BE%D0%B5%D0%B3%D0%BE%20%D1%81%D0%B0%D0%B9%D1%82%D0%B0" style="display:block;width: 100%;;height:34px;z-index:30;">Написать в whatsapp</a>
<a id="Button12" href="https://t.me/RustemHv" style="display:block;width: 100%;;height:34px;z-index:31;">Написать в telegram</a>
</div>
<div class="col-2">
<div id="wb_Text46">
<span style="color:#000000;font-family:Ubuntu;font-size:24px;letter-spacing:2.07px;line-height:31px;"><strong>Контакты </strong></span>
</div>
<div id="wb_Image5" style="display:inline-block;width:100%;height:auto;z-index:33;">
<img src="images/map-min.png" id="Image5" alt="">
</div>
<div id="wb_Text34">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;letter-spacing:2.07px;line-height:23px;">Тел: <a href="tel:+7(960) 056 - 29 - 77" class="Link-black">+7(960) 056 - 29 - 77</a></span>
</div>
<div id="wb_Text35">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;letter-spacing:2.07px;line-height:23px;">E-mail: <a href="mailto:rustem.marketing@yandex.com" class="Link-black">rustem.marketing@yandex.com</a></span>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</body>
</html>