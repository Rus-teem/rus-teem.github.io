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
   $message = 'Данные - Кейс "Dkhome", footer';
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
<title>Кейс: "DKHome"</title>
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
<link href="css/dkhome.css" rel="stylesheet">
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
<img src="images/789 (2).png" id="Image1" alt="">
</div>
</div>
<div class="col-2">
<div id="wb_Text1">
<span style="color:#FFFFFF;"><strong>Компания «DK Home» занимается деревянным домостроением на рынке Казани и&nbsp; Татарстана с 2011 года. Строительство дома «под ключ», в том числе и все придомовые постройки - гаражи, беседки, бани, заборы, благоустройство участка.<br><br>Отдельным направлением можно выделить строительство бань и саун от средних до премиум класса.</strong></span>
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
<img src="images/oczilindrovannoe-brevno.png" id="Image3" alt="">
</div>
</div>
<div class="col-2">
<div id="wb_Text2">
<span style="color:#000000;">&#1062;&#1077;&#1083;&#1080;:</span>
</div>
<div id="wb_Text6">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">- Оптимизация стоимости привлечения клиента<br>- Увеличение количества клиентов</span>
</div>
<div id="wb_Text4">
<span style="color:#000000;">Задачи:</span>
</div>
<div id="wb_Text3">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">- Изменить&nbsp; предложение<br>- Расширить товарную линейку<br>- Расширить каналы коммуникации</span>
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
<span style="color:#000000;">Разработка сайта </span>
</div>
<div id="wb_Text5">
<span style="color:#000000;">Для достижения максимальной конверсии была необходима разработка сайта с учетом потребностей клиентов на каждой стадии жизни “потребности” от начала появления до удовлетворения. Для этого составили был произведен глубокий анализ:<br>- Ключевых запросов пользователей поисковым система<br>- Анализ сезонности спроса в разрезе потребностей клиентов<br>- Трендов интересов из агрегаторов <br>- Гендерный и возрастной анализ пользователей по ключевым запросам из поисковых систем. <br><br><strong>На основании пользовательских данных был составлен</strong><br>- User map<br>- Разработан сценарий взаимодействия с пользователем <br><br>Для этого было создано множество посадочных страниц и разработан сценарий, по которому пройдет клиент для совершения целевого действия. Под каждую потребность была разработана страница или включена в станицу ведущую к целевому действию в качестве примера:<br><br><strong>Деревянное строительство с учетом материалов строительства:</strong><br>- Строительство: Одноэтажные дома, Дома с мансардой, Двухэтажные дома<br>- Строительство бань<br>- Заборы, беседки и прочие инфраструктурные нежилые помещения <br>Блочное строительство с учетом материалов строительства:<br>- Строительство: Одноэтажные дома, Дома с мансардой, Двухэтажные дома<br><br><strong>По этапам строительства:</strong><br>- Возведение фундамента<br>- Приобретение сруба<br>- Установка доп. элементов<br><br><strong>По качеству приобретения:</strong><br>- В кредит/ипотеку<br>- С использованием материнского капитала<br>- В обмен на другое жилье<br>На основании данных были созданы множество посадочных страниц и сайтов.</span>
</div>
</div>
<div class="col-2">
<div id="wb_Text9">
<span style="color:#000000;">600%</span>
</div>
<div id="wb_Text10">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Увлечение в 6 раз за отчетный период количества сделок.<br>Снижение стоимости сделки на ~ 70%</span>
</div>
<a id="Button1" href="https://www.figma.com/proto/okSoeHMIcLg9DXJtnIZV7N/DK-home?node-id=1%3A2&viewport=406%2C262%2C0.16455423831939697&scaling=min-zoom" target="_blank" style="display:block;width: 100%;;height:38px;z-index:16;">Посмотреть примеры страниц</a>
</div>
</div>
</div>
<div id="wb_LayoutGrid6">
<div id="LayoutGrid6">
<div class="col-1">
<div id="wb_Image6" style="display:inline-block;width:100%;height:auto;z-index:17;">
<a href="images/Land-mockup-1-min.jpg" data-rel="lightbox-fancybox" target="_self"><img src="images/Land-mockup-1-min.jpg" id="Image6" alt=""></a>
</div>
</div>
<div class="col-2">
<div id="wb_Image7" style="display:inline-block;width:386px;height:360px;z-index:18;">
<a href="images/Land-mockup-2-min.jpg" data-rel="lightbox-fancybox" target="_self"><img src="images/Land-mockup-2-min.jpg" id="Image7" alt=""></a>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid7">
<div id="LayoutGrid7">
<div class="col-1">
<div id="wb_Text11">
<span style="color:#000000;">Расширение рекламных каналов</span>
</div>
<div id="wb_Text12">
<span style="background-color:#FFFFFF;color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;line-height:22px;">Для максимизации трафика в рамках бюджета была произведена оптимизация рекламных каналов с учетом пользовательских сценариев поведения на всех этапах продаж. <br></span><span style="background-color:#FFFFFF;color:#000000;font-family:Ubuntu;font-size:19px;line-height:32px;"><strong>ПАССИВНЫЕ ИСТОЧНИКА ТРАФИКА <br></strong></span><span style="background-color:#FFFFFF;color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;line-height:22px;">В рамках работ по привлечению трафика необходимо создать пассивный источники трафика с данной целью была разработана стратегию по использованию каналов.<br><strong>SEO оптимизация </strong>- В рамках данного источника трафика была оптимизированы все страницы посадки трафика под поисковые системы, а также для повышения релевантности ключевого запроса из контекстно-поисковой рекламы были созданы копии страниц для снижения стоимости клика и показа.<br><strong>ГЕО сервисы </strong>– Как дополнительный источник трафика было создано ряд организаций под ключевые запросы в различных категориях строительства.<br><strong>Доски объявлений </strong>– созданы косвенные организации со смежной деятельность, а также перепродажа материала под различные нужды строительства, в которых есть возможность вхождения клиента в воронку продаж.<br></span><span style="background-color:#FFFFFF;color:#000000;font-family:Ubuntu;font-size:19px;line-height:33px;"><strong>АКТИВНЫЕ ИСТОЧНИКИ ТРАФИКА<br></strong></span><span style="background-color:#FFFFFF;color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;line-height:22px;">В активные источника трафика были выбраны следующие источники трафика:<br><br><strong>Поисковая контекстная реклама:</strong><br>- Яндекс Директ<br>&nbsp; - Дополнительно: Гео реклама по конкурентам среди новых посетителей конкурентов<br>- Google ADS<br>&nbsp; - Дополнительно: По интересам пользователей к конкурентам<br><br><strong>Таргетированная реклама:</strong><br>- MyTarget (по интересам пользователей)<br>- VK ADS (по группам конкурентов – парсинг пользователей по действиям) <br><br>Также среди пользователей не пришедших к целевому действию был настроен каскадный ретаргетинг пользователей&nbsp; с оптимизацией по конверсии к целевому действию<br></span>
</div>
</div>
<div class="col-2">
<div id="wb_Text13">
<span style="color:#000000;">75%</span>
</div>
<div id="wb_Text14">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Снижение стоимости привлечение клиента<br><br>Данные по сайту:<br>- Конверсия сайта 3-5%<br>- Отказы в рекламных системах ~ 12%<br></span>
</div>
<div id="wb_Image8" style="display:inline-block;width:270px;height:270px;z-index:23;">
<img src="images/2adebcec2f5bb2547c23f41d2e53679e.png" id="Image8" alt="">
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid8">
<div id="LayoutGrid8">
<div class="col-1">
<div id="wb_Image5" style="display:inline-block;width:100%;height:auto;z-index:24;">
<a href="images/Yandex-metrika-min.jpg" data-rel="lightbox-fancybox" target="_self"><img src="images/Yandex-metrika-min.jpg" id="Image5" alt=""></a>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid9">
<div id="LayoutGrid9">
<div class="col-1">
<div id="wb_Text15">
<span style="color:#000000;">Расширение торгового предложения</span>
</div>
<div id="wb_Text16">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Для максимизации пользовательского входа в воронку продаж разбили все этапы строительства включая периферийное строительство на товарные группы для создания точек входа:<br><br>- Возведение фундамента<br>- Возведение стен<br>- Покупка сруба и других материалов<br>- Создание вентиляции<br>- Создание стропильной системы<br>- Укладки крыши <br>- И другие клиентские потребности<br></span>
</div>
</div>
<div class="col-2">
<div id="wb_Text17">
<span style="color:#000000;">~20%</span>
</div>
<div id="wb_Text18">
<span style="color:#000000;font-family:'Ubuntu';font-weight:300;font-size:19px;">Увеличение обращений пользователей по сопутствующим потребностям пользователей<br></span>
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
<div id="wb_Image2" style="display:inline-block;width:100%;height:auto;z-index:37;">
<img src="images/map-min.png" id="Image2" alt="">
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