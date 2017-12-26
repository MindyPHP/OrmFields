<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Fields\Tests;

use Mindy\Orm\Fields\IpField;
use PHPUnit\Framework\TestCase;

class IpFieldTest extends TestCase
{
    public function testIpV4()
    {
        $field = new IpField();

        $field->setValue('foo');
        $this->assertFalse($field->isValid());

        $field->setValue('127.0.0.1');
        $this->assertTrue($field->isValid());

        $field->setValue('127.0.0');
        $this->assertFalse($field->isValid());

        $field->setValue('127.0.0.1.1');
        $this->assertFalse($field->isValid());
    }

    public function testIpV6()
    {
        $field = new IpField([
            'version' => 6,
        ]);

        $field->setValue('0:0:0:0:0:0:0');
        $this->assertFalse($field->isValid());

        $field->setValue('0:0:0:0:0:0:0:1');
        $this->assertTrue($field->isValid());
    }

    /**
     * @expectedException \LogicException
     */
    public function testIpWrong()
    {
        $field = new IpField([
            'version' => 8,
        ]);
    }
}
