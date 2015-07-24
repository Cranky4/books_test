<?php

    namespace app\models;

    use Yii;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "authors".
     *
     * @property integer $id
     * @property string $first_name
     * @property string $last_name
     */
    class Authors extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'authors';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
              [['first_name', 'last_name'], 'required'],
              [['first_name', 'last_name'], 'string', 'max' => 255]
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
              'id'         => 'ID',
              'first_name' => 'First Name',
              'last_name'  => 'Last Name',
            ];
        }

        /**
         * @inheritdoc
         * @return AuthorsQuery the active query used by this AR class.
         */
        public static function find()
        {
            return new AuthorsQuery(get_called_class());
        }

        public static function getAuthorsList($withEmpty = false)
        {
            $authors = self::find()->all();
            $listdata = ArrayHelper::map($authors,'id', 'last_name');
            if($withEmpty) {
                $listdata = ArrayHelper::merge([0=>""], $listdata);
            }
            return $listdata;
        }

        public function getFullName()
        {
            return trim($this->last_name." ".$this->first_name);
        }
    }
