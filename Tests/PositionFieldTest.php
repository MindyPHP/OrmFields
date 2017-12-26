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
use Mindy\Orm\ModelInterface;
use PHPUnit\Framework\TestCase;

class PositionFieldTest extends TestCase
{
    public function testPositionField()
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

        $model = $this
            ->getMockBuilder(ModelInterface::class)
            ->getMock();

        $this->assertInstanceOf(ModelInterface::class, $model);
        $this->assertEquals(1, $field->getNextPosition($model));
        $this->assertEquals(2, $field->getNextPosition($model));
        $this->assertEquals(3, $field->getNextPosition($model));
    }
}
