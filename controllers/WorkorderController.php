<?php

namespace app\controllers;

use Yii;
use app\models\Workorder;
use app\models\WorkorderSearch;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * WorkorderController implements the CRUD actions for Workorder model.
 */
class WorkorderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete',
                    //'delete' => ['POST'],
                    'get-automobiles',
                ],
                
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create','get-automobiles', 'index', 'edit', 'create-template'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Workorder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkorderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Workorder model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Workorder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //$model = null;
        // \Yii::debug($id,
        //      'dev'  // devlog file.  See components->log->dev defined in /config/web.php
        // );
        $model = new Workorder();
        //$model->scenario = Workorder::SCENARIO_STEP1;
    

        return $this->render('create', [
            'model' => $model,
            'update' => false
            //'stage' => 1,
        ]);
    }

    public function actionEdit($id)
    {
        //$model = $this->findModel($id);
        $model = Workorder::find()->where(['id' => $id])->one();
        
        return $this->render('edit', [
            'model' => $model,
        ]);
    }


    public function actionCreateTemplate()
    {
        $model = new Workorder();

        

        if($model->load(Yii::$app->request->post()) && $model->save()) {
            //$model->scenario = Workorder::SCENARIO_STEP2;
            
            $this->redirect(['edit', 'id' => $model->id]);
                
               
            

        }  else {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Workorder Save Error'));
            return $this->redirect(Url::base(true).'/workorder');
        }
        
    }

    /**
     * Updates an existing Workorder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Workorder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Workorder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Workorder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Workorder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public static function actionGetAutomobiles()
    {
        // \Yii::debug("before id",
        //     'dev'  // devlog file.  See components->log->dev defined in /config/web.php
        //     );
        if ($id = Yii::$app->request->post('id')) {
            return \app\models\Automobile::getIds($id);
        } else {
            return \yii\helpers\Json::encode([
                'status' => 'error',
                'details' => 'No customer_id',
            ]);
        }
    }
}
