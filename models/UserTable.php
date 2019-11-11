<?php


namespace app\models;


use yii\db\ActiveRecord;
use app\models\AddressTable;

class UserTable extends ActiveRecord
{
    public static function tableName()
    {
        return "user";
    }

    public function getAddress(){
        return $this->hasMany(get_class(new AddressTable), ['user_id'=>'id_user']);
    }

    public function getGenders(){
        return $this->hasOne(get_class(new Genders()), ['id_gender'=>'gender']);
    }

    public function rules()
    {
        return [
            [['login', 'password', 'name_user', 'surname_user', 'gender', 'date_creation', 'email'], 'required'],
            [ ['login', 'email'], 'unique', 'targetAttribute' => ['login', 'email']],
            ['login', 'string','min'=>4],
            ['password', 'string','min'=>6],
            [['name_user','surname_user'],'filter','filter'=> function ($value){return ucfirst($value);}],

        ];
    }

}