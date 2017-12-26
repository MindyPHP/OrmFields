<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Fields\Tests;

use Mindy\Orm\Fields\EmailField;
use PHPUnit\Framework\TestCase;

class EmailFieldTest extends TestCase
{
    public function testEmail()
    {
        $field = new EmailField();

        $field->setValue('foo@bar.com');
        $this->assertTrue($field->isValid());

        $field->setValue('123');
        $this->assertFalse($field->isValid());
    }
}
