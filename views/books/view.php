<?php

    use yii\helpers\Html;
    use yii\widgets\DetailView;
    use app\assets\AppAsset;

    /* @var $this yii\web\View */
    /* @var $model app\models\Books */

    $this->title = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;

    AppAsset::register($this);
?>

<div class="books-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--    <p>-->
    <!--        --><? //= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <!--        --><? //= Html::a('Delete', ['delete', 'id' => $model->id], [
        //          'class' => 'btn btn-danger',
        //          'data'  => [
        //            'confirm' => 'Are you sure you want to delete this item?',
        //            'method'  => 'post',
        //          ],
        //        ]) ?>
    <!--    </p>-->

    <?= DetailView::widget([
      'model'      => $model,
      'attributes' => [
        'id',
        'name',
        'date_create:datetime',
        'date_update:datetime',
        'date:datetime',
        [
          'attribute' => 'preview_image',
          'content'   => function ($model, $key, $index, $column) {
              if ($model->preview_image) {
                  return Html::img($model->preview_image, [
                    'class' => 'img img-thumbnail',
                    'alt'   => $model->name
                  ]);
              }
              return "";
          }
        ],
        'authorName',
      ],
    ]) ?>

</div>