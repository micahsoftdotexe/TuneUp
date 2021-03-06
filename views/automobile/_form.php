<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div>
    <?php $automobileForm = ActiveForm::begin([
        'id' => 'initial-automobile-form',
        'action' => $create ? \yii\helpers\Url::to(['/automobile/create', 'customer_id' => $model->customer_id]) : \yii\helpers\Url::to(['/automobile/customer-edit', 'id' => $model->id]),
    ]) ?>
    <?= $automobileForm->field($model, 'make')->label(Yii::t('app', 'Make'))->textInput(['disabled' => $view])?>
    <?= $automobileForm->field($model, 'model')->label(Yii::t('app', 'Model'))->textInput(['disabled' => $view])?>
    <?= $automobileForm->field($model, 'year')->label(Yii::t('app', 'Year'))->textInput(['disabled' => $view])?>
    <?= $automobileForm->field($model, 'motor_number')->label(Yii::t('app', 'Motor Number'))->textInput(['disabled' => $view])?>
    <?= $automobileForm->field($model, 'vin')->label(Yii::t('app', 'VIN'))->textInput(['disabled' => $view])?>
    <?= $automobileForm->field($model, 'customer_id')->hiddenInput(['id' => 'customer_id_field'])->label(false)?>
    <?= Html::submitButton($create ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), [
        'id'                => 'create-automobile',
        'class'             => 'btn btn-success',
        'disabled'          => $view,
    ])?>
    <?php $automobileForm = ActiveForm::end() ?>   
</div>

<?php
    if ($change_form) {
        $jsBlock = <<< JS
        $('#modalNewAutomobile').on('shown.bs.modal', function () {
            document.getElementById('customer_id_field').value = $('#customer_id').val();
        })
        JS;
        $this->registerJs($jsBlock, \yii\web\View::POS_END);
        $ajaxSubmitUrl = \yii\helpers\Url::to(['/automobile/ajax-initial-create']);
        $jsBlock2 = '
            $("#initial-automobile-form").on("beforeSubmit", function(){
                let data = $("#initial-automobile-form").serialize();
                //console.log(data);
                //console.log($(\'#customer_id_field\').value);
                $.ajax({
                    url:"'.$ajaxSubmitUrl.'",
                    type: "POST",
                    data: data,
                    success: function (returnData) {
                        //console.log(returnData);
                        if(returnData != 400) {
                            returnData = JSON.parse(returnData);
                            let newOption = new Option(returnData.text, returnData.id, false, false);
                            //change the forms
                            $("#automobile_id").append(newOption).trigger("change");
                            $("#automobile_id").val(returnData.id).trigger("change");
                            //clear form
                            $("#initial-automobile-form")[0].reset();
                            $("#modalNewAutomobile").modal("hide");
                        } else {
                            console.log("error");
                        }
                        

                    }, error: function( xhr, status, errorThrown ) {
                        console.log("Error: " + errorThrown );
                        console.log("Status: " + status );
                        console.dir( xhr );
                    },
                })
            }).on("submit", function(e){
                e.preventDefault();
            });

        ';
        $this->registerJs($jsBlock2, \yii\web\View::POS_END);
    }
?>