<?php

namespace tomaivanovtomov\revolution\controllers;

use Yii;
use tomaivanovtomov\revolution\models\Slide;
use tomaivanovtomov\revolution\models\SlideSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SlideController implements the CRUD actions for Slide model.
 */
class SlideController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Slide models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SlideSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $hidden = new Slide();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'hidden' => $hidden
        ]);

    }

    /**
     * Displays a single Slide model.
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
     * Creates a new Slide model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $is_OK = true;

        if(isset(Yii::$app->request->post('Slide')['image'])){

            for ($i = 0; $i < count(Yii::$app->request->post('Slide')['image']); $i++){

                $id = (int)Yii::$app->request->post('Slide')['model_id'][$i];

                //Check if new record
                if(Yii::$app->request->post('Slide')['is_new'][$i] == 1){
                    $model = new Slide();

                    //Save the model to create id
                    $model->save();

                    $id = $model->id;
                }

                //Retrive the model again
                $model = $this->findModel($id, true);

                $model->loadModels($i);

                if($model->update() === false){

                    $is_OK = false;
                    break;

                }

            }

            if($is_OK){
                Yii::$app->session->setFlash('success',  Yii::t('app', 'Your changes were saved successfully!'));
                return $this->redirect('index');
            }

            Yii::$app->session->setFlash('error',  Yii::t('app', 'Something went wrong. Please, try again later!'));
            return $this->redirect('index');

        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Slide model.
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
     * Deletes an existing Slide model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id, true);

        $file = Yii::getAlias('@images-dev') . "/backend_images/".Slide::FOLDER_SLIDER."/{$model->filename}";

        if(file_exists($file)){
            unlink($file);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionAddSlide()
    {
        $model = new Slide();

        return $this->renderAjax('_slideImage', [
            'model' => $model,
            'index' => Yii::$app->request->post('index')
        ]);
    }

    /**
     * Finds the Slide model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Slide the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $multilingual = false)
    {
        if($multilingual === true){
            $model = Slide::find()->where(['id' => $id])->multilingual()->one();
        }else{
            $model = Slide::findOne($id);
        }

        if(!empty($model)){
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
