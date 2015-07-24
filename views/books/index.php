<?php

    use yii\helpers\Html;
    use yii\grid\GridView;

    /* @var $this yii\web\View */
    /* @var $searchModel app\models\BooksSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Books';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Books', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
        /**
         * Каруселька для картинок книг
         */
        echo newerton\fancybox\FancyBox::widget([
          'target'  => 'a[rel=book_preview]',
          'helpers' => true,
          'mouse'   => true,
          'config'  => [
            'maxWidth'    => '90%',
            'maxHeight'   => '90%',
            'playSpeed'   => 7000,
            'padding'     => 0,
            'fitToView'   => false,
            'width'       => '70%',
            'height'      => '70%',
            'autoSize'    => false,
            'closeClick'  => false,
            'closeBtn'    => false,
            'openOpacity' => true,
          ]
        ]);
        /**
         * Вьюха книги в окне
         */
        echo newerton\fancybox\FancyBox::widget([
          'target'  => 'a[rel=book_view]',
          'helpers' => false,
          'mouse'   => false,
          'config'  => [
            'type'       => 'iframe',
            'fitToView'  => true,
            'autoSize'   => false,
            'closeClick' => false,
            'arrows'     => false,
          ],
        ]);
    ?>

    <?=$this->render('_search',[
          'model' => $searchModel,
      ]);
    ?>

    <?= GridView::widget([
      'dataProvider' => $dataProvider,
//      'filterModel'  => $searchModel,
      'columns'      => [
        'id',
        'name',
        [
          'attribute' => 'preview_image',
          'content'   => function ($model, $key, $index, $column) {
              if ($model->preview_image) {
                  return Html::a(Html::img($model->preview_image, [
                    'class' => 'img img-thumbnail smallimage js_fancybox',
                    'alt'   => $model->name
                  ]), $model->preview_image, [
                    'rel' => 'book_preview',
                  ]);
              }
              return "";
          }
        ],
        'authorName',
        'date:datetime',
        'date_create:datetime',
        [
          'class'   => 'yii\grid\ActionColumn',
          'template' => '{view} {update} {delete}',
          'buttons' => [
            'view' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                  'title' => "Просмотр",
                  'rel'   => 'book_view',
                ]);
            },
            'update' => function ($url, $model) {
                $url .= "?".http_build_query($_GET);
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                  'title' => "Редактирование",
                ]);
            },
          ],
        ]
      ]
    ]); ?>

</div>
