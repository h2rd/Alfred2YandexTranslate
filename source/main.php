<?php
require __DIR__ . '/workflows.php';
define('YA_KEY', 'trnsl.1.1.20130724T164704Z.52427c04ed17cc71.a26297a53fc1553f8a8b36dd1072da239b46989b');

$wf = new Workflows();

$query = $_SERVER['argv'][1];
$defaultlanguage = "uk";
$lang = "";

$detecturl = "https://translate.yandex.net/api/v1.5/tr.json/detect?text=" . urlencode( $query ) . "&key=" . YA_KEY;
$json = json_decode( $wf->request( $detecturl ), true);

if ($json && 200 === $json["code"]):
    $lang = $json["lang"];

    if ("en" === $lang):
        $querylang = "en-" . $defaultlanguage;
    else:
        $querylang = $lang . "-en";
    endif;

    $translateurl = "https://translate.yandex.net/api/v1.5/tr.json/translate?lang=". urlencode($querylang) . "&text=" . urlencode($query) . "&key=" . YA_KEY;
    $json = json_decode( $wf->request($translateurl), true);
    if ($json && 200 === $json["code"]):
        $translate = $json['text']['0'];

        $wf->result( time(), $translate, $translate, 'Yandex Translate ' . $translate, 'icon.png'  );
    endif;
endif;

echo $wf->toxml();
