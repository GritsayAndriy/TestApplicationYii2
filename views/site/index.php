<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use \yii\helpers\Html;

?>
<div class="container">

    <?php foreach ($users as $user): ?>
        <?php $formUsers = ActiveForm::begin() ?>
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="">
                    <div class="panel-title pull-left" style="padding-top: 7.5px;">
                        <?= Html::a($user->name_user . ' ' . $user->surname_user, ['site/about-user'], [
                                'class' => 'profile-link',
                                'data-method' => 'POST',
                                'data-params'=>[
                                        'id_user'=>$user->id_user
                                ]]) ?>
                    </div>
                </div>
                <div class="btn-group pull-right">
                    <?= Html::hiddenInput('user_id', $user->id_user) ?>
                    <?= Html::submitButton('Edit', ['class' => 'btn btn-success', 'name' => "edit_submit"]) ?>
                    <?= Html::submitButton('Remove', ['class' => 'btn btn-danger', 'name' => "delete_submit"]) ?>
                </div>
            </div>
            <?php if ($id == $user->id_user): ?>
                <div class="panel-body text-right">
                    <?php $formEditUser = ActiveForm::begin(['class' => 'row']) ?>
                    <div class="col-xs-3 text-left">
                        <?= $formEditUser->field($user, 'login') ?>
                        <?= $formEditUser->field($user, 'password') ?>
                        <?= $formEditUser->field($user, 'name_user') ?>
                    </div>
                    <div class="col-xs-3 text-left">
                        <?= $formEditUser->field($user, 'surname_user') ?>
                        <?= $formEditUser->field($user, 'gender') ?>
                        <?= $formEditUser->field($user, 'email') ?>
                    </div>
                    <?= Html::hiddenInput('panel_edit', 'open') ?>
                    <?= Html::submitButton('Change', ['class' => 'btn btn-success', 'name'=>'change_submit']) ?>
                    <?php $formEditUser = ActiveForm::end(); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php $formUsers = ActiveForm::end() ?>
    <?php endforeach; ?>


    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>

