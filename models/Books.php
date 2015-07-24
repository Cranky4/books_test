<?php

    namespace app\models;

    use Yii;
    use yii\helpers\FileHelper;
    use yii\helpers\StringHelper;
    use yii\helpers\VarDumper;
    use yii\web\UploadedFile;

    /**
     * This is the model class for table "books".
     *
     * @property integer $id
     * @property string $name
     * @property integer $date_create
     * @property integer $date_update
     * @property integer $date
     * @property string $preview
     * @property integer $author_id
     */
    class Books extends \yii\db\ActiveRecord
    {

        public $_date;
        public $_author;
        public $imageFile;

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'books';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
              [['name'], 'required'],
              [['date_create', 'date_update', 'date', 'author_id'], 'integer'],
              [['preview_image'], 'string'],
              [['name'], 'string', 'max' => 255],
              [
                '_date',
                'match',
                'pattern' => '/^\d{2}\.\d{2}\.\d{4}$/',
                'message' => 'Неверный фомат даты (дд.мм.гггг)'
              ],
              [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
              'id'            => 'ID',
              'name'          => 'Название',
              'date_create'   => 'Дата создания',
              'date_update'   => 'Дата изменения',
              'date'          => 'Дата выпуска',
              '_date'         => 'Дата выпуска',
              'preview_image' => 'Картинка',
              'author_id'     => 'Ид автора',
              'authorName'    => 'Автор',
              'imageFile'     => 'Картинка'
            ];
        }

        /**
         * @inheritdoc
         * @return BooksQuery the active query used by this AR class.
         */
        public static function find()
        {
            return new BooksQuery(get_called_class());
        }

        /**
         * @inheritdoc
         */
        public function beforeSave($insert)
        {

            if ($this->isNewRecord) {
                $this->date_create = time();
            }

            $this->date_update = time();

            if ($this->_date) {
                $this->date = strtotime($this->_date);
            }

            return parent::beforeSave($insert);
        }

        /**
         * This method is called when the AR object is created and populated with the query result.
         * The default implementation will trigger an [[EVENT_AFTER_FIND]] event.
         * When overriding this method, make sure you call the parent implementation to ensure the
         * event is triggered.
         */
        public function afterFind()
        {
            if ($this->date) {
                $this->_date = date('d.m.Y', $this->date);
            }

//            if($this->preview_image) {
//                $this->imageFile = ;
//            }

//            if ($this->author_id) {
//                if ($author = Authors::findOne($this->author_id)) {
//                    $this->_author = $author->getFullName();
//                }
//            }

            parent::afterFind();
        }


        public function getAuthor()
        {
            return $this->hasOne(Authors::className(), ['id' => 'author_id']);
        }

        public function getAuthorName()
        {
            return $this->author->getFullName();
        }

        public function getImageFile()
        {
            $uploadPath = __DIR__ . '/../web';
            return isset($this->preview_image) ? $uploadPath . $this->preview_image : null;
        }

        public function uploadImage()
        {
            $image = UploadedFile::getInstance($this, 'imageFile');
            $file_name = Yii::$app->security->generateRandomString();
            $webpath = '/imgs/' . $file_name . '.' . $image->extension;

            if (empty($image)) {
                return false;
            }
            $this->preview_image = $webpath;

            return $image;
        }

        public function upload()
        {
            if ($this->validate('imageFile')) {
                if (!$this->imageFile->baseName) {
                    return null;
                }

                //можно под класть файлы в папки с ид экземпляра объекта, но, думаю, и так будет достаточно.
                $uploaddir = __DIR__ . '/../web/imgs/' . $this->imageFile->baseName;
                $savepath = $uploaddir . '.' . $this->imageFile->extension;

                $webpath = '/imgs/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;

                if (file_exists($savepath)) {
                    return $webpath;
                }
                $this->imageFile->saveAs($savepath);
                return $webpath;
            } else {
                return false;
            }
        }

        public function deleteImage()
        {
            $file = $this->getImageFile();

            // check if file exists on server
            if (empty($file) || !file_exists($file)) {
                return false;
            }

            // check if uploaded file can be deleted on server
            if (!unlink($file)) {
                return false;
            }

            // if deletion successful, reset your file attributes
            $this->preview_image = null;

            return true;
        }
    }
