<?php

//use Yii;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
// if ($update) {
//     $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
//     $this->params['breadcrumbs'][] = ['label' => \app\models\Customer::find()->where(['id'=> $model->customer_id])->one()->first_name.' '.\app\models\Customer::find()->where(['id'=> $model->customer_id])->one()->last_name.' - '.\app\models\Automobile::find()->where(['id'=> $model->automobile_id])->one()->make.' '.\app\models\Automobile::find()->where(['id'=> $model->automobile_id])->one()->model, 'url' => ['edit', 'id' => $model->id]];
//     //$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
// }
$canAddCustomer = Yii::$app->user->can('createCustomer');
$canAddAutomobile = Yii::$app->user->can('createAuto');
$canEditOrder = $update ? Yii::$app->user->can('editOrder'):Yii::$app->user->can('createOrder');

?>

<div class="order-form">
    <?php if (!$update) : ?>
        <?php $form = ActiveForm::begin([
            'id' => 'order-form',
            'action' => ['create-template']
        ]); ?>
    <?php else : ?>
        <?php $form = ActiveForm::begin([
            'id' => 'order-form',
            'action' => ['update-template', 'id' => $model->id]
        ]); ?>
    <?php endif ?>
    <div class="input-group">
        <?= $form->field($model, 'customer_id')->label(Yii::t('app', 'Customer'))->widget(Select2::class, [
                    'data' => \app\models\Customer::getIds(),
                    //'bsVersion' => '3.x',
                    'options' => [
                        'id'   => 'customer_id',
                        'placeholder' => '--'.Yii::t('app', 'Select One').'--',
                        'disabled' => !$canEditOrder
                        //'class' => 'form-control'
                    ],
                ]) ?>
        <span>
                <?= Html::button('Add Customer', [
                                'class' => 'btn btn-default btn-outline-secondary',
                                'id' => 'add-customer',
                                'disabled' => !$canAddCustomer,
                                'data' => [
                                    'toggle' => 'modal',
                                    'target' => '#modalNewCustomer',
                                ],]) ?>
        </span>

    </div>
    <div class="input-group">
        <?= $form->field($model, 'automobile_id')->label(Yii::t('app', 'Automobile'))->widget(Select2::class, [
                    'data' => ($update) ? \app\models\Automobile::getIds($model->customer_id) : [],
                    'options' => [
                        'id'   => 'automobile_id',
                        'placeholder' => '--'.Yii::t('app', 'Select One').'--',
                        'disabled' => !$update || !$canEditOrder,
                    ],
                    'pluginOptions' => [
                    ],]) ?>
        <div class="input-group-append">
                <?= Html::button('Add Automobile', [
                                'id' => 'new_automobile',
                                'disabled' => !$update || !$canAddAutomobile,
                                'class' => 'btn btn-default btn-outline-secondary',
                                'data' => [
                                    'toggle' => 'modal',
                                    'target' => '#modalNewAutomobile',
                                ],]) ?>
        </div>
    </div>
    <div class="input-group">
        <?= $form->field($model, 'odometer_reading')->label(Yii::t('app', 'Odometer Reading'))->textInput(['id' => 'odometer_reading_input','disabled' => !$update || !$canEditOrder])?>
    </div>
    <div class="input-group">
        <?= $form->field($model, 'date')->label(Yii::t('app', 'Date'))->widget(\yii\jui\DatePicker::class,[
            'options' => [
                'class' => 'form-control',

            ],
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear'  => true,
            ]
        ])?>
    </div>
    <div class="input-group">
        <?= Html::checkbox('taxable', $update ? ($model->tax != 0 ? true : false) : true, ['label' => 'Taxable', 'disabled' => !$canEditOrder])?>
    </div>
    <div class="form-group">
        <?= !$update ? Html::a('Cancel', 'index', ['class' => 'btn btn-default btn-outline-secondary']): '' ?>
        <?php if ($canEditOrder) : ?>
            <?= Html::submitButton('Save', ['id'=> 'save_order', 'class' => 'btn btn-primary btn-success']) ?>
        <?php endif ?>
    </div>



    <?php ActiveForm::end(); ?>
</div>

<?php
//------------------------
// Add new Customer
//------------------------
yii\bootstrap\Modal::begin([
    'id'    => 'modalNewCustomer',
    'header' => Yii::t('app', 'Create New Customer'),
    'size'  => yii\bootstrap\Modal::SIZE_LARGE,
]);
?>

<div class="modal-body">
    <!-- Some modal content here -->
    <div id="modalContent">
        <?= Yii::$app->controller->renderPartial('/customer/_form', [
                'model'       => new app\models\Customer(),
                'change_form' => true,
                'create'      => true,
                'view'        => false,
            ]) ?>
    </div>

</div>
<?php
    yii\bootstrap\Modal::end();
?>

<?php
//------------------------
// Add new Automobile
//------------------------
yii\bootstrap\Modal::begin([
    'id'    => 'modalNewAutomobile',
    'header' => Yii::t('app', 'Create New Automobile'),
    'size'  => yii\bootstrap\Modal::SIZE_LARGE,
]);
?>

<div class="modal-body">
    <!-- Some modal content here -->
    <div id="modalContent">
        <?= Yii::$app->controller->renderPartial('/automobile/_form', [
                'model'=> new app\models\AutomobileForm(),
                'change_form' => true,
                'create' => false,
                'view' => false,
            ]) ?>
    </div>

</div>
<?php
    yii\bootstrap\Modal::end();
?>



<?php
//------------------------------------------------------------------------------
// Variables
//------------------------------------------------------------------------------
$getAutomobilesUrl = \yii\helpers\Url::to(['/order/get-automobiles']);
$submitPartFormUrl = \yii\helpers\Url::to(['/part/submit-part-form-url']);
//------------------------------------------------------------------------------
// Javascript
//------------------------------------------------------------------------------
$jsBlock = <<< JS


// Populate Automobiles
$('#customer_id').on('select2:select', function (e) {
    //console.log('here');
    // console.log($('#part_id').value);
    // console.log(e.params.data.id);
    updateAutomobiles();


});


function updateAutomobiles() {
    let selectValue = $('#customer_id').val();
    $('#automobile_id').empty();
    //getting automobiles
    $.ajax({
        type: 'POST',
        url: '$getAutomobilesUrl',
        data: {id: selectValue},
        success: function(data)
        {
            data = JSON.parse(data);
            for(let key in data) {
                if(key == 'error') {
                    alert(data[key]);
                    return;
                }
                let newOption = new Option(data[key], key);
                $('#automobile_id').append(newOption);
            }
            $('#automobile_id').attr('disabled',false);
            $('#new_automobile').attr('disabled',false);
            $('#odometer_reading_input').attr('disabled',false);

        },

        error: function( xhr, status, errorThrown )
        {
            console.log('Error: ' + errorThrown );
            console.log('Status: ' + status );
            console.dir( xhr );
        },
    });

}
JS;




$this->registerJs($jsBlock, \yii\web\View::POS_END);
?>
