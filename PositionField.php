<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Fields;

use Mindy\Orm\ModelInterface;
use Mindy\Orm\TreeModel;

/**
 * Class PositionField.
 */
class PositionField extends IntField
{
    /**
     * @var \Closure
     */
    public $callback;

    /**
     * @param ModelInterface $model
     * @param $value
     */
    public function beforeInsert(ModelInterface $model, $value)
    {
        if (is_null($value) || '' === $value) {
            $model->setAttribute($this->getName(), $this->getNextPosition($model));
        }
    }

    /**
     * @param ModelInterface $model
     *
     * @return int
     */
    public function getNextPosition(ModelInterface $model)
    {
        if ($this->callback instanceof \Closure) {
            $qs = $this->callback->__invoke($model);
            if (!is_object($qs) && is_numeric($qs)) {
                return $qs;
            }
        } else {
            $qs = $model->objects();
            if ($model instanceof TreeModel && !empty($model->parent_id)) {
                $qs->filter(['parent_id' => $model->parent_id]);
            }
        }

        $max = (int) $qs->max($this->getAttributeName());

        return $max ? $max + 1 : 1;
    }
}
