<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\models\Authors;
    use yii\jui\DatePicker;

    /* @var $this yii\web\View */
    /* @var $model app\models\BooksSearch */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="books-search">

    <?php $form = ActiveForm::begin([
      'action' => ['search'],
      'method' => 'get',
    ]); ?>


    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'date_start')->widget(DatePicker::classname(), [
      'language'   => 'ru',
      'dateFormat' => 'dd.MM.yyyy',
      'options'    => [
        'class' => 'form-control'
      ]
    ]); ?>

    <?= $form->field($model, 'date_end')->widget(DatePicker::classname(), [
      'language'   => 'ru',
      'dateFormat' => 'dd.MM.yyyy',
      'options'    => [
        'class' => 'form-control'
      ]
    ]); ?>

    <?= $form->field($model, 'author_id')->dropDownList(Authors::getAuthorsList(true)) ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', Yii::$app->getUrlManager()->createUrl('books/index'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
