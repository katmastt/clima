<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\Headers;
use app\models\Token;

echo Html::CssFile('@web/css/project/index.css');
$this->registerJsFile('@web/js/project/index.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$back_icon='<i class="fas fa-arrow-left"></i>';
$access_icon='<i class="fas fa-external-link-square-alt"></i>';
$update_icon='<i class="fas fa-pencil-alt"></i>';
$back_link='/project/index';
$delete_icon='<i class="fas fa-times"></i>';
$new_icon='<i class="fas fa-plus-circle"></i>';
$exclamation_icon='<i class="fas fa-exclamation-triangle" style="color:orange" title="The Vm belongs to an expired project"></i>';
$mode = 0;


Headers::begin() ?>
<?php
	echo Headers::widget(
	['title'=>$project->name,
		'buttons'=>
		[
			//added the access button that redirects you to schema
			['fontawesome_class'=>$access_icon,'name'=> 'Access', 'action'=> ['/site/index'], 'type'=>'a', 'options'=>['class'=>'btn btn-success btn-md'] ],
			//added the token button
			
			['fontawesome_class'=>$new_icon,'name'=> 'New Token', 'action'=> ['/project/new-token-request','id'=>$requestId, 'mode'=>$mode, 'uuid'=>$mode], 'type'=>'a', 'options'=>['class'=>'btn btn-secondary btn-md'] ],
			//['fontawesome_class'=>$update_icon,'name'=> 'Update', 'action'=> ['/project/edit-project','id'=>$request_id], 'type'=>'a', 'options'=>['class'=>'btn btn-secondary btn-md'] ],
			['fontawesome_class'=>$back_icon,'name'=> 'Back', 'action'=>[$back_link], 'type'=>'a', 
			'options'=>['class'=>'btn btn-default']] 
		],
	]);
?>
<?Headers::end()?>



<div class="row"><h3 class="col-md-12">Access on demand batch computations</div>
<div class="row"><div class="col-md-12">If you want to access on demand batch computations please click on <b>Access</b> button.</div></div>
<div class="row">&nbsp;</div>
<div class="row"><h3 class="col-md-12">New token</div>
<div class="row"><div class="col-md-12">If you want to create a new token please click on <b>New Token</b> button. You can create more than one tokens. If you don't provide any information during the creation of the token, your token would take the dafault values (title: first 8 characters of the token, expiration date: expiration date of the project). </div></div>

<div class="row"><div class="col-md-12"></div></div>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning" role="alert">
		<td class="col-md-2 align-middle"><?=$exclamation_icon ?></td>
		Please keep in mind that for security reasons HYPATIA stores only a description of your assigned tokens and not your exact tokens.  <font color=black><b>Thus, you are responsible for storing and retriving your tokens.</b>
        </div>
    </div>
</div>  


<div class="row"><h3 class="col-md-12">Issued tokens(<?=$issued_tokens?>) 
	<i class="fas fa-chevron-up" id="arrow" title="Hide tokens" style="cursor: pointer" ></i></h3> 
</div>

<div class="row">&nbsp;</div>

<?php
if ($issued_tokens!=0) {
?>

<div class="table-responsive" style="display:all;" id="expired-table">
   	<table class="table table-striped">
		<thead>
			<tr>
				<th class="col-md-2" scope="col">Token title</th>
				<th class="col-md-2 text-center">Expires on</th>
				<th class="col-md-2 text-center" scope="col">Active</th>
				<th class="col-md-3" scope="col">&nbsp;</th>
			</tr>
		</thead>
		<tbody>

<?php
	foreach ($strArray as $token_name){
		if (strcmp($token_name, "[{") != 0 and strcmp($token_name, "uuid") != 0 and strcmp($token_name, ":") != 0 and strcmp($token_name, "}]") != 0 and strcmp($token_name, "},{") != 0){
			//echo "$token_name". "<br>";
			$URLt = $URL.'/'.$token_name;
			//echo "$URLt". "<br>";
			$token_details = Token::GetTokenDetails($URLt, $headers);
			$title = $token_details[0];
			$exp_days = $token_details[2];
			$active = $token_details[3];
			$uuid = $token_details[4];
			//echo "$token_details". "<br>";
			// $token_details[1] = $token_details[1]->format('d/m/Y');
			// echo $token_details[1];


?>
	<tr class="active" style="font-size: 14px;">
		<td class="col-md-2" style="vertical-align: middle!important;"> <?=$title?></td>
		<td class="col-md-2 text-center" style="vertical-align: middle!important;"><?=$exp_days. " days "?></td>
		<td class="col-md-2 text-center" style="vertical-align: middle!important;"><?=$active?></td>
		<td class="col-md-3 text-right">
			<?=Html::a("$update_icon Edit",['/project/new-token-request','id'=>$requestId, 'mode'=>1, 'uuid'=>$uuid],['class'=>'btn btn-primary create-vm-btn'])?> 
			<?=Html::a("$delete_icon Delete",['/project/new-token-request','id'=>$requestId, 'mode'=>2, 'uuid'=>$uuid],['class'=>"btn btn-danger btn-md delete-volume-btn"])?>
					
		</td>
	</tr>
<?php
				
			
		}
	}
} 
?>
			</tbody>
		</table>
	</div> <!--table-responsive-->