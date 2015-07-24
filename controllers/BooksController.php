<?php

    namespace app\controllers;

    use app\models\Authors;
    use Yii;
    use app\models\Books;
    use app\models\BooksSearch;
    use yii\data\ActiveDataProvider;
    use yii\helpers\ArrayHelper;
    use yii\helpers\VarDumper;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\web\UploadedFile;

    /**
     * BooksController implements the CRUD actions for Books model.
     */
    class BooksController extends Controller
    {
        public function behaviors()
        {
            return [
              'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                  'delete' => ['post'],
                ],
              ],
              'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                      'allow'   => true,
                      'actions' => ['index', 'create', 'update', 'delete', 'view', 'search', 'upload'],
                      'roles'   => ['@'],
                    ],
                    // everything else is denied
                ],
              ],
            ];
        }

        /**
         * Lists all Books models.
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new BooksSearch();
//            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider = new ActiveDataProvider([
              'query' => Books::find(),
            ]);

            return $this->render('index', [
              'dataProvider' => $dataProvider,
              'searchModel'  => $searchModel,
            ]);
        }

        /**
         * Lists all Books models.
         * @return mixed
         */
        public function actionSearch()
        {
            $searchModel = new BooksSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
              'dataProvider' => $dataProvider,
              'searchModel'  => $searchModel,
            ]);
        }

        /**
         * Displays a single Books model.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionView($id)
        {
            return $this->renderAjax('view', [
              'model' => $this->findModel($id),
            ]);
        }

        /**
         * Creates a new Books model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new Books();

            if ($model->load(Yii::$app->request->post())) {
                $image = $model->uploadImage();
                if ($image !== false) {
                    $path = $model->getImageFile();
                    $image->saveAs($path);
                }

//                if ($path = $model->upload()) {
//                    $model->preview_image = $path;
//                }
                $model->save();
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                  'model'   => $model,
                  'authors' => Authors::getAuthorsList(),
                ]);
            }
        }

        /**
         * Updates an existing Books model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);
            $oldFilePath = $model->preview_image;

            if ($model->load(Yii::$app->request->post())) {
                $image = $model->uploadImage();
                if ($image === false) {
                    $model->preview_image = $oldFilePath;
                }
                if ($model->save()) {
                    if ($image !== false) { // delete old and overwrite
                        $path = $model->getImageFile();
                        $image->saveAs($path);
                    }
                }

                //сохранение параметров фильтра
                $filterParams = $_GET;
                if (ArrayHelper::keyExists('id', $filterParams)) {
                    unset($filterParams['id']);
                    if ($filterParams) {
                        $url = ['search'] + $filterParams;
                        return $this->redirect($url);
                    }
                }

                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                  'model'   => $model,
                  'authors' => Authors::getAuthorsList(),
                ]);
            }
        }

        /**
         * Deletes an existing Books model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionDelete($id)
        {
            $model = $this->findModel($id);
            if ($model->delete()) {
                $model->deleteImage();
            }

            return $this->redirect(['index']);
        }

        /**
         * Finds the Books model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return Books the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (($model = Books::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        public function actionUpload()
        {
            $model = new Books();

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                return;
            }
        }
    }
