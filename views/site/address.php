<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="container">
    <?php $formAddress = ActiveForm::begin() ?>
    <div class="col-xs-3">
    <?= $formAddress->field($address, 'mail_index') ?>
    <?= $formAddress->field($address, 'country') ?>
    <?= $formAddress->field($address, 'city') ?>
    <?= $formAddress->field($address, 'street') ?>
    <?= $formAddress->field($address, 'number_home') ?>
    <?= $formAddress->field($address, 'number_premises') ?>
    <?= Html::hiddenInput('id_address', $address->id_address) ?>
    <?php //if (isset($id_user)):?>
    <?= Html::hiddenInput('id_user', $id_user) ?>
    <?php //endif; ?>
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php $formAddress = ActiveForm::end(); ?>
</div>
