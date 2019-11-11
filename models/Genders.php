<?php


namespace app\models;


use yii\db\ActiveRecord;

class Genders extends ActiveRecord
{
    public function getUsers(){
        return $this->hasMany(get_class(new UserTable()), ['gender'=>'id_gender']);
    }

}