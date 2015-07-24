<?php
    /**
     * Created by PhpStorm.
     * User: Cranky4
     * Date: 23.07.2015
     * Time: 14:55
     */
    namespace app\models;

    use yii\base\Model;
    use yii\web\UploadedFile;

    class UploadForm extends Model
    {
        /**
         * @var UploadedFile
         */
        public $imageFile;

        public function rules()
        {
            return [
              [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            ];
        }

        public function upload()
        {
            if ($this->validate()) {
                $this->imageFile->saveAs('@web/imgs/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
                return true;
            } else {
                return false;
            }
        }
    }