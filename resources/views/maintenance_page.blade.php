<?php
// Get the user's preferred languages from the Accept-Language header
$languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

// Set the default language to English
$lang = 'en';

// Loop through the user's preferred languages and check if we have a translation available
foreach ($languages as $language) {
    $language = strtolower(substr($language, 0, 2));
    if (in_array($language, array('en', 'ar', 'fr', 'es', 'zh', 'pt'))) {
        $lang = $language;
        break;
    }
}

// Set the content-language header to the selected language
header("Content-Language: $lang");

// Translations object
$translations = array(
    'en' => array(
        'title' => 'Site Maintenance',
        'heading' => 'We\'ll be back soon!',
        'text' => 'Sorry for the inconvenience but we\'re performing some maintenance at the moment. If you need to you can always <a href="mailto:vertitect@gmail.com">contact us</a>, otherwise we\'ll be back online shortly!',
        'team' => '&mdash; The Team',
        'day' => 'Days',
        'hour' => 'Hours',
        'minute' => 'Minutes',
        'second' => 'Seconds',
    ),
    'ar' => array(
        'title' => 'صيانة الموقع',
        'heading' => 'سنعود قريبا!',
        'text' => 'نعتذر عن الإزعاج الذي قد يسببه الصيانة الحالية للموقع. في حال كان لديك أي احتياجات يمكنك الاتصال بنا على الفور <a href="mailto:vertitect@gmail.com">بريدنا الإلكتروني</a>، وفي غير ذلك فسنعود على المدى القريب!',
        'team' => '&mdash; فريق العمل',
        'day' => 'أيام',
        'hour' => 'ساعات',
        'minute' => 'دقائق',
        'second' => 'ثواني',
    ),
    'fr' => array(
        'title' => 'Maintenance du site',
        'heading' => 'Nous reviendrons bientôt!',
        'text' => 'Désolé pour le dérangement, mais nous effectuons actuellement des travaux de maintenance. Si vous en avez besoin, vous pouvez toujours <a href="mailto:vertitect@gmail.com">nous contacter</a>, sinon nous serons de retour en ligne prochainement!',
        'team' => '&mdash; L\'équipe',
        'day' => 'Jours',
        'hour' => 'Heures',
        'minute' => 'Minutes',
        'second' => 'Secondes',
    ),
    'es' => array(
        'title' => 'Mantenimiento del sitio',
        'heading' => '¡Volveremos pronto!',
        'text' => 'Disculpe las molestias, pero estamos realizando mantenimiento en este momento. Si lo necesita, siempre puede <a href="mailto:vertitect@gmail.com">contactarnos</a>, ¡de lo contrario volveremos en línea pronto!',
        'team' => '&mdash; El equipo',
        'day' => 'Días',
        'hour' => 'Horas',
        'minute' => 'Minutos',
        'second' => 'Segundos',
    ),
    'zh' => array(
        'title' => '网站维护',
        'heading' => '我们很快就会回来！',
        'text' => '抱歉给您带来不便，但我们目前正在进行维护。如果您需要的话，您随时可以<a href="mailto:vertitect@gmail.com">联系我们</a>，否则我们很快就会恢复在线！',
        'team' => '&mdash; 团队',
        'day' => '天',
        'hour' => '小时',
        'minute' => '分钟',
        'second' => '秒',
    ),
    'pt' => array(
        'title' => 'Manutenção do Site',
        'heading' => 'Voltaremos em breve!',
        'text' => 'Desculpe pelo incômodo, mas estamos realizando manutenção no momento. Se precisar, sempre pode <a href="mailto:vertitect@gmail.com">entrar em contato conosco</a>, senão estaremos de volta online em breve!',
        'team' => '&mdash; O time',
        'day' => 'Dias',
        'hour' => 'Horas',
        'minute' => 'Minutos',
        'second' => 'Segundos',
    ),
);

// Set the protocol
$protocol = isset($_SERVER['SERVER_PROTOCOL']) ?? '';
if (!in_array($protocol, array('HTTP/1.1', 'HTTP/2', 'HTTP/2.0'), true)) {
    $protocol = 'HTTP/1.0';
}

// Set the status code for crawlers like googlebot...
header("$protocol 503 Service Unavailable", true, 503);
header('Content-Type: text/html; charset=utf-8');
header('Retry-After: 30');
?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo $translations[$lang]['title']; ?></title>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            text-align: center;
            padding: 20px;
            font: 20px Helvetica, sans-serif;
            color: #efe8e8;
            background-color: #2e2929
        }

        @media (min-width: 768px) {
            body {
                padding-top: 150px;
            }
        }

        h1 {
            font-size: 50px;
        }

        article {
            display: block;
            text-align: left;
            max-width: 650px;
            margin: 0 auto;
        }

        a {
            color: #dc8100;
            text-decoration: none;
        }

        a:hover {
            color: #efe8e8;
            text-decoration: none;
        }
    </style>
</head>
<body>
<article>
    <h1><?php echo $translations[$lang]['heading']; ?></h1>
    <div>
        <p><?php echo $translations[$lang]['text']; ?></p>
        <p><?php echo $translations[$lang]['team']; ?></p>
    </div>
    <div style="display: flex; flex-direction: row; justify-content: space-between;">
        <p class="day"></p>
        <p class="hour"></p>
        <p class="minute"></p>
        <p class="second"></p>
    </div>
</article>
<script>

    const countDown = () => {
        const countDay = new Date("08/07/2024"); // please indicate launch date (mm/dd/YYYY) :)
        const now = new Date();
        const counter = countDay - now;
        const second = 1000;
        const minute = second * 60;
        const hour = minute * 60;
        const day = hour * 24;
        const textDay = Math.floor(counter / day);
        const textHour = Math.floor((counter % day) / hour);
        const textMinute = Math.floor((counter % hour) / minute);
        const textSecond = Math.floor((counter % minute) / second)
        document.querySelector(".day").innerText = textDay + ' <?php echo $translations[$lang]['day']; ?>';
        document.querySelector(".hour").innerText = textHour + ' <?php echo $translations[$lang]['hour']; ?>';
        document.querySelector(".minute").innerText = textMinute + ' <?php echo $translations[$lang]['minute']; ?>';
        document.querySelector(".second").innerText = textSecond + ' <?php echo $translations[$lang]['second']; ?>';
    }
    countDown();
    setInterval(countDown, 1000);
</script>
</body>
</html>