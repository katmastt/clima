<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\MagicSearchBox;
use kartik\date\DatePicker;
use app\components\Headers;



echo Html::CssFile('@web/css/project/project-request.css');
$this->registerJsFile('@web/js/project/project-request.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title=$project->name;
?>



Headers::begin() ?>
<?php echo Headers::widget(
['title'=>$this->title])
?>
<?Headers::end()?>

<?php
if ($mode == 0){
?>
<div class="row"><h3 class="col-md-12">New token request</div>
<div class="row"><div class="col-md-12">You can specify the name and the expiration date of your token by filling the following form : </div></div>

<div class="row">&nbsp;</div>


<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    
    <div style="margin-bottom: 20px;">
    <?php echo '<label>  Token expiration date  </label>';
            echo DatePicker::widget([
            'model' => $model, 
            'attribute' => 'expiration_date',
            'options' => ['placeholder' => 'Enter Date'],
            'pluginOptions' => [
            'autoclose'=>true,
            'format'=>'yyyy-mm-dd'
            ]
        ]);?>
        </div>

    <div class="form-group">
            <div class="col-md-1"><?= Html::submitButton('<i class="fas fa-check"></i> Submit', ['class' => 'btn btn-primary']) ?></div>
            <div class="col-md-1"><?= Html::a('<i class="fas fa-times"></i> Cancel', ['/project/on-demand-lp','id'=>$requestId], ['class'=>'btn btn-default']) ?></div>
    </div>

<?php ActiveForm::end(); ?>

<?php 

} else {

?>
<div class="row"><h3 class="col-md-12">Edit </div>
<div class="row"><div class="col-md-12">You can update the name and the expiration date of your token by filling the following form : </div></div>

<div class="row">&nbsp;</div>


<?php $form = ActiveForm::begin(); ?>
<?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'name')->textInput(['value' => $title]) ?>

    <div style="margin-bottom: 20px;">
    <?php echo '<label>  Token expiration date  </label>';
            echo DatePicker::widget([
            'model' => $model, 
            'attribute' => 'expiration_date',
            'options' => ['placeholder' => $exp_date],
            'pluginOptions' => [
            'autoclose'=>true,
            'format'=>'yyyy-mm-dd'
            ]
        ]);?>
        </div>

    <div class="form-group">
            <div class="col-md-1"><?= Html::submitButton('<i class="fas fa-check"></i> Submit', ['class' => 'btn btn-primary']) ?></div>
            <div class="col-md-1"><?= Html::a('<i class="fas fa-times"></i> Cancel', ['/project/on-demand-lp','id'=>$requestId], ['class'=>'btn btn-default']) ?></div>
    </div>
<div class="row"><div class="col-md-12"><?= Html::errorSummary($model, ['encode' => false]) ?></div></div>
<?php ActiveForm::end(); ?>

<?php
}
?>
