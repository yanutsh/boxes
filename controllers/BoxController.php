<?php

namespace app\controllers;

use Yii;
use app\models\Box;
use app\models\Status;
use app\models\ProductToBox;
use app\models\BoxSearch;
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
                        'delete' => ['POST'],
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
        // создание событий ===============================
        // $event_disp = new BoxEventDispatcher(['one', 'two']);
        
        // $box=new Box();
        // $box->recordEvent('showBoxesList-1');
        // $box->recordEvent('showBoxesList-2');
        
        // $box->events = $box->releaseEvents();
        // // обработка событий диспетчером
        // $event_disp->dispatch($box->events);
        // //=========================================================
        
        $searchModel = new BoxSearch();

        $dataProvider = $searchModel->search($this->request->queryParams);

        //debug($this->request->queryParams);
        $status = Status::find()->indexBy('id')->asArray()->all();

       
        // проверяем равенство shipped_qty=received_qty
        $boxes = $dataProvider->query->indexBy('id')->asArray()->all();
        
        $isEQ = $this->checkEQ($boxes);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status' => $status,
            'isEQ' => $isEQ,
        ]);
    }

    // проверяем равенство shipped_qty=received_qty
    public function checkEQ($boxes) {
        //debug($boxes,0);
        $isEQ = array();
        foreach($boxes as $key=>$box){
            $isEQ[$key] = true;
            foreach($box['productToBoxes'] as $prod_to_box) {
                //echo('shipped_qty='.$prod_to_box['shipped_qty'].' && received_qty='.$prod_to_box['received_qty'].'<br>');
                if($prod_to_box['shipped_qty'] <> $prod_to_box['received_qty']) {
                     $isEQ[$key] = false;
                     break;
                }
            }           
        }
        //debug($isEQ);
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
        $status = Status::find()->asArray()->orderBy('name')->all();

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

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }


        // обработк событий диспетчером
        // $ded = new DummyEventDispatcher(['one', 'two']);
        // $ded->dispatch(['three', 'fore']);

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Box model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // список статусов
        $status = Status::find()->asArray()->orderBy('name')->all();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'status'=> $status,
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

        return $this->redirect(['index']);
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
