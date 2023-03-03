<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Headers;

echo Html::cssFile('@web/css/project/new_token.css');
$this->registerJsFile('@web/js/project/view-request-user.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$back_icon='<i class="fas fa-arrow-left"></i>';
// $back_link='/project/index';
$access_icon='<i class="fas fa-external-link-square-alt"></i>';


Headers::begin() ?>
<?php
	echo Headers::widget(
	['title'=>$project->name,
		'buttons'=>
		[
			['fontawesome_class'=>$back_icon,'name'=> 'Back', 'action'=>['/project/on-demand-lp','id'=>$requestId], 'type'=>'a', 
			'options'=>['class'=>'btn btn-default']] 
		],
	]);
?>
<?Headers::end()?>

<?php
    $headers = [
        "Authorization: Token e2b5e57c47d8bd073ce2b02e49ab2ddeb869837559138e134d3ed13a714c6ac9a236381e83b22e22e7849b04bef7d25ba3be9db33c67a7cbf239e9bd199bd04ca602f1baf1db7018eb231f574a48d9e90e19ac4c9fb1acfead568fe749fe4c94",
        'Content-Type: application/json'
    ];
    $URL1 = "http://62.217.122.242/api_auth/contexts/";
    $pname = $project->name;
    $URL = $URL1.$pname."/tokens"."/".$uuid;
    //echo "$URL"."<br>";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $out = curl_exec($ch);
    curl_close($ch);
    //echo "$out"."<br>";

?>
<div class="row"><h2 class="col-md-12">Token deletion</div>
<div class="row"><div class="col-md-12">Your token was deleted. </div></div>