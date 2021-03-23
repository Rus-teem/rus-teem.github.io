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
   $message = 'Данные - Кейс "ГКС",  footer';
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
<title>Кейс: "Городская Курьерская Служба"</title>
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
<link href="css/rassil.ru.css" rel="stylesheet">
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
<img src="images/logo.png" id="Image1" alt="">
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
<img src="images/mail.png" id="Image3" alt="">
</div>
</div>
<div class="col-2">
<div id="wb_Text2">
<span style="color:#000000;">&#1062;&#1077;&#1083;&#1080;:</span>
</div>
<div id="wb_Text6">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">- Запустить новое направление компании для снижения издержек компании<br>- Увеличение доходности компании </span>
</div>
<div id="wb_Text4">
<span style="color:#000000;">Задачи:</span>
</div>
<div id="wb_Text3">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">- Протестировать рынок на спрос <br>- Запустить каналы продаж услуг<br>- Упаковать продукт потребностям рынка </span>
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
<span style="color:#000000;">Опредление спроса</span>
</div>
<div id="wb_Text5">
<span style="color:#000000;"><strong>Для определения спроса было сделано следующее:</strong><br><br><strong>Сбор информации о конкурентах:</strong><br>- Количество конкурентов (прямых и косвенных)<br>- Торговые предложение конкурентов<br>- Рекламные каналы и продолжительность (как offline так и online):<br>&nbsp; - Для определение сезонности спроса<br>&nbsp; - Для определения сами каналов рекламы <br><br>После сбора информации о конкурентах были выявлены следующие направления деятельности в предложении услуг без увеличения трудовых мощностей:<br>- Раздача промо материалов <br>- Адресная рассылка промо материалов<br><strong><br>Сбор информации о потенциале рынка:<br></strong>- Сбор информации о зарегистрированных компаниях с подходящей кодом деятельности<br>- Сбор информации о уже рекламирующийся кампаниях в офлайне в необходимом для на формате<br>- Сбор инфекции о количестве запрашиваемой услуги в поисковых системах (Google, Yandex, Mail.ru) <br>- Сбор информации о потенциальных клиентах, зарегистрировавших информацию о своей компании:<br>&nbsp; - В соц. сетях<br>&nbsp; - В гео. сервисах<br>&nbsp; - В поисковых системах (количество сайтов в регионе присутствия)</span>
</div>
<div id="wb_Text10">
<span style="color:#000000;"><strong>Усредненные данные по распредлению активности конкурентов</strong></span>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid6">
<div id="LayoutGrid6">
<div class="col-1">
<div id="wb_Image2" style="display:inline-block;width:100%;height:auto;z-index:15;">
<img src="images/chart.png" id="Image2" alt="">
</div>
</div>
<div class="col-2">
<div id="wb_Image4" style="display:inline-block;width:100%;height:auto;z-index:16;">
<img src="images/chart(1).png" id="Image4" alt="">
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
<span style="background-color:#FFFFFF;color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Для создания торгового предложения необходимо было отстроится от конкурентов, поскольку на момент запуска продукта не было предложений на рынке по offline таргетингу которому маркетологи привыкли по ведению рекламных компаний в сети интернет на различных площадках</span>
</div>
<div id="wb_Image6" style="display:inline-block;width:100%;height:auto;z-index:19;">
<a href="images/targeting.jpg" data-rel="lightbox-fancybox" target="_self"><img src="images/targeting.jpg" id="Image6" alt=""></a>
</div>
<div id="wb_Text9">
<span style="color:#000000;"><strong>&#1054;&#1090;&#1083;&#1080;&#1095;&#1080;&#1077; &#1086;&#1090; &#1080;&#1085;&#1090;&#1077;&#1088;&#1085;&#1077;&#1090;-&#1088;&#1077;&#1082;&#1083;&#1072;&#1084;&#1099;:<br></strong>- &#1042;&#1086;&#1079;&#1084;&#1086;&#1078;&#1085;&#1086;&#1089;&#1090;&#1100; &#1089;&#1086;&#1093;&#1088;&#1072;&#1085;&#1080;&#1090;&#1100; &#1087;&#1088;&#1086;&#1084;&#1086;-&#1084;&#1072;&#1090;&#1077;&#1088;&#1080;&#1072;&#1083; (&#1089; &#1084;&#1086;&#1090;&#1080;&#1074;&#1072;&#1094;&#1080;&#1077;&#1081; &#1076;&#1083;&#1103; &#1082;&#1083;&#1080;&#1077;&#1085;&#1090;&#1072; &#1087;&#1086; &#1074;&#1086;&#1079;&#1074;&#1088;&#1072;&#1090;&#1091; &#1080; &#1087;&#1086;&#1074;&#1090;&#1086;&#1088;&#1085;&#1099;&#1084; &#1079;&#1072;&#1082;&#1072;&#1079;&#1072;&#1084;)<br>- &#1041;&#1086;&#1083;&#1077;&#1077; &#1076;&#1086;&#1083;&#1075;&#1086;&#1077; &#1087;&#1103;&#1090;&#1085;&#1086; &#1082;&#1086;&#1085;&#1090;&#1072;&#1082;&#1090;&#1072; &#1089; &#1087;&#1086;&#1083;&#1100;&#1079;&#1086;&#1074;&#1072;&#1090;&#1077;&#1083;&#1077;&#1084; <br>- &#1041;&#1086;&#1083;&#1077;&#1077; &#1085;&#1080;&#1079;&#1082;&#1080;&#1081; CPO (&#1090;&#1086;&#1083;&#1100;&#1082;&#1086; &#1076;&#1083;&#1103; &#1086;&#1087;&#1088;&#1077;&#1076;&#1077;&#1083;&#1077;&#1085;&#1085;&#1099;&#1093; &#1075;&#1088;&#1091;&#1087;&#1087; &#1090;&#1086;&#1074;&#1072;&#1088;&#1086;&#1074; &#1074; &#1089;&#1074;&#1103;&#1079;&#1080; &#1089; &#1086;&#1075;&#1088;&#1072;&#1085;&#1080;&#1095;&#1077;&#1085;&#1080;&#1103;&#1084;&#1080; &#1074; &#1090;&#1072;&#1088;&#1075;&#1077;&#1090;&#1080;&#1085;&#1075;&#1077;)<br></span>
</div>
</div>
<div class="col-2">
<div id="wb_Text13">
<span style="color:#000000;">~30-35%</span>
</div>
<div id="wb_Text14">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Конверсия сайта в оставление заявок к приобретению услуги<br>Для достижения столь высокой конверсии&nbsp; удалось добиться благодаря узости самого целевого таргета, а также временным ограничениям работы компании (Данные только по интернет рекламе)<br></span>
</div>
<a id="Button1" href="https://www.figma.com/proto/R5g5bs0sezW9KJPhCWRCEv/%D0%93%D0%BE%D1%80%D0%BE%D0%B4%D1%81%D0%BA%D0%B0%D1%8F-%D0%BA%D1%83%D1%80%D1%8C%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F-%D1%81%D0%BB%D1%83%D0%B6%D0%B1%D0%B0?node-id=1%3A2&viewport=628%2C82%2C0.07206089049577713&scaling=scale-down-width" target="_blank" style="display:block;width: 100%;;height:38px;z-index:23;">Посмотреть сайт</a>
</div>
</div>
</div>
<div id="wb_LayoutGrid9">
<div id="LayoutGrid9">
<div class="col-1">
<div id="wb_Text15">
<span style="color:#000000;">Запуск рекламной компании</span>
</div>
<div id="wb_Text16">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Для запуска рекламной компании была выбрана следующая стратегия:<br><br><strong>Телемаркетинг</strong> – предложение к основной услуге, после уже менеджер по продажам завершает сделку<br><br><strong>Интернет-реклама:</strong><br>&nbsp; - Яндекс директ <br>&nbsp; - Google ADS</span>
</div>
<div id="wb_Image5" style="display:inline-block;width:100%;height:auto;z-index:26;">
<a href="images/90dx0xea41.jpg" data-rel="lightbox-fancybox" target="_self"><img src="images/90dx0xea41.jpg" id="Image5" alt=""></a>
</div>
</div>
<div class="col-2">
<div id="wb_Text17">
<span style="color:#000000;">1100%</span>
</div>
<div id="wb_Text18">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Средний ROMI&nbsp; на рекламную кампанию, максимальный ROMI&nbsp; на отчетный период 1900%<br><br>Отдел продаж СR на лида 10% при предложении сопуствующих услуг<br></span>
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
<input type="text" id="Editbox1" style="display:block;width: 100%;height:40px;z-index:31;" name="Имя" value="" spellcheck="false" placeholder="&#1042;&#1072;&#1096;&#1077; &#1080;&#1084;&#1103;">
<div id="wb_phone" style="display:inline-block;width:100%;z-index:32;">
<input type="text" id="phone" style="" name="Номер телефона" value="" spellcheck="false" placeholder="&#1053;&#1086;&#1084;&#1077;&#1088; &#1090;&#1077;&#1083;&#1077;&#1092;&#1086;&#1085;&#1072;" class="phone">
</div>
<a id="Button4" href="" style="display:block;width: 100%;;height:38px;z-index:33;">Перезвоните мне</a>
<a id="Button10" href="https://wa.me/79600562977?text=%D0%9F%D1%80%D0%B8%D0%B2%D0%B5%D1%82%3A))%20%D0%9F%D0%B8%D1%88%D1%83%20%D1%82%D0%B5%D0%B1%D0%B5%20%D1%81%20%D1%82%D0%B2%D0%BE%D0%B5%D0%B3%D0%BE%20%D1%81%D0%B0%D0%B9%D1%82%D0%B0" style="display:block;width: 100%;;height:34px;z-index:34;">Написать в whatsapp</a>
<a id="Button12" href="https://t.me/RustemHv" style="display:block;width: 100%;;height:34px;z-index:35;">Написать в telegram</a>
</div>
<div class="col-2">
<div id="wb_Text46">
<span style="color:#000000;font-family:Ubuntu;font-size:24px;letter-spacing:2.07px;line-height:31px;"><strong>Контакты </strong></span>
</div>
<div id="wb_Image7" style="display:inline-block;width:100%;height:auto;z-index:37;">
<img src="images/map-min.png" id="Image7" alt="">
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