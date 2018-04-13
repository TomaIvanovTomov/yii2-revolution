<?php

namespace tomaivanovtomov\slider\controllers;

use yii\base\InvalidArgumentException;
use Yii;
use tomaivanovtomov\slider\models\Slide;
use tomaivanovtomov\slider\models\SlideSearch;
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
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 0,
                'variations' => [
                    \Yii::$app->language,
                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT * FROM slide LEFT JOIN slideLang ON slide.id=slideLang.slide_id',
                ],
            ],
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

            $images = Yii::$app->request->post('Slide');

            Slide::deleteRemovedImages($images['image']);

            $i = 0;
            $images_keys = array_keys($images['image']);

            while ($i <= end($images_keys))
            {
                if(isset($images['model_id'][$i])){

                    $id = (int)$images['model_id'][$i];

                    //Check if new record
                    if($images['is_new'][$i] == 1){
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
                        throw new InvalidArgumentException;

                    }

                }

                $i++;
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

    public function actionSort()
    {
        $model = new Slide();

        if(Yii::$app->request->post()){

            $sort = 1;

            foreach (Yii::$app->request->post('Slide') as $id){

                if( $model->reorderSlide($id, $sort) === false ){
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Something went wrong! Please, try again.'));
                    return $this->render('_sort', [
                        'result' => $model->loadSortable()
                    ]);
                }

                $sort++;

            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Order was updated successfully!'));

        }

        return $this->render('_sort', [
            'result' => $model->loadSortable()
        ]);
    }

    /**
     * Delete image via ajax
     */
    public function actionDeleteImage()
    {
        $slide_id = Yii::$app->request->post('id');
        $image = Slide::findOne($slide_id);
        if(!empty($image)){
            $path = $image->getImagePath() . "/backend_images/".Slide::FOLDER_SLIDER."/" . $image->filename;
            if(file_exists($path)){
                unlink($path);
            }

            $image->delete();
        }
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
