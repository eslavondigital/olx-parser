# Olx.ua parser
 <p align="center">
 <img src="https://img.shields.io/badge/PHP-%3E%3D7.4.2-%23green" alt="php version">
 <img src="https://img.shields.io/github/v/tag/eslavondigital/olx-parser?label=version" alt="Latest Stable Version">
 <img src="https://img.shields.io/github/license/eslavondigital/olx-parser" alt="License">
 </p> 
  Simple parser of ads from olx.ua
 
 ## Installation
 
 Install the latest version with
 
 ```bash
 $ composer require eslavondigital/olx-parser
 ```
 

 ## Basic Usage
 ### Parsing a single ad
 
 ```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Eslavon\Olx\Parser;

$parser = new Parser();

$parser->setUrl('https://www.olx.ua/obyavlenie/koshechka-shanel-ischet-dom-IDIiT6V.html#38b636e8a1;promoted');
$result = $parser->run();
var_dump($result);
/**
array(1) {
    [0]=> array(7) {
        ["name"]=> string(43) "Кошечка Шанель ищет дом"
        ["amount"]=> string(18) "Бесплатно"
        ["description"]=> string(738) "Скромная и интеллигентная Шанель с пепельно-голубоватой шерсткой и белыми носочками ищет самых лучших хозяев. Не шкодная, спокойная, лоточек с древесным наполнителем знает. Кушает кашку с куриным фаршем и корм Клуб 4 лапы. Желательно в спокойную семью без маленьких детей. Девочке 1,5 месяца, первичную обработку от паразитов прошла. Будет среднешерстной (короткая очень густая шерсть) Привезем по городу"
        ["img"]=> string(80) "https://ireland.apollo.olxcdn.com:443/v1/files/pjq2uknv0fca2-UA/image;s=1000x700"
        ["date"]=> string(45) "Добавлено: в 22:31, 9 июня 2020"
        ["author"]=> string(10) "Ольга"
        ["address"]=> string(85) "Запорожье, Запорожская область, Шевченковский"
    }
}
*/
 ```

 ### Parsing multiple ads
 
 ```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Eslavon\Olx\Parser;

$parser = new Parser();
$url_array =[
    'https://www.olx.ua/obyavlenie/intel-core-IDG8Pu3.html#f9f01ff837;promoted',
    'https://www.olx.ua/obyavlenie/monitor-samsung-diagonal-21-5-IDIfVZ8.html#f9f01ff837;promoted'
];
$parser->setMultiUrl($url_array);
$result = $parser->run();
var_dump($result);
/**
array(2) {
    [0]=> array(7) {
        ["name"]=> string(10) "Intel Core"
        ["amount"]=> string(13) "1 000 грн."
        ["description"]=> string(307) "Процесори різних моделей, всі у робочому стані. Якщо зацікавило пишіть. Для уточнення вартості моделі яка зацікавила, пишіть. Також є інші комплектуючі для комп'ютерів."
        ["img"]=> string(80) "https://ireland.apollo.olxcdn.com:443/v1/files/pk95fbpvt4z53-UA/image;s=1000x700"
        ["date"]=> string(75) "Опубликовано с мобильного в 12:03, 10 июня 2020"
        ["author"]=> string(16) "Катерина"
        ["address"]=> string(61) "Тернополь, Тернопольская область"
  }
  [1]=> array(7) {
        ["name"]=> string(46) "Монитор Samsung диагональ 21,5"
        ["amount"]=> string(13) "1 800 грн."
        ["description"]=> string(710) "Монитор в идеальном состоянии. Хотел сделать из него телевизор и подключить к IPtv через HDMI разъем. Но поскольку к нему нужно отдельно еще и звук делать решил не заморачиваться и купить просто телевизор. Монитор оказался не нужен. Продаю по полной предоплате на карту Приват банка или доставка ОЛХ. При необходимости лучше не звоните а пишите сообщение в ОЛХе. Я дам другой телефон для связи."
        ["img"]=> string(80) "https://ireland.apollo.olxcdn.com:443/v1/files/9qfvni8xxix32-UA/image;s=1000x700"
        ["date"]=> string(75) "Опубликовано с мобильного в 12:02, 10 июня 2020"
        ["author"]=> string(8) "Ігор"
        ["address"]=> string(98) "Прогресс, Кировоградская область, Гайворонский район"
  }
}
*/
 ```

### Remove invalid URLs from the array
 ```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Eslavon\Olx\Parser;

$parser = new Parser();
$url_array =[
    'https://www.olx.ua/obyavlenie/intel-core-IDG8Pu3.html#f9f01ff837;promoted',
    'undefined'
];
$clear_url_array = $parser->clearUrl($url_array);
var_dump($clear_url_array);
/**
array(1) {
    [0]=> string(73) "https://www.olx.ua/obyavlenie/intel-core-IDG8Pu3.html#f9f01ff837;promoted"
}
 */
 ``` 

### Check URL for correctness
 ```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Eslavon\Olx\Parser;

$parser = new Parser();
$url = 'https://www.olx.ua/obyavlenie/intel-core-IDG8Pu3.html#f9f01ff837;promoted';
var_dump($parser->validationUrl($url)); //bool (true)

$url = 'https://www.avito.ru/krasnodar/ohota_i_rybalka/flyaga_brezent_5_l_1913328391';
var_dump($parser->validationUrl($url)); //bool (false)
 ``` 

 ## Support Eslavon Digital Financially
 Get supported Eslavon Digital and help fund the project.
 
 **Yandex.Money:** 410016014512747
 
 **QIWI:** https://qiwi.com/n/eslavon
 
 ### Author
 
 **Vinogradov Victor** - <eslavon.work.victor@gmail.com> - <https://vk.com/winogradow.wiktor><br />
 **Eslavon Digital** - <eslavondigital@gmail.com> - <https://vk.com/eslavon>
 
 ### License
 
 All Elavon Digital products are licensed under the MIT license - see the `LICENSE` file for details

