<?php
//https://prog-time.ru/

function vardump($str){
    echo "<pre>";
    var_dump($str);
    echo "<pre>";
}

header('Content-type: text/html; charset=utf-8');
setlocale(LC_ALL, 'ru_RU.UTF-8');

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ .'/vendor/autoload.php';

function parser($urlPage){
    $ch = curl_init($urlPage);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $result = curl_exec($ch);

    return $result;
}

$urlPage = 'https://artelamp.ru/catalog/podvesnyie-svetilniki/a1023sp-1cc';
$result = parser($urlPage);

$pq = phpQuery::newDocument($result);

$arrMainParams = [
    "title" => trim($pq->find('h1.title')->text()),
    "price" => trim($pq->find('.card_main_content_unit_bottom_cost')->text()),
];

$arrMainImg = $pq->find('.gallery-top_img img');

foreach($arrMainImg as $el){
    $imgEl = pq($el);
    $arrMainImagesTotal[] = 'https://artelamp.ru' . $imgEl->attr('data-url');
}

$arrExtraParams = $pq->find('.card_characters_list_content_block li');

foreach($arrExtraParams as $el){
    $extraEl = pq($el);
    $name = trim($extraEl->find('.name')->text());
    $value = trim($extraEl->find('.value')->text());


    $arrDropParams[] = [
        "name" => $name,
        "value" => $value
    ];
}

$offers = [

    [
        'id' => '123',
        'listMainParams' => $arrMainParams,
        'listMainImg' => $arrMainImagesTotal,
        'listExtraParams' => $arrDropParams,

    ]

];

vardump($offers);


