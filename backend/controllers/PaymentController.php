<?php

namespace backend\controllers;

use common\models\Payment;
use common\models\search\PaymentSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => [
                                //'create',
                                'index',
                                'delete',
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ]
            ]
        );
    }

    /**
     * Lists all Payment models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $day_start = mktime('0', '0', '0');
        $day_end = $day_start + 86399;
        $today_cash = Payment::find()->where(['between', 'created', $day_start, $day_end])
            ->andWhere(['is_deleted' => 0, 'payment_type' => 0])
            ->sum('amount');
        $today_card = Payment::find()->where(['between', 'created', $day_start, $day_end])
            ->andWhere(['is_deleted' => 0, 'payment_type' => 1])
            ->sum('amount');
        $card = Payment::find()->where(['is_deleted' => 0, 'payment_type' => 1])
            ->sum('amount');
        $cash = Payment::find()->where(['is_deleted' => 0, 'payment_type' => 0])
            ->sum('amount');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'today_cash' => $today_cash,
            'today_card' => $today_card,
            'card' => $card,
            'cash' => $cash,
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Payment();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->deleted_time = time();
        $model->deleted_user_id = \Yii::$app->user->id;
        $model->update();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
