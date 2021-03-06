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
   $message = 'Данные - Менеджер по маркетингу, footer';
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
<title>Менеджер по маркетингу | Хуснутдинов Рустем</title>
<meta name="author" content="Хуснутдинов Рустем">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
<link href="favicon-32x32.png" rel="icon" type="image/png">
<link href="apple-touch-icon.png" rel="icon" type="image/png">
<link href="android-chrome-192x192.png" rel="apple-touch-icon">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500&subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400&subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Arial" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Calibri" rel="stylesheet">
<link href="css/Rustem-porfolio.css" rel="stylesheet">
<link href="css/marketing.css" rel="stylesheet">
<script src="jquery-1.12.4.min.js"></script>
<script src="transition.min.js"></script>
<script src="collapse.min.js"></script>
<script src="dropdown.min.js"></script>
<script src="wb.validation.min.js"></script>
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
<div id="wb_LayoutGrid3">
<div id="LayoutGrid3">
<div class="row">
<div class="col-1">
<div id="wb_ThemeableMenu1" style="display:inline-block;width:100%;z-index:1004;">
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
<a href="./index.html">Главная</a>
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">Навыки работы<b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="#online-marketing">Online маркетинг</a></li>
<li><a href="#offline-marketing">Offline маркетинг</a></li>
<li><a href="#marketing-analytics">Маркетинговая аналитика</a></li>
<li><a href="#over-skils">Общие навыки работа</a></li>
</ul>
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
<div id="wb_Text89">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:48px;letter-spacing:2.07px;">&lt;b&gt;<strong>Hello world</strong>&lt;/b&gt;</span>
</div>
<div id="wb_Text90">
<span style="color:#000000;font-family:Ubuntu;font-size:27px;letter-spacing:2.07px;line-height:47px;"><strong>Я Менеджер </strong>по маркетингу <strong><br></strong></span>
</div>
<div id="wb_Text91">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;letter-spacing:2.07px;">Здравствуйте, </span><span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">меня зовут Хуснутдинов Рустем<br>Это страница с описанием части моих навыков. <br>Буду очень рад с вами поработать.</span>
</div>
</div>
<div class="col-2">
<div id="wb_Image1" style="display:inline-block;width:100%;height:auto;z-index:3;">
<img src="images/Rus-team.png" id="Image1" alt="">
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="wb_online-marketing">
<div id="online-marketing">
<div class="row">
<div class="col-1">
<div id="wb_Text8">
<span style="color:#000000;"><strong>ONLine marketing</strong><br>Навыки работы</span>
</div>
</div>
<div class="col-2">
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid6">
<div id="LayoutGrid6">
<div class="col-1">
<div id="wb_Text14">
<span style="color:#000000;"><strong>Рекламный трафик - источники коммерческого происхождения</strong></span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid4">
<div id="LayoutGrid4">
<div class="col-1">
<div id="wb_Text5">
<span style="color:#000000;"><strong>&#1053;&#1072;&#1089;&#1090;&#1088;&#1086;&#1081;&#1082;&#1072; &#1080; &#1086;&#1087;&#1090;&#1080;&#1084;&#1080;&#1079;&#1072;&#1094;&#1080;&#1103; &#1087;&#1086;&#1080;&#1089;&#1082;&#1086;&#1074;&#1086;-&#1082;&#1086;&#1085;&#1090;&#1077;&#1082;&#1089;&#1090;&#1085;&#1086;&#1081; &#1080; &#1073;&#1072;&#1085;&#1085;&#1077;&#1088;&#1085;&#1086;&#1081; &#1088;&#1077;&#1082;&#1083;&#1072;&#1084;&#1099;:</strong></span>
</div>
<div id="wb_Text7">
<span style="color:#000000;">Google ADS<br>Yandex Direct<br>Bing ADS<br>&#1048; &#1087;&#1088;&#1086;&#1095;&#1080;&#1077; &#1089;&#1080;&#1089;&#1090;&#1077;&#1084;&#1099;</span>
</div>
</div>
<div class="col-2">
<div id="wb_Text18">
<span style="color:#000000;"><strong>Настройка и оптимизация рекламы в маркетплейс системах:</strong></span>
</div>
<div id="wb_Text19">
<span style="color:#000000;">Яндекс маркет<br>Авито<br>И прочие системы</span>
</div>
</div>
<div class="col-3">
<div id="wb_Text20">
<span style="color:#000000;"><strong>Настройка и оптимизация: таргетированной рекламы:</strong></span>
</div>
<div id="wb_Text21">
<span style="color:#000000;">VK ADS<br>My Target (vk.com, ok.ru, mail.ru и партнерские сети)<br>Facebook ADS (facebook, instagram и партнерские сети)<br>Verizon media (yahoo и медиа ресурсы verizon)<br>И прочие системы</span>
</div>
</div>
<div class="col-4">
<div id="wb_Text44">
<span style="color:#000000;"><strong>Настройка и оптимизация<br>Desktop push, Mobile push:</strong></span>
</div>
<div id="wb_Text45">
<span style="color:#000000;">Mega.push<br>killtarget<br>adroll<br>PropellerAds<br>&#1048; &#1087;&#1088;&#1086;&#1095;&#1080;&#1077; &#1089;ети </span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid9">
<div id="LayoutGrid9">
<div class="col-1">
<div id="wb_Text42">
<span style="color:#000000;font-family:Ubuntu;font-size:16px;"><strong>Настройка и оптимизация: Тизерной рекламы, Мотивационного трафика:</strong></span>
</div>
<div id="wb_Text43">
<ul style="font-size:13px;line-height:16.5px;color:#000000;list-style-type:disc;">
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;"><span style="color:#000000;">Kadam</span>
</li>
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;">Mgid
</li>
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;">Exoclick
</li>
</ul>
<p style="font-size:13px;line-height:16.5px;color:#000000;">И&nbsp;прочие&nbsp;сети&nbsp;</p>
</div>
</div>
<div class="col-2">
<div id="wb_Text46">
<p style="font-size:16px;line-height:19.5px;font-weight:bold;color:#000000;">Настройка&nbsp;и&nbsp;оптимизация: in-app рекламы:</p>
</div>
<div id="wb_Text47">
<ul style="font-size:13px;line-height:16.5px;color:#000000;list-style-type:disc;">
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;"><span style="color:#000000;">Через</span><span style="color:#000000;">&nbsp;</span><span style="color:#000000;">рекламные</span><span style="color:#000000;">&nbsp;</span><span style="color:#000000;">сети</span>
</li>
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;">Через&nbsp;производителей
</li>
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;">Через&nbsp;приложения cloaking методом&nbsp;замены&nbsp;контента
</li>
</ul>
</div>
</div>
<div class="col-3">
</div>
<div class="col-4">
</div>
</div>
</div>
<div id="wb_LayoutGrid5">
<div id="LayoutGrid5">
<div class="row">
<div class="col-1">
<div id="wb_Text10">
<span style="color:#000000;font-family:Ubuntu;font-size:19px;"><strong>PR технологии:</strong></span>
</div>
<div id="wb_Text11">
<span style="color:#000000;"><strong>&#1053;&#1072;&#1090;&#1080;&#1074;&#1085;&#1072;&#1103; &#1088;&#1077;&#1082;&#1083;&#1072;&#1084;&#1072; </strong>&#8211; &#1101;&#1090;&#1086; &#1074;&#1080;&#1076; &#1088;&#1077;&#1082;&#1083;&#1072;&#1084;&#1099;, &#1082;&#1086;&#1075;&#1076;&#1072; &#1087;&#1086;&#1083;&#1100;&#1079;&#1086;&#1074;&#1072;&#1090;&#1077;&#1083;&#1100; &#1085;&#1077; &#1076;&#1086;&#1075;&#1072;&#1076;&#1099;&#1074;&#1072;&#1077;&#1090;&#1089;&#1103;, &#1095;&#1090;&#1086; &#1087;&#1077;&#1088;&#1077;&#1076; &#1085;&#1080;&#1084; &#1088;&#1077;&#1082;&#1083;&#1072;&#1084;&#1072;. &#1069;&#1090;&#1086; &#1090;&#1077;&#1093;&#1085;&#1086;&#1083;&#1086;&#1075;&#1080;&#1103; &#1091;&#1087;&#1088;&#1072;&#1074;&#1083;&#1077;&#1085;&#1080;&#1103; &#1084;&#1072;&#1089;&#1089;&#1086;&#1074;&#1099;&#1084; &#1089;&#1086;&#1079;&#1085;&#1072;&#1085;&#1080;&#1077;&#1084;, &#1082;&#1086;&#1075;&#1076;&#1072; &#1088;&#1077;&#1082;&#1083;&#1072;&#1084;&#1085;&#1099;&#1081; &#1087;&#1086;&#1089;&#1099;&#1083; &#1074;&#1089;&#1090;&#1088;&#1072;&#1080;&#1074;&#1072;&#1077;&#1090;&#1089;&#1103; &#1074; &#1077;&#1089;&#1090;&#1077;&#1089;&#1090;&#1074;&#1077;&#1085;&#1085;&#1099;&#1081; &#1082;&#1086;&#1085;&#1090;&#1077;&#1085;&#1090; &#8211; &#1074; &#1092;&#1080;&#1083;&#1100;&#1084;&#1099;, &#1088;&#1072;&#1076;&#1080;&#1086;, &#1074; &#1089;&#1090;&#1072;&#1090;&#1100;&#1080; &#1074; &#1048;&#1085;&#1090;&#1077;&#1088;&#1085;&#1077;&#1090;&#1077;, &#1082;&#1083;&#1080;&#1087;&#1099;, &#1084;&#1091;&#1083;&#1100;&#1090;&#1092;&#1080;&#1083;&#1100;&#1084;&#1099;, &#1087;&#1077;&#1089;&#1085;&#1080; &#1080; &#1090;.&#1076;.</span>
</div>
<div id="wb_Text9">
<span style="color:#000000;"><strong>Online Reputation Management – </strong>метод глобального управления репутацией компании в интернете. Цель ORM — формирование положительной репутации бренда с помощью использования разных маркетинговых стратегий, в том числе SERM. Это комплексный подход, который прорабатывает имидж на разных ресурсах: </span>
</div>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid7">
<div id="LayoutGrid7">
<div class="col-1">
<div id="wb_Text22">
<span style="color:#000000;"><strong>Ведение нативных рекламных кампаний:</strong></span>
</div>
<div id="wb_Text23">
<span style="color:#000000;">Блогерами / Инфлюенсерами<br>Через посевные публикации в социальных медиа, а также мессенджерах<br>Статьями на сайтах - нативная реклама<br>Статьями в печатных изданиях - нативная реклама</span>
</div>
</div>
<div class="col-2">
<div id="wb_Text1">
<span style="color:#000000;"><strong>SERM - проведение работ с выдачей:</strong></span>
</div>
<div id="wb_Text6">
<span style="color:#000000;">Продвижение сайтов с позитивными отзывами в поисковой системе<br>Вытеснение сайтов с негативными отзывами<br>Создание сети сателлитов с отзывами<br>Продвижение групп посадочных страниц с отзывами <br></span>
</div>
</div>
<div class="col-3">
<div id="wb_Text17">
<span style="color:#000000;"><strong>ORM - управление репутацией:</strong></span>
</div>
<div id="wb_Text24">
<span style="color:#000000;">Работа с лидерами мнений <br>Работа с агрегаторами отзывов<br>Вытеснение негативных отзывов в агрегаторах<br>Работа с рейтингами в агрегаторах приложений / компаний<br>Создание позитивных коннотаций о бренде</span>
</div>
</div>
<div class="col-4">
</div>
</div>
</div>
<div id="wb_LayoutGrid8">
<div id="LayoutGrid8">
<div class="row">
<div class="col-1">
<div id="wb_Text13">
<span style="color:#000000;"><strong>Оптимизация сайтов в поисковых системах - SEO:</strong></span>
</div>
<div id="wb_Text12">
<span style="color:#000000;"><strong>SEO </strong>&#1103;&#1074;&#1083;&#1103;&#1077;&#1090;&#1089;&#1103; &#1072;&#1073;&#1073;&#1088;&#1077;&#1074;&#1080;&#1072;&#1090;&#1091;&#1088;&#1086;&#1081; &#1092;&#1088;&#1072;&#1079;&#1099; Search Engine Optimization &#1080; &#1087;&#1086;&#1076;&#1088;&#1072;&#1079;&#1091;&#1084;&#1077;&#1074;&#1072;&#1077;&#1090; &#1082;&#1086;&#1084;&#1087;&#1083;&#1077;&#1082;&#1089; &#1084;&#1077;&#1088;&#1086;&#1087;&#1088;&#1080;&#1103;&#1090;&#1080;&#1081;, &#1085;&#1072;&#1087;&#1088;&#1072;&#1074;&#1083;&#1077;&#1085;&#1085;&#1099;&#1093; &#1085;&#1072; &#1086;&#1087;&#1090;&#1080;&#1084;&#1080;&#1079;&#1072;&#1094;&#1080;&#1102; &#1074;&#1099;&#1076;&#1072;&#1095;&#1080; &#1089;&#1072;&#1081;&#1090;&#1072; &#1087;&#1086;&#1080;&#1089;&#1082;&#1086;&#1074;&#1099;&#1084;&#1080; &#1089;&#1080;&#1089;&#1090;&#1077;&#1084;&#1072;&#1084;&#1080; &#1087;&#1086; &#1094;&#1077;&#1083;&#1077;&#1074;&#1099;&#1084; &#1079;&#1072;&#1087;&#1088;&#1086;&#1089;&#1072;&#1084;.</span>
</div>
<div id="wb_Text16">
<span style="color:#000000;"><strong>ASO</strong>-оптимизация (от англ. App Store Optimization) — это процесс улучшения видимости вашего мобильного приложения в магазинах Google Play и App Store с помощью специальных инструментов. Один из видов мобильного маркетинга, важная составляющая стратегии по продвижению.</span>
</div>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid11">
<div id="LayoutGrid11">
<div class="col-1">
<div id="wb_Text40">
<span style="color:#000000;"><strong>Оптимизация сайтов в поисковых системах - SEO:</strong></span>
</div>
<div id="wb_Text41">
<span style="color:#000000;">Продвижение основного сайта компании<br>Работа с репутацией в сети компании<br>Продвижение сторонних страниц ресурсов<br>Вытеснение нежелательных сайтов из выдачи<br>cloaking сайты - методом замены контента<br>Разработка и продвижение doorway сайтов</span>
</div>
</div>
<div class="col-2">
<div id="wb_Text52">
<span style="color:#000000;font-family:Ubuntu;font-size:16px;"><strong>Навыки создания создания сетей:</strong></span>
</div>
<div id="wb_Text53">
<span style="color:#000000;">Сайтов сателлитов в поисковых системах (SEO оптимизация)<br>Бот сетей в социальных сетях (vk.com, ok.ru)</span>
</div>
</div>
<div class="col-3">
<div id="wb_Text50">
<span style="color:#000000;"><strong>Знания и навыки спам систем выдачи:</strong></span>
</div>
<div id="wb_Text51">
<span style="color:#000000;">Поисковые системы (Yandex, Google) <br>Социальных сетей (Vk.com - группы, пользователи, медиа контент)<br>Форумов, систем комментариев, досок объявлений<br>Гео сервисов (2gis, Google мой бизнес, Yandex карты) </span>
</div>
</div>
<div class="col-4">
<div id="wb_Text85">
<span style="color:#000000;"><strong>Оптимизация приложений в поисковой выдаче - ASO:</strong></span>
</div>
<div id="wb_Text86">
<span style="color:#000000;">Создание сателитов<br>Оптимизация под запросы пользователей</span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid12">
<div id="LayoutGrid12">
<div class="row">
<div class="col-1">
<div id="wb_Text15">
<span style="color:#000000;font-family:Ubuntu;font-size:19px;"><strong>Партнерский маркетинг:</strong></span>
</div>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid13">
<div id="LayoutGrid13">
<div class="col-1">
<div id="wb_Text54">
<span style="color:#000000;"><strong>Навыки создание партнерской сети:</strong></span>
</div>
<div id="wb_Text55">
<span style="color:#000000;">Создание партнерской сети<br>Привлечение партнеров<br>Мотивация партнерской сети</span>
</div>
</div>
<div class="col-2">
</div>
<div class="col-3">
</div>
<div class="col-4">
</div>
</div>
</div>
<div id="wb_LayoutGrid14">
<div id="LayoutGrid14">
<div class="row">
<div class="col-1">
<div id="wb_Text25">
<span style="color:#000000;font-family:Ubuntu;font-size:19px;line-height:23px;"><strong>Мессенджер маркетинг:</strong></span>
</div>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid15">
<div id="LayoutGrid15">
<div class="col-1">
<div id="wb_Text26">
<span style="color:#000000;"><strong>Навыки работа прямых каналах:</strong></span>
</div>
<div id="wb_Text29">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">SMS
</li>
<li style="margin:0 0 0 24px;">E-mail
</li>
<li style="margin:0 0 0 24px;">Messenger (WhatsApp, Telegram, Viber)
</li>
</ul>
</div>
</div>
<div class="col-2">
<div id="wb_Text28">
<span style="color:#000000;"><strong>Создание автоматизированных систем продаж:</strong></span>
</div>
<div id="wb_Text30">
<p>Создание&nbsp;автоматизированных каналов продаж на основе каналов коммуникаций через </p>
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Мессенджеры
</li>
<li style="margin:0 0 0 24px;">E-mail
</li>
</ul>
</div>
</div>
<div class="col-3">
</div>
<div class="col-4">
</div>
</div>
</div>
<div id="wb_offline-marketing">
<div id="offline-marketing">
<div class="row">
<div class="col-1">
<div id="wb_Text49">
<span style="color:#000000;"><strong>OFFLine marketing</strong><br>Навыки работы</span>
</div>
</div>
<div class="col-2">
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid19">
<div id="LayoutGrid19">
<div class="col-1">
<div id="wb_Text56">
<span style="color:#000000;"><strong>Рекламный трафик - источники коммерческого происхождения</strong></span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid21">
<div id="LayoutGrid21">
<div class="col-1">
<div id="wb_Text59">
<span style="color:#000000;"><strong>Мерчендайзинг:</strong></span>
</div>
<div id="wb_Text60">
<ul style="color:#000000;list-style-type:disc;">
<li style="margin:0 0 0 24px;"><span style="color:#000000;">Оптимизация путей потребителей на основе данных о покупках</span>
</li>
<li style="margin:0 0 0 24px;">Оптимизация расположения товаровИ&nbsp;прочие&nbsp;системы
</li>
<li style="margin:0 0 0 24px;">и так далее
</li>
</ul>
</div>
</div>
<div class="col-2">
<div id="wb_Text61">
<span style="color:#000000;"><strong>BTL -&nbsp; offline:</strong></span>
</div>
<div id="wb_Text62">
<ul style="color:#000000;list-style-type:disc;">
<li style="margin:0 0 0 24px;"><span style="color:#000000;">Создание розыгрышей, викторин – off line формата</span>
</li>
<li style="margin:0 0 0 24px;">Распространение промо-материалов в точках реализации продуктов и услуг или по пути следования в сопутствующих точках: Раздача,установка 
</li>
</ul>
<p style="color:#000000;">И т.д.</p>
</div>
</div>
<div class="col-3">
<div id="wb_Text63">
<span style="color:#000000;"><strong>ATL -&nbsp; offline:</strong></span>
</div>
<div id="wb_Text64">
<ul style="color:#000000;list-style-type:disc;">
<li style="margin:0 0 0 24px;"><span style="color:#000000;">Радиореклама</span>
</li>
<li style="margin:0 0 0 24px;">ТВ реклама
</li>
<li style="margin:0 0 0 24px;">Реклама в печатных изданиях
</li>
<li style="margin:0 0 0 24px;">Реклама на транспорте
</li>
<li style="margin:0 0 0 24px;">Наружная реклама 
</li>
</ul>
<p style="color:#000000;">И т.д.</p>
</div>
</div>
<div class="col-4">
</div>
</div>
</div>
<div id="wb_marketing-analytics">
<div id="marketing-analytics">
<div class="row">
<div class="col-1">
<div id="wb_Text3">
<span style="color:#000000;"><strong>Маркетинговая аналитика</strong><br>Навыки работы</span>
</div>
</div>
<div class="col-2">
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid17">
<div id="LayoutGrid17">
<div class="col-1">
<div id="wb_Text32">
<span style="color:#000000;"><strong>Маркетинговая аналитика</strong></span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid16">
<div id="LayoutGrid16">
<div class="col-1">
<div id="wb_Text27">
<span style="color:#000000;"><strong>Web аналитика:</strong></span>
</div>
<div id="wb_Text31">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Google analytics
</li>
<li style="margin:0 0 0 24px;">Yandex Metrika
</li>
<li style="margin:0 0 0 24px;">live intrnet
</li>
<li style="margin:0 0 0 24px;">Top mail
</li>
<li style="margin:0 0 0 24px;">OWA
</li>
</ul>
</div>
</div>
<div class="col-2">
<div id="wb_Text33">
<span style="color:#000000;"><strong>APP аналитика:</strong></span>
</div>
<div id="wb_Text34">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Google analytics
</li>
<li style="margin:0 0 0 24px;">APPMetrika Yandex 
</li>
<li style="margin:0 0 0 24px;">Amplitude&nbsp;
</li>
</ul>
</div>
</div>
<div class="col-3">
<div id="wb_Text35">
<span style="color:#000000;"><strong>Анализ целевой аудиории</strong></span>
</div>
<div id="wb_Text36">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Глубинное интервью
</li>
<li style="margin:0 0 0 24px;">CustDev
</li>
<li style="margin:0 0 0 24px;">Split testing
</li>
<li style="margin:0 0 0 24px;">4Р - 4С&nbsp;анализ
</li>
<li style="margin:0 0 0 24px;">Поисковый анализ данных пользователей
</li>
<li style="margin:0 0 0 24px;">Анализ связей и интересов из данных социальные сети
</li>
<li style="margin:0 0 0 24px;">и многое другое
</li>
</ul>
</div>
</div>
<div class="col-4">
<div id="wb_Text39">
<span style="color:#000000;"><strong>Источники трафика</strong></span>
</div>
<div id="wb_Text48">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Анализ каналов дистрибуции
</li>
<li style="margin:0 0 0 24px;">Построение воронок продаж 
</li>
<li style="margin:0 0 0 24px;">Анализ эффективности каналов трафика в долгосрочной перспективе
</li>
<li style="margin:0 0 0 24px;">Анализ поведения посетителей в разрезе источника трафика
</li>
<li style="margin:0 0 0 24px;">и многое другое
</li>
</ul>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid20">
<div id="LayoutGrid20">
<div class="col-1">
<div id="wb_Text57">
<span style="color:#000000;"><strong>Продуктовая аналитика</strong></span>
</div>
<div id="wb_Text58">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Unit экономика
</li>
<li style="margin:0 0 0 24px;">Моделирование каналов дистрибуции
</li>
<li style="margin:0 0 0 24px;">Юзабилити аудит 
</li>
<li style="margin:0 0 0 24px;">Анализ пользовательских путей внутри продукта
</li>
<li style="margin:0 0 0 24px;">Возвращаемость пользователя&nbsp; в продукт
</li>
<li style="margin:0 0 0 24px;">Кагорный анализ&nbsp; пользователей
</li>
<li style="margin:0 0 0 24px;">LTV
</li>
<li style="margin:0 0 0 24px;">и так далее
</li>
</ul>
</div>
</div>
<div class="col-2">
<div id="wb_Text37">
<span style="color:#000000;"><strong>Базовый стат анализ</strong></span>
</div>
<div id="wb_Text38">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Сравнительный анализ
</li>
<li style="margin:0 0 0 24px;">Выявление отклонения
</li>
<li style="margin:0 0 0 24px;">Выявление частотного признака
</li>
<li style="margin:0 0 0 24px;">Нормализация данных
</li>
<li style="margin:0 0 0 24px;">Т преобразование 
</li>
<li style="margin:0 0 0 24px;">Корреляционный анализ
</li>
<li style="margin:0 0 0 24px;">Корреляционный анализ Крамара
</li>
</ul>
</div>
</div>
<div class="col-3">
</div>
<div class="col-4">
</div>
</div>
</div>
<div id="wb_over-skils">
<div id="over-skils">
<div class="row">
<div class="col-1">
<div id="wb_Text65">
<span style="color:#000000;"><strong>Общие навыки работа</strong></span>
</div>
</div>
<div class="col-2">
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid23">
<div id="LayoutGrid23">
<div class="col-1">
<div id="wb_Text66">
<span style="color:#000000;"><strong>Модели управления </strong></span>
</div>
<div id="wb_Text67">
<p>Проектная модель управления</p>
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Waterfall (прим.)
</li>
</ul>
<p>&nbsp;</p>
<p>Продуктовая модель управления</p>
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Scrum (AGILE)
</li>
<li style="margin:0 0 0 24px;">Kanban (AGILE)
</li>
</ul>
</div>
</div>
<div class="col-2">
<div id="wb_Text68">
<span style="color:#000000;"><strong>Обучение</strong></span>
</div>
<div id="wb_Text69">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Обучение сотрудников
</li>
<li style="margin:0 0 0 24px;">Самообучение
</li>
</ul>
</div>
</div>
<div class="col-3">
<div id="wb_Text70">
<span style="color:#000000;"><strong>Бизнес аналитика </strong></span>
</div>
<div id="wb_Text71">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Моделирование бизнес-процессов 
</li>
<li style="margin:0 0 0 24px;">Моделирование каналов дистрибуции
</li>
<li style="margin:0 0 0 24px;">Написание ТЗ
</li>
<li style="margin:0 0 0 24px;">Декомпозирование требований
</li>
<li style="margin:0 0 0 24px;">Построение схем работы функционала или требований
</li>
</ul>
</div>
</div>
<div class="col-4">
<div id="wb_Text72">
<span style="color:#000000;"><strong>Тестирование</strong></span>
</div>
<div id="wb_Text73">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Составление тест-кейсов
</li>
<li style="margin:0 0 0 24px;">Составление чек-листов
</li>
<li style="margin:0 0 0 24px;">Навыки ручного тестирования
</li>
</ul>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid24">
<div id="LayoutGrid24">
<div class="col-1">
<div id="wb_Text74">
<span style="color:#000000;"><strong>Проектирование</strong></span>
</div>
<div id="wb_Text75">
<ul style="list-style-type:disc;">
<li style="margin:0 0 0 24px;">Прототипирование сайтов
</li>
<li style="margin:0 0 0 24px;">Прототипирование мобильных приложений
</li>
<li style="margin:0 0 0 24px;">Проектирование Каналов коммуникации и каналов дистрибуций
</li>
<li style="margin:0 0 0 24px;">И т.д.
</li>
</ul>
</div>
</div>
<div class="col-2">
<div id="wb_Text82">
<span style="color:#000000;"><strong>Языки </strong></span>
</div>
<div id="wb_Text81">
<p style="font-size:13px;line-height:16.5px;color:#000000;">Редактирование и получение данных на языках:</p>
<p style="font-size:13px;line-height:16.5px;">&nbsp;</p>
<ul style="font-size:13px;line-height:16.5px;color:#000000;list-style-type:disc;">
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;">Html
</li>
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;">CSS
</li>
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;">JavaScript
</li>
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;">SQL
</li>
</ul>
</div>
</div>
<div class="col-3">
<div id="wb_Text84">
<span style="color:#000000;"><strong>Редактирование и создание видео</strong></span>
</div>
<div id="wb_Text83">
<ul style="font-size:13px;line-height:16.5px;color:#000000;list-style-type:disc;">
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;"><span style="color:#000000;">After Effects</span>
</li>
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;">SonyVegas
</li>
<li style="margin:0 0 0 24px;"><p style="font-size:13px;line-height:16.5px;">Premiere Pro
</li>
</ul>
</div>
</div>
<div class="col-4">
<div id="wb_Text87">
<span style="color:#000000;font-family:Ubuntu;font-size:16px;"><strong>Графические редакторы</strong></span>
</div>
<div id="wb_Text88">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:13px;">Редактирование и создание макетов, графики, прототипов:<br><br>- Photoshop<br>- Figma<br>- Illustrator<br>- CorelDrow</span>
</div>
</div>
</div>
</div>
<div id="wb_Kontakt">
<div id="Kontakt">
<div class="row">
<div class="col-1">
<div id="wb_Footer-contact">
<form name="Footer-contact" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="Footer-contact">
<input type="hidden" name="formid" value="footer-contact">
<div class="col-1">
<div id="wb_Text78">
<span style="color:#000000;font-family:Ubuntu;font-size:24px;"><strong>Вы хотите связаться со мной?</strong></span>
</div>
<div id="wb_Text76">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;letter-spacing:2.07px;line-height:23px;">Вам перезвонить?</span>
</div>
<input type="text" id="Editbox1" style="display:block;width: 100%;height:40px;z-index:93;" name="Имя" value="" spellcheck="false" placeholder="&#1042;&#1072;&#1096;&#1077; &#1080;&#1084;&#1103;">
<div id="wb_phone" style="display:inline-block;width:100%;z-index:94;">
<input type="text" id="phone" style="" name="Номер телефона" value="" spellcheck="false" placeholder="&#1053;&#1086;&#1084;&#1077;&#1088; &#1090;&#1077;&#1083;&#1077;&#1092;&#1086;&#1085;&#1072;" class="phone">
</div>
<a id="Button4" href="" style="display:block;width: 100%;;height:38px;z-index:95;">Перезвоните мне</a>
<a id="Button10" href="https://wa.me/79600562977?text=%D0%9F%D1%80%D0%B8%D0%B2%D0%B5%D1%82%3A))%20%D0%9F%D0%B8%D1%88%D1%83%20%D1%82%D0%B5%D0%B1%D0%B5%20%D1%81%20%D1%82%D0%B2%D0%BE%D0%B5%D0%B3%D0%BE%20%D1%81%D0%B0%D0%B9%D1%82%D0%B0" style="display:block;width: 100%;;height:34px;z-index:96;">Написать в whatsapp</a>
<a id="Button12" href="https://t.me/RustemHv" style="display:block;width: 100%;;height:34px;z-index:97;">Написать в telegram</a>
</div>
<div class="col-2">
<div id="wb_Text77">
<span style="color:#000000;font-family:Ubuntu;font-size:24px;letter-spacing:2.07px;line-height:31px;"><strong>Контакты </strong></span>
</div>
<div id="wb_Image5" style="display:inline-block;width:100%;height:auto;z-index:99;">
<img src="images/map-min.png" id="Image5" alt="">
</div>
<div id="wb_Text79">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;letter-spacing:2.07px;line-height:23px;">Тел: <a href="tel:+7(960) 056 - 29 - 77" class="Link-black">+7(960) 056 - 29 - 77</a></span>
</div>
<div id="wb_Text80">
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