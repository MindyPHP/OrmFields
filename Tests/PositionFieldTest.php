<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Fields\Tests;

use Mindy\Orm\Fields\PositionField;
use Mindy\Orm\ManagerInterface;
use Mindy\Orm\ModelInterface;
use Mindy\Orm\TreeModel;
use PHPUnit\Framework\TestCase;

abstract class TestModel implements ModelInterface
{
    abstract public function objects();
}

abstract class TreeTestModel extends TreeModel
{
    public $parent_id = 5;

    abstract public function objects();
}

class PositionFieldTest extends TestCase
{
    public function testPositionFieldCallback()
    {
        $i = 0;
        $callback = function () use ($i) {
            global $i;
            $i += 1;

            return $i;
        };

        $field = new PositionField([
            'callback' => $callback,
        ]);
        $field->setName('test');

        $position = -1;

        $model = $this
            ->getMockBuilder(TestModel::class)
            ->getMock();

        $model->method('setAttribute')
            ->will($this->returnCallback(function ($attr, $value) use (&$position) {
                $position = $value;
            }));

        $this->assertEquals(1, $field->getNextPosition($model));
        $this->assertEquals(2, $field->getNextPosition($model));
        $this->assertEquals(3, $field->getNextPosition($model));
    }

    public function testPositionFieldModel()
    {
        $field = new PositionField();
        $field->setName('test');

        $position = -1;

        $manager = $this
            ->getMockBuilder(ManagerInterface::class)
            ->getMock();
        $manager
            ->method('max')
            ->willReturn(41);

        $model = $this->getMockBuilder(TestModel::class)->getMock();

        $model->method('objects')->willReturn($manager);

        $model->method('setAttribute')->will(
            $this->returnCallback(function ($attr, $value) use (&$position) {
                $position = $value;
            })
        );

        $field->beforeInsert($model, null);
        $this->assertSame(42, $position);
        $this->assertEquals(42, $field->getNextPosition($model));
    }

    public function testPositionFieldTreeModel()
    {
        $field = new PositionField();
        $field->setName('test');

        $position = -1;

        $manager = $this
            ->getMockBuilder(ManagerInterface::class)
            ->getMock();
        $manager
            ->method('max')
            ->willReturn(41);

        $model = $this->getMockBuilder(TreeTestModel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->assertInstanceOf(TreeModel::class, $model);

        $model->method('objects')->willReturn($manager);

        $model->method('setAttribute')->will(
            $this->returnCallback(function ($attr, $value) use (&$position) {
                $position = $value;
            })
        );

        $field->beforeInsert($model, null);
        $this->assertSame(42, $position);
        $this->assertEquals(42, $field->getNextPosition($model));
    }
}
