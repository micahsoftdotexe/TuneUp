<?php
// use Yii;
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome to the <?= Yii::$app->name ?> <br/>Order System</h1>
        
        
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Orders</h2>
                <p>Get a list of and edit orders.</p>
                <p><?=Html::a('Orders', ['order/index'], [
                    'class' => 'btn btn-lg btn-success',
                    'id' => 'order-button',
                    ]) ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Customers</h2>
                <p>Get a list of and edit customers.</p>
                <p><?=Html::a('Customer', ['customer/index'], [
                    'class' => 'btn btn-lg btn-success',
                    'id' => 'order-button',
                    ]) ?></p>
            </div>
            <!-- <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div> -->
        </div>

    </div>
</div>
