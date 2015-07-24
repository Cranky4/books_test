<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\jui\DatePicker;
    use kartik\file\FileInput;

    /* @var $this yii\web\View */
    /* @var $model app\models\Books */
    /* @var $form yii\widgets\ActiveForm */
    //    \yii\helpers\VarDumper::dump($model,10,true);
?>

<div class="books-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <!--    --><? //= $form->field($model, 'imageFile')->fileInput() ?>

    <?= $form->field($model, 'imageFile')->widget(FileInput::classname(), [
      'options'       => ['accept' => 'image/*'],
      'pluginOptions' => [
        'showPreview'      => true,
        'showCaption'      => true,
        'showRemove'       => false,
        'showUpload'       => false,
        'overwriteInitial' => true,
        'initialCaption'   => $model->preview_image ? $model->preview_image : "",
        'initialPreview'   => [
          $model->preview_image ? Html::img($model->preview_image,
            ['class' => 'file-preview-image', 'alt' => $model->name, 'title' => $model->name]) : null,
        ],
      ],
    ]); ?>



    <?= $form->field($model, 'author_id')->dropDownList($authors) ?>

    <?= $form->field($model, '_date')->widget(DatePicker::classname(), [
      'language'   => 'ru',
      'dateFormat' => 'dd.MM.yyyy',
      'options'    => [
        'class' => 'form-control'
      ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
          ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
