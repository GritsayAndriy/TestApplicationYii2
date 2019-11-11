<?php


namespace app\models;


use yii\db\ActiveRecord;

class AddressTable extends ActiveRecord
{

    public static function tableName()
    {
        return "address";
    }

    public function getUsers(){
        return $this->hasOne(get_class(new UserTable), ['id_user'=>'user_id']);
    }

    public function rules()
    {
        return [
            [['mail_index', 'country', 'city', 'street', 'number_home', 'number_premises'], 'required'],
            ['country','string', 'max'=>2],
            ['country','filter','filter'=> function ($value){return strtoupper($value);}],
            ['mail_index', 'match', 'pattern' => '/^\d+$/'],

        ];
    }
}