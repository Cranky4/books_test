<?php

    namespace app\models;

    use Yii;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use app\models\Books;
    use yii\helpers\ArrayHelper;

    /**
     * BooksSearch represents the model behind the search form about `app\models\Books`.
     */
    class BooksSearch extends Books
    {

        public $authorName;
        public $date_start;
        public $date_end;

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
              [['id', 'date_create', 'date_update', 'date', 'author_id'], 'integer'],
              [['name', 'preview_image', 'authorName', 'date_start', 'date_end'], 'safe'],
              [
                ['date_start', 'date_end'],
                'match',
                'pattern' => '/^\d{2}\.\d{2}\.\d{4}$/',
                'message' => 'Неверный фомат даты (дд.мм.гггг)'
              ],
            ];
        }

        /**
         * @inheritdoc
         */
        public function scenarios()
        {
            // bypass scenarios() implementation in the parent class
            return Model::scenarios();
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return ArrayHelper::merge(parent::attributeLabels(), [
              'authorName' => 'Автор',
              'date_start' => 'Дата выпуска',
              'date_end'   => 'до'
            ]);
        }

        /**
         * Creates data provider instance with search query applied
         *
         * @param array $params
         *
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = Books::find();

            $dataProvider = new ActiveDataProvider([
              'query' => $query,
            ]);

            $this->load($params);

            $dataProvider->setSort([
              'attributes' => [
                'id',
                'name',
                'date_create',
                'date_update',
                'date',
                'authorName' => [
                  'asc'   => ['authors.last_name' => SORT_ASC],
                  'desc'  => ['authors.last_name' => SORT_DESC],
                  'label' => 'Автор'
                ]
              ]
            ]);

            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                $query->joinWith(['author']);
                return $dataProvider;
            }

            $query->andFilterWhere([
              'books.id'    => $this->id,
              'date_create' => $this->date_create,
              'date_update' => $this->date_update,
              'date'        => $this->date,
              'author_id'   => $this->author_id,
            ]);

//            $query->andFilterWhere(['like', 'name', $this->name])->andFilterWhere([
//              'like',
//              'preview_image',
//              $this->preview_image
//            ]);

            if ($this->date_start && $this->date_end) {
                $ts1 = strtotime($this->date_start);
                $ts2 = strtotime($this->date_end);
                $query->andFilterWhere(['between', 'date', $ts1, $ts2]);
            } elseif ($this->date_start) {
                $ts = strtotime($this->date_start);
                $query->andWhere('date >= :TS', [":TS" => $ts]);
            }  elseif ($this->date_end) {
                $ts = strtotime($this->date_end);
                $query->andWhere('date <= :TS', [":TS" => $ts]);
            }

//            if ($this->authorName) {
//                // filter by author name
//                $query->innerJoinWith([
//                  'author' => function ($q) {
//                      $q->where('authors.first_name LIKE "%' . $this->authorName . '%" OR authors.last_name LIKE "%' . $this->authorName . '%"');
//                  }
//                ]);
//            } else {
//                $query->innerJoinWith(['author']);
//            }

            return $dataProvider;
        }
    }
