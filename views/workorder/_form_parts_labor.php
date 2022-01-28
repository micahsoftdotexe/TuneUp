<?php
use yii\helpers\Html;
?>
<h1> Parts and Labor </h1>
<div id="partsdiv">
    <h2>Parts</h2>
    <?= yii\grid\GridView::widget([
        'id' => 'partGrid',
        'dataProvider' => $partDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'part_number',
            'price',
            'quantity',
            [
                'label' => 'Quantity Type',
                'attribute' => 'quantity_type_id',
                'value'=> function($model) {
                    return \app\models\QuantityType::findOne(['id' => $model->quantity_type_id ])->description;
                }
            ]
        ]
    ])?>
    <?= Html::button('Add Part', [
        'id' => 'new_part_button',
        'disabled' => !$update,
        'class' => 'btn btn-default btn-outline-secondary',
        'data' => [
            'toggle' => 'modal',
            'target' => '#modalNewPart',
        ],]) ?>   
</div>
<div id="labordiv">
    <h2>Labor</h2>
    <?= yii\grid\GridView::widget([
        'id' => 'laborGrid',
        'dataProvider' => $laborDataProvider
    ])?>
</div>

<?php
//------------------------
// Add new Customer
//------------------------
yii\bootstrap\Modal::begin([
    'id'    => 'modalNewPart',
    'header' => Yii::t('app', 'Create New Customer'),
    'size'  => yii\bootstrap4\Modal::SIZE_LARGE,
]);
?>

<div class="modal-body">
    <!-- Some modal content here -->
    <div id="modalContent">
        <?= Yii::$app->controller->renderPartial('/part/_form', [
                'model'=> new app\models\Part(),
                'change_form' => true,
                'workorder_id' => $model->id,
            ]) ?>
    </div>

</div>
<?php
    yii\bootstrap\Modal::end();
?>

<?php
    $jsBlock = <<< JS
        $('#modalNewPart').on("beforeSubmit",function(event){
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'post',
                dataType: 'json',
                data: $(this).serializeArray(),
                success: function (returnData) {
                    console.log(returnData);

                }, error: function( xhr, status, errorThrown ) {
                    console.log("Error: " + errorThrown );
                    console.log("Status: " + status );
                    console.dir( xhr );
                },
            })
        })
    JS;
