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
   $message = 'Данные - Кейс "Палитра дома",  footer';
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
<title>Кейс: "Палитра дома"</title>
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
<link href="css/palitra-doma.css" rel="stylesheet">
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
function displaylightbox(url, options)
{
   options.padding = 0;
   options.autoScale = true;
   options.href = url;
   options.type = 'iframe';
   $.fancybox(options);
}
</script>
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
   $("a[data-rel='lightbox-fancybox']").attr('rel', 'lightbox-fancybox');
   $("a[data-rel^='lightbox-fancybox']").fancybox({});
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
</script><!-- Insert Google Analytics code here -->
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
<img src="images/logo doma.png" id="Image1" alt="">
</div>
</div>
<div class="col-2">
<div id="wb_Text1">
<span style="color:#FFFFFF;"><strong>Городская курьерская служба была основана в 2014 году для обеспечения услуг по доставке рекламных материалов, а также печатной продукции до потребителей&nbsp; различных организаций.</strong></span>
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
<div id="wb_Image3" style="display:inline-block;width:100%;height:auto;z-index:6;">
<img src="images/tandir-min.png" id="Image3" alt="">
</div>
</div>
<div class="col-2">
<div id="wb_Text2">
<span style="color:#000000;">&#1062;&#1077;&#1083;&#1080;:</span>
</div>
<div id="wb_Text6">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">- Оптимизировать доходность магазина </span>
</div>
<div id="wb_Text4">
<span style="color:#000000;">Задачи:</span>
</div>
<div id="wb_Text3">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">- Определить распределение трафика по товарам<br>- Вынести товары локомотивы в отдельный сайты<br>- Распределить рекламный трафик</span>
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
<span style="color:#000000;">Определение тренда товаров</span>
</div>
<div id="wb_Text5">
<span style="color:#000000;"><strong>Для выявления популярных сделали расчет по формуле на каждый товар:<br></strong>1. Количество заказов (определённого товара) / количество обращений<br>2. Выявляем маржинальность с каждого товара <br>3. Тенденцию роста:<br>&nbsp; - Определяем по внутренним данным магазина <br>&nbsp; - Определяем по внешним данным (косвенный признак увеличения количества спроса в поисковых системах,&nbsp; а также увеличения конкуренции в рекламных сетях)<br></span>
</div>
<div id="wb_Text10">
<span style="color:#000000;"><strong>&#1044;&#1083;&#1103; &#1085;&#1072;&#1075;&#1083;&#1103;&#1076;&#1085;&#1086;&#1089;&#1090;&#1080; &#1091;&#1074;&#1077;&#1083;&#1080;&#1095;&#1077;&#1085;&#1080;&#1077; &#1089;&#1090;&#1088;&#1072;&#1085;&#1080;&#1094; &#1074;&#1093;&#1086;&#1076;&#1072; &#1087;&#1086; &#1090;&#1086;&#1074;&#1072;&#1088;&#1072;&#1084;:</strong></span>
</div>
<div id="wb_Image7" style="display:inline-block;width:100%;height:auto;z-index:15;">
<a href="javascript:displaylightbox('./Img-titel-metrika.html',{autoDimensions:true,autoScale:true,width:1032,height:'100%'})" target="_self"><img src="images/metrika.yandex-min-graf.jpg" id="Image7" alt=""></a>
</div>
<div id="wb_Text19">
<span style="color:#000000;"><strong>&#1044;&#1083;&#1103; &#1074;&#1099;&#1073;&#1086;&#1088;&#1082;&#1080; &#1090;&#1077;&#1089;&#1090;&#1086;&#1074;&#1086;&#1075;&#1086; &#1079;&#1072;&#1087;&#1091;&#1089;&#1082;&#1072; &#1090;&#1086;&#1074;&#1072;&#1088;&#1086;&#1074; &#1084;&#1099; &#1080;&#1089;&#1087;&#1086;&#1083;&#1100;&#1079;&#1086;&#1074;&#1072;&#1083;&#1080; &#1089;&#1083;&#1077;&#1076;&#1091;&#1102;&#1097;&#1080;&#1077; &#1082;&#1088;&#1080;&#1090;&#1077;&#1088;&#1080;&#1080;:<br></strong><br>- &#1042;&#1099;&#1089;&#1086;&#1082;&#1072;&#1103; &#1084;&#1072;&#1088;&#1078;&#1072; &#1090;&#1072;&#1082; &#1082;&#1072;&#1082; &#1076;&#1083;&#1103; &#1080;&#1089;&#1090;&#1086;&#1095;&#1085;&#1080;&#1082;&#1072; &#1090;&#1088;&#1072;&#1092;&#1080;&#1082;&#1072; &#1073;&#1091;&#1076;&#1091;&#1090; &#1080;&#1089;&#1087;&#1086;&#1083;&#1100;&#1079;&#1086;&#1074;&#1072;&#1090;&#1100;&#1089;&#1103; &#1082;&#1086;&#1084;&#1084;&#1077;&#1088;&#1095;&#1077;&#1089;&#1082;&#1080;&#1077; &#1080;&#1089;&#1090;&#1086;&#1095;&#1085;&#1080;&#1082;&#1080; &#1090;&#1088;&#1072;&#1092;&#1080;&#1082;&#1072;<br>- &#1058;&#1077;&#1085;&#1076;&#1077;&#1085;&#1094;&#1080;&#1103; &#1088;&#1086;&#1089;&#1090;&#1072;, &#1090;&#1072;&#1082; &#1082;&#1072;&#1082; &#1076;&#1083;&#1103; &#1086;&#1087;&#1090;&#1080;&#1084;&#1080;&#1079;&#1072;&#1094;&#1080;&#1080; &#1080; &#1088;&#1086;&#1089;&#1090;&#1072; &#1076;&#1086;&#1084;&#1077;&#1085;&#1072; &#1085;&#1077;&#1086;&#1073;&#1093;&#1086;&#1076;&#1080;&#1084;&#1086; &#1085;&#1072;&#1073;&#1086;&#1088; &#0171;&#1082;&#1072;&#1088;&#1084;&#1099;&#0187; &#1076;&#1086;&#1084;&#1077;&#1085;&#1072; &#1089; &#1073;&#1091;&#1076;&#1091;&#1097;&#1080;&#1084; &#1088;&#1072;&#1079;&#1076;&#1077;&#1083;&#1077;&#1085;&#1080;&#1077;&#1084; &#1085;&#1072; &#1087;&#1086;&#1076;&#1076;&#1086;&#1084;&#1077;&#1085;&#1099; &#1089; &#1086;&#1087;&#1090;&#1080;&#1084;&#1080;&#1079;&#1072;&#1094;&#1080;&#1077;&#1081; &#1082;&#1086;&#1085;&#1090;&#1077;&#1085;&#1090;&#1085;&#1072; &#1080; &#1087;&#1086;&#1074;&#1099;&#1096;&#1077;&#1085;&#1080;&#1103; &#1088;&#1077;&#1083;&#1077;&#1074;&#1072;&#1085;&#1090;&#1085;&#1086;&#1089;&#1090;&#1080; &#1082; &#1088;&#1077;&#1082;&#1083;&#1072;&#1084;&#1080;&#1088;&#1091;&#1077;&#1084;&#1086;&#1084;&#1091; &#1090;&#1086;&#1074;&#1072;&#1088;&#1091; &#1090;&#1072;&#1082; &#1082;&#1072;&#1082; &#1095;&#1072;&#1089;&#1090;&#1100; &#1079;&#1072;&#1087;&#1088;&#1086;&#1089;&#1086;&#1074; &#1075;&#1077;&#1086; &#1079;&#1072;&#1074;&#1080;&#1089;&#1080;&#1084;&#1099;&#1077;&nbsp; <br></span>
</div>
</div>
</div>
</div>

