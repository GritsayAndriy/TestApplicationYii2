<?php

namespace app\controllers;

use app\models\AddressTable;
use app\models\UserTable;
use \yii\helpers\Url;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return string
     */


    public function actionIndex() //for page users list
    {
        $id = 0;
        $request = Yii::$app->request;
        if (null !== $request->post('delete_submit')) {
            $this->deleteUser($request->post('user_id'));
        }
        if (null !== $request->post('edit_submit')) {
            if ($request->post('panel_edit') == 'open') {
                $id = 0;
            } else
                $id = $request->post('user_id');
        }
        if (null !== $request->post('change_submit')) {
            $this->editUser($request->post('user_id'));
        }
        $query = UserTable::find();

        //pagination of users list
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 5,
            'forcePageParam' => false, 'pageSizeParam' => false]);
        $users = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        return $this->render('index', compact("users", "pages", 'id'));
    }


    public function actionAddUser()
    { //for page which adding new user. After added user adding address.

        //check session with property user_id/ if there is a user_id then start adding address.
        if (Yii::$app->session->has('user_id')) {
//        if (Yii::$app->request->post('id_user')!==null) {
            $this->actionAddAddress();
        }
        //load request in model. Validation and save user's data in database. And start adding address.
        $user = new UserTable();
        if ($user->load(Yii::$app->request->post())) {
            $user->date_creation = date('Y-m-d h:i:s');
            if ($user->save()) {
                $id = $user->getPrimaryKey();
                Yii::$app->session->set('user_id', $id);
                return $this->actionAddAddress();
            } else
                Yii::$app->session->setFlash('error', 'Не удалось загрузить');
        }

        return $this->render('users', compact('user'));
    }


    public function actionAddAddress()
    {
        //for adding address.
        $request = Yii::$app->request;
        if ($request->isPost) {
            if ($request->post('id_user') !== null) {
                $id_user = $request->post('id_user');
                Yii::$app->session->set('id_user', $id_user);
            } else {
                $id_user = Yii::$app->session->get('user_id');
            }

            $address = new AddressTable();
            $address->user_id = $id_user;
            if ($address->load(Yii::$app->request->post())) {
                if ($address->save()) {
                    Yii::$app->session->removeAll();
                    Yii::$app->session->setFlash('success', 'Адрес добавлен');
                    Yii::$app->session->set('id_user', $id_user);
                    return $this->actionAboutUser();
                } else
                    Yii::$app->session->setFlash('error', 'Не удалось загрузить');
            }
            return $this->render('address', compact('address', 'id_user'));
        }
        return Yii::$app->response->redirect(['site/index']);


    }


    public function deleteUser($id)
    {
        $user = UserTable::find()->where(['id_user' => $id])->one();
        $addresses = $user->address;
        $transaction = UserTable::getDb()->beginTransaction();
        try {
            foreach ($addresses as $address) {
                $address->delete();
            }
            $user->delete();
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

    }


    public function editUser($id)
    {
        $user = UserTable::findOne($id);
        if ($user->load(Yii::$app->request->post())) {
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Данные Изменены');
            } else
                Yii::$app->session->setFlash('error', 'Не удалось загрузить');
        }
    }


    public function actionAboutUser()
    {
        $request = Yii::$app->request;
        if ($request->post('id_user') !== null ) {
            Yii::$app->session->set('id_user', $request->post('id_user'));
//        } elseif (!Yii::$app->session->has('id_user'))
//            return Yii::$app->response->redirect(['site/index']);
        }
//            if ($request->isPost) {
        $idUser =  Yii::$app->session->get('id_user');
        $user = UserTable::find()->where(['id_user' => $idUser])->one();
        $gender = $user->genders;
        $dataProvider = new ActiveDataProvider([
            'query' => AddressTable::find()->where(['user_id' => $idUser]),
            'pagination' => [
                'pageSize' => 5,
                'forcePageParam' => false,
                'pageSizeParam' => false
            ]

        ]);
        return $this->render('user_info', compact('idUser', 'user', 'gender', 'dataProvider'));

    }


    public function actionDeleteAddress()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $idAddress = $request->post('id_address');
            if (AddressTable::find()->where(['user_id' => $request->post('id_user')])->count() > 1) {
                $address = AddressTable::findOne($idAddress);
                $address->delete();
                Yii::$app->session->setFlash('success', 'Данные удалены');
            } else
                Yii::$app->session->setFlash('error', 'Не удалось удалить последний адрес');
        }

        return $this->actionAboutUser();
    }


    public function actionUpdateAddress()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $id_user = $request->post('id_user');
            $address = AddressTable::findOne($request->post('id_address'));
            if ($address->load($request->post())) {
                if ($address->save()) {
                    Yii::$app->session->setFlash('success', 'Данные изменены');
                } else
                    Yii::$app->session->setFlash('error', 'Не удалось изменить');
            }

        }
        if (Yii::$app->session->hasFlash('success')) {
            return $this->actionAboutUser();
        }
        return $this->render('address', compact('address', 'id_user'));
    }
}
