<?php

namespace app\controllers;

use Yii;
use app\models\Box;
use app\models\Status;
use app\models\ProductToBox;
use app\models\BoxSearch;
use app\models\Events\BoxVolumeChanged;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\dispatchers\BoxEventDispatcher;
/**
 * BoxController implements the CRUD actions for Box model.
 */
class BoxController extends Controller
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
     * Lists all Box models.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->showBoxIndex();  
    }

    // проверяем равенство shipped_qty=received_qty
    public function checkEQ($boxes) {
        $isEQ = array();
        foreach($boxes as $key=>$box){
            $isEQ[$key] = true;
            foreach($box['productToBoxes'] as $prod_to_box) {
                if($prod_to_box['shipped_qty'] <> $prod_to_box['received_qty']) {
                     $isEQ[$key] = false;
                     break;
                }
            }           
        }
        return $isEQ;       
    }

    /**
     * Displays a single Box model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // добавляем таблицу со списком продуктов вкоробке
        $product_to_box = ProductToBox::find()
            ->where(['box_id'=>$id])
            ->asArray()->all();
        // список статусов
        $status = Status::find()->orderBy('name')->asArray()->all();

        $model = $this->findModel($id);  
        return $this->render('view', compact('model', 'product_to_box', 'status'));
    }

    /**
     * Creates a new Box model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Box();
        // список статусов
                
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                
                $model->recordEvent(new BoxVolumeChanged($model->id));
                // можно просто посчитать объем коробки и без события перед сохранением
                // обработка событий диспетчером
                $model->events = $model->releaseEvents();
                $event_disp = new BoxEventDispatcher();
                $event_disp->dispatch($model->events);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $status = Status::find()->orderBy('name')->asArray()->all();
        return $this->render('create', [
            'model' => $model,
            'status' => $status,
        ]);
    }

    /**
     * Updates an existing Box model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id = null)
    {
        // список статусов
        $status = Status::find()->asArray()->orderBy('name')->all();
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

            // фиксируем событие
            $model->recordEvent(new BoxVolumeChanged($id));
            // можно просто посчитать объем коробки и без события перед сохранением
            // обработка событий диспетчером
            $model->events = $model->releaseEvents();
            $event_disp = new BoxEventDispatcher();
            $event_disp->dispatch($model->events);

            return $this->redirect(['view', 'id' => $model->id]);          
        }
       
        return $this->render('update', [
            'model' => $model,
            'status'=> $status,
            //'action' => 'update',
        ]);
    }

    /**
     * Deletes an existing Box model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        return $this->showBoxIndex();      
    }

    protected function showBoxIndex() {
        $searchModel = new BoxSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $status = Status::find()->indexBy('id')->asArray()->all();
       
        // проверяем равенство shipped_qty=received_qty
        $boxes = $dataProvider->query->indexBy('id')->asArray()->all();
        
        $isEQ = $this->checkEQ($boxes);

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'status' => $status,
                'isEQ' => $isEQ,
            ]);
        }
            
        //debug('render index');    
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status' => $status,
            'isEQ' => $isEQ,
        ]);   
    }

    /**
     * Finds the Box model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Box the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Box::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // изменение статуса коробки
    public function actionChangeStatus($box_id=null, $status_id=null) {

        if(Yii::$app->request->isAjax) {
            $box = Box::findOne($box_id);
            $box->status_id = $status_id;
            //debug($box);
            if($box->save())
                return 'ok change status';
            else return false;    
           
        }
    } 

    // экспорт списка коробок в Excel
    public function actionExportExcel() {
        // получаем данные коробок
        $boxes = Box::find()->with('status')->asArray()->all();
        //debug($boxes,0);

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();  
        //debug( $spreadsheet);

        // Create a new worksheet called "My Data"
        $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'export');

        // Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
        //$spreadsheet->addSheet($worksheet, 0);
        $spreadsheet->addSheet($worksheet, 0);
        $spreadsheet->getSheet(0);
        $spreadsheet->removeSheetByIndex(1);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);


        // Set up some basic data
        $worksheet
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Date')
            ->setCellValue('C1', 'Weight, g')
            ->setCellValue('D1', 'Status');

            $i=2;
            foreach($boxes as $box) {
                $worksheet
                    ->setCellValue('A'.$i, $box['id'])           
                    ->setCellValue('B'.$i, date('d.m.Y', strtotime($box['created_at'])))
                    ->setCellValue('C'.$i, $box['weight'])  
                    ->setCellValue('D'.$i, $box['status']['name']); 
                $i++;   
            }
        
        $time = Date('YmdHis');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        $writer->save(Yii::getAlias('@webroot')."/export/boxes".$time.".xlsx");
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        return "/web/export/boxes".$time.".xlsx";        
    }     
}
