<?php

namespace app\controllers;

use Yii;
use app\models\ProductToBox;
use app\models\ProductToBoxSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductToBoxController implements the CRUD actions for ProductToBox model.
 */
class ProductToBoxController extends Controller
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
                        'delete' => ['POST', 'GET'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ProductToBox models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductToBoxSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductToBox model.
     * @param string $sku Sku
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($sku)
    {
        return $this->render('view', [
            'model' => $this->findModel($sku),
        ]);
    }

    /* добавление продукта в коробку id  */
    public function actionCreate($id=null)
    {
        $model = new ProductToBox();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // return $this->redirect(['update', 'sku' => $model->sku]);
                return $this->redirect(['box/view', 'id' => $model->box_id]); // возвращаемся в коробку            
            }
        } else {
            $model->loadDefaultValues();
        }        

        $model->box_id = $id;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductToBox model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $sku Sku
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($sku)
    {
        $model = $this->findModel($sku);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
          
            return $this->redirect('/box/view?id='.$model['box_id']);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProductToBox model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $sku Sku
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($sku)
    {
        $this->findModel($sku)->delete();

        return $this->redirect(Yii::$app->request->referrer);
        //return $this->redirect(['index']);
    }

    /**
     * Finds the ProductToBox model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $sku Sku
     * @return ProductToBox the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sku)
    {
        if (($model = ProductToBox::findOne(['sku' => $sku])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