<div id="wb_LayoutGrid7">
<div id="LayoutGrid7">
<div class="col-1">
<div id="wb_Text11">
<span style="color:#000000;">Создание торгового предложения</span>
</div>
<div id="wb_Text12">
<span style="background-color:#FFFFFF;color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Для создания торгового предложения мы проанализировали пользовательские предпочтения:<br><br>- По данным ключевых запросов. Для ранжирования потребностей мы разбили «боли» потребителей по рангу из общих потребностей и упаковали продукты как дополнительные выгоды (книга рецептов, повышенная гарантия, дополнительные аксессуары) <br>- По предложениям конкурентов<br>- По конкурентным продуктам<br>- По косвенным продуктам той же тематики (при отборе смежных товаров учитывалось как их преимущества так и недостатки)</span>
</div>
<div id="wb_Image2" style="display:inline-block;width:100%;height:auto;z-index:19;">
<img src="map-traffic-new.jpeg" id="Image2" alt="">
</div>
</div>
<div class="col-2">
<div id="wb_Text13">
<span style="color:#000000;">~1428%</span>
</div>
<div id="wb_Text14">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Средний ROMI на сайт тандыров<br>1950% средний ROMI на сайт табличек для дома<br><br>Увеличение количества заказов в среднем за отчетный период:<br>- 450% тандыры<br>- 333% адресные таблички<br><br></span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid6">
<div id="LayoutGrid6">
<div class="col-1">
<div id="wb_Image4" style="display:inline-block;width:100%;height:auto;z-index:22;">
<a href="images/metrika.yandex-palitra.jpg" data-rel="lightbox-fancybox" target="_self"><img src="images/metrika.yandex-palitra.jpg" id="Image4" alt=""></a>
</div>
<a id="Button1" href="https://www.figma.com/proto/688k8V4dSlf9iQ5waxNfhq/%D0%9F%D0%B0%D0%BB%D0%B8%D1%82%D1%80%D0%B0-%D0%B4%D0%BE%D0%BC%D0%B0?node-id=1%3A5&viewport=828%2C45%2C0.13327418267726898&scaling=scale-down-width" target="_blank" style="display:block;width: 100%;;height:38px;z-index:23;">Посмотреть сайт</a>
</div>
<div class="col-2">
<div id="wb_Image5" style="display:inline-block;width:100%;height:auto;z-index:24;">
<a href="images/tandir24.jpg" data-rel="lightbox-fancybox" target="_self"><img src="images/tandir24.jpg" id="Image5" alt=""></a>
</div>
<a id="Button2" href="https://www.figma.com/proto/jxE8cYcSejdi8aqnHw292M/Tandir-24?node-id=1%3A15&viewport=475%2C-463%2C0.06466367840766907&scaling=scale-down-width" target="_blank" style="display:block;width: 100%;;height:38px;z-index:25;">Посмотреть сайт</a>
</div>
<div class="col-3">
<div id="wb_Image6" style="display:inline-block;width:100%;height:auto;z-index:26;">
<a href="images/tablichka.jpg" data-rel="lightbox-fancybox" target="_self"><img src="images/tablichka.jpg" id="Image6" alt=""></a>
</div>
<a id="Button3" href="https://www.figma.com/proto/y5kUpb6Gc3SlCm85x6QFtQ/%D0%A2%D0%B0%D0%B1%D0%BB%D0%B8%D1%87%D0%BA%D0%B0-%D0%B4%D0%BE%D0%BC%D0%B0?node-id=1%3A5&viewport=546%2C60%2C0.09888903796672821&scaling=scale-down-width" target="_blank" style="display:block;width: 100%;;height:38px;z-index:27;">Посмотреть сайт</a>
</div>
</div>
</div>
<div id="wb_LayoutGrid8">
<div id="LayoutGrid8">
<div class="col-1">
<div id="wb_Text9">
<span style="color:#000000;">Оптимизация расходов</span>
</div>
<div id="wb_Text15">
<span style="background-color:#FFFFFF;color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Для оптимизации расходов на продвижение нескольких ресурсов: <br><br>- Регионы доставки (ограничение на все типы продвижения)<br>- Взяли отношение региональный спрос и количество конкурентов (контекстной рекламы)<br>- Создание поддоменов для каждого региона продвижения учитывая ГЕО зависимость запросов<br><br>Для улучшения поведенческих факторов под SEO запросы основгов сайта<br><br>- Закупка трафика расширение ключевых запросов связанных с основным<br>- Расширение кластера запросов и закупка по минимальной цене </span>
</div>
<div id="wb_Image9" style="display:inline-block;width:100%;height:auto;z-index:30;">
<a href="images/Map-russia.png" data-rel="lightbox-fancybox" target="_self"><img src="images/Map-russia.png" id="Image9" alt=""></a>
</div>
</div>
<div class="col-2">
<div id="wb_Text16">
<span style="color:#000000;">~20%</span>
</div>
<div id="wb_Text17">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Средний снижение расходов на отчетный пероиод</span>
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
<input type="text" id="Editbox1" style="display:block;width: 100%;height:40px;z-index:35;" name="Имя" value="" spellcheck="false" placeholder="&#1042;&#1072;&#1096;&#1077; &#1080;&#1084;&#1103;">
<div id="wb_phone" style="display:inline-block;width:100%;z-index:36;">
<input type="text" id="phone" style="" name="Номер телефона" value="" spellcheck="false" placeholder="&#1053;&#1086;&#1084;&#1077;&#1088; &#1090;&#1077;&#1083;&#1077;&#1092;&#1086;&#1085;&#1072;" class="phone">
</div>
<a id="Button4" href="" style="display:block;width: 100%;;height:38px;z-index:37;">Перезвоните мне</a>
<a id="Button10" href="https://wa.me/79600562977?text=%D0%9F%D1%80%D0%B8%D0%B2%D0%B5%D1%82%3A))%20%D0%9F%D0%B8%D1%88%D1%83%20%D1%82%D0%B5%D0%B1%D0%B5%20%D1%81%20%D1%82%D0%B2%D0%BE%D0%B5%D0%B3%D0%BE%20%D1%81%D0%B0%D0%B9%D1%82%D0%B0" style="display:block;width: 100%;;height:34px;z-index:38;">Написать в whatsapp</a>
<a id="Button12" href="https://t.me/RustemHv" style="display:block;width: 100%;;height:34px;z-index:39;">Написать в telegram</a>
</div>
<div class="col-2">
<div id="wb_Text46">
<span style="color:#000000;font-family:Ubuntu;font-size:24px;letter-spacing:2.07px;line-height:31px;"><strong>Контакты </strong></span>
</div>
<div id="wb_Image8" style="display:inline-block;width:100%;height:auto;z-index:41;">
<img src="images/map-min.png" id="Image8" alt="">
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