<?php
namespace models;

use app\models\Part;

class PartTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _fixtures()
    {   return [
            'Orders' => [
                'class' => \app\tests\fixtures\OrderFixture::class,
                'dataFile' => codecept_data_dir() . 'order.php',
            ],
            'Parts' => [
                'class' => \app\tests\fixtures\PartFixture::class,
                'dataFile' => codecept_data_dir() . 'part.php',
                //'depends' => ['Orders'],
            ],
            'Labor' => [
                'class' => \app\tests\fixtures\LaborFixture::class,
                'dataFile' => codecept_data_dir() . 'labor.php',
            ],
            'Owns' => [
                'class' => \app\tests\fixtures\OwnFixture::class,
                'dataFile' => codecept_data_dir() . 'owns.php',
                //'depends' => ['Parts'],
            ],
            'Customers' => [
                'class' => \app\tests\fixtures\CustomerFixture::class,
                'dataFile' => codecept_data_dir() . 'customer.php',
                //'depends' => ['Owns'],
            ],
            'Automobiles' => [
                'class' => \app\tests\fixtures\AutomobileFixture::class,
                'dataFile' => codecept_data_dir() . 'automobile.php',
                //'depends' => ['Owns'],
            ],
        ];
    }

    // tests
    public function testValidation()
    {
        // without price
        $part = new Part();
        $part->margin = 1;
        $part->description = 'test';
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->order_id = 1;
        $this->tester->assertFalse($part->validate());

        // without description
        $part = new Part();
        $part->margin = 1;
        $part->price = 1;
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->order_id = 1;
        $this->tester->assertFalse($part->validate());

        // without margin
        $part = new Part();
        $part->price = 1;
        $part->description = 'test';
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->order_id = 1;
        $this->tester->assertFalse($part->validate());

        // without part_number
        $part = new Part();
        $part->margin = 1;
        $part->description = 'test';
        $part->price = 1;
        $part->quantity = 1;
        $part->order_id = 1;
        $this->tester->assertFalse($part->validate());

        // without quantity
        $part = new Part();
        $part->margin = 1;
        $part->description = 'test';
        $part->part_number = 'test';
        $part->price = 1;
        $part->order_id = 1;
        $this->tester->assertFalse($part->validate());

        // without order_id
        $part = new Part();
        $part->margin = 1;
        $part->description = 'test';
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->price = 1;
        $this->tester->assertFalse($part->validate());

        // order_id not found
        $part = new Part();
        $part->margin = 1;
        $part->description = 'test';
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->price = 1;
        $part->order_id = 999;
        $this->tester->assertFalse($part->validate());

        // order_id not integer
        $part = new Part();
        $part->margin = 1;
        $part->description = 'test';
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->price = 1;
        $part->order_id = 'test';
        $this->tester->assertFalse($part->validate());

        // quantity_type_id not integer
        $part = new Part();
        $part->margin = 1;
        $part->description = 'test';
        $part->part_number = 'test';
        $part->quantity = 'test';
        $part->price = 1;
        $part->order_id = 1;
        $part->quantity_type_id = 'test';
        $this->tester->assertFalse($part->validate());

        // price not number
        $part = new Part();
        $part->margin = 1;
        $part->description = 'test';
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->price = 'test';
        $part->order_id = 1;
        $this->tester->assertFalse($part->validate());

        // margin not number
        $part = new Part();
        $part->margin = 'test';
        $part->description = 'test';
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->price = 1;
        $part->order_id = 1;
        $this->tester->assertFalse($part->validate());

        // quantity not a number
        $part = new Part();
        $part->margin = 'test';
        $part->description = 'test';
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->price = 1;
        $part->order_id = 1;
        $this->tester->assertFalse($part->validate());

        // part_number not a string
        $part = new Part();
        $part->margin = 1;
        $part->description = 'test';
        $part->part_number = 1;
        $part->quantity = 1;
        $part->price = 1;
        $part->order_id = 1;
        $this->tester->assertFalse($part->validate());

        // description not a string
        $part = new Part();
        $part->margin = 1;
        $part->description = 1;
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->price = 1;
        $part->order_id = 1;
        $this->tester->assertFalse($part->validate());


        //valid
        $part = new Part();
        $part->margin = 1;
        $part->description = 'test';
        $part->part_number = 'test';
        $part->quantity = 1;
        $part->price = 1;
        $part->order_id = 1;
        $part->quantity_type_id = 1;
        $this->tester->assertTrue($part->validate());
    }

    public function testGetTotal()
    {
        $part = Part::findOne(1);
        $this->tester->assertEquals(110, $part->getTotal());
        $part = Part::findOne(2);
        $this->tester->assertEquals(50, $part->getTotal());
    }
}
