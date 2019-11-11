<?php

use yii\data\ActiveDataProvider;
use \yii\grid\GridView;
use \app\models\AddressTable;
use yii\helpers\Html;

?>
<div class="container">
    <div class="text-lg-center">
        <p class="h3"><?= $user->name_user." " ?><?= $user->surname_user ?></p>
        <p class="h4"><?= $gender->name_gender ?></p>
        <p class="h4"><?php $time = strtotime($user->date_creation);echo date("d-m-Y g:i", $time);?></p>
        <p class="h4"><?= $user->email ?></p>






    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'mail_index',
            'country',
            'city',
            'street',
            'number_home',
            'number_premises',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete} {update}',
                'buttons' => [

                    'update' => function ($url, $model, $key) {
                        return Html::a("Update", ['site/update-address'], [
                            'class' => 'btn btn-success',
                            'data-method' => 'POST',
                            'data-params' => [
                                'id_address' => $model->id_address,
                                'id_user' => $model->user_id
                            ]]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a("Delete", ['site/delete-address'], [
                            'class' => 'btn btn-danger',
                            'data-method' => 'POST',
                            'data-params' => [
                                'id_address' => $model->id_address,
                                'id_user' => $model->user_id
                            ]]);
                    }


                ]
            ],
        ],
    ]);
    ?>
    <?= Html::a('Add', ['site/add-address'], [
        'class' => 'btn btn-success',
        'data-method' => 'POST',
        'data-params' => [
            'id_user' => $user->id_user,
        ],
    ]) ?>
</div>
