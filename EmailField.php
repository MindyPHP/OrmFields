<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Fields;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EmailField.
 */
class EmailField extends CharField
{
    /**
     * @var bool
     */
    public $checkMX = false;
    /**
     * @var bool
     */
    public $checkHost = false;

    /**
     * @return array
     */
    public function getValidationConstraints()
    {
        return array_merge(parent::getValidationConstraints(), [
            new Assert\Email([
                'checkMX' => $this->checkMX,
                'checkHost' => $this->checkHost,
            ]),
        ]);
    }
}
