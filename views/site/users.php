<?php

use \yii\widgets\ActiveForm;
use \yii\helpers\Html;

?>
<div class="container mt-xl-5">
    <?php $formUser = ActiveForm::begin(['class' => 'row']) ?>
    <div class="col-xs-3">
        <?= $formUser->field($user, 'login') ?>
        <?= $formUser->field($user, 'password') ?>
        <?= $formUser->field($user, 'name_user') ?>
        <?= $formUser->field($user, 'surname_user') ?>
        <?= $formUser->field($user, 'gender')->dropDownList([
            '3' => 'none',
            '2' => 'female',
            '1' => 'male'
        ]) ?>
        <?= $formUser->field($user, 'email') ?>
        <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
    </div>
    <?php $formUser = ActiveForm::end(); ?>
</div>



