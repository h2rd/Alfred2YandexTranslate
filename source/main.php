<?php
require __DIR__ . '/workflows.php';
$wf = new Workflows();

$query = $_SERVER['argv'][1];
$defaultlanguage = "uk";
$lang = "";

$detecturl = "http://translate.yandex.net/api/v1/tr.json/detect?text=" . urlencode( $query );
$json = json_decode( $wf->request( $detecturl ), true);

if ($json && 200 === $json["code"]):
    $lang = $json["lang"];

    if ("en" === $lang):
        $querylang = "en-" . $defaultlanguage;
    else:
        $querylang = $lang . "-en";
    endif;

    $translateurl = "http://translate.yandex.net/api/v1/tr.json/translate?lang=". urlencode($querylang) . "&text=" . urlencode($query);
    $json = json_decode( $wf->request($translateurl), true);
    if ($json && 200 === $json["code"]):
        $translate = $json['text']['0'];

        $wf->result( time(), $translate, $translate, 'Yandex Translate ' . $translate, 'icon.png'  );
    endif;
endif;

echo $wf->toxml();
