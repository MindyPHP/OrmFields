<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Orm\Fields;

@trigger_error('The '.__NAMESPACE__.' class is deprecated since version 3.0 and will be removed in 4.0.', E_USER_DEPRECATED);

/**
 * Class TreeForeignField.
 */
class TreeForeignField extends ForeignField
{
    /**
     * @param string $fieldClass
     *
     * @return false|null|string
     */
    public function getFormField($fieldClass = '\Mindy\Form\Fields\SelectField')
    {
        if ($this->primary || false === $this->editable) {
            return;
        }

        $relatedModel = $this->getRelatedModel();

        if (!empty($this->choices)) {
            $choices = $this->choices;
        } else {
            $choices = function () use ($relatedModel) {
                $list = ['' => ''];

                $qs = $relatedModel->objects()->order(['root', 'lft']);
                $parents = $qs->all();
                foreach ($parents as $model) {
                    $level = $model->level ? $model->level - 1 : $model->level;
                    $list[$model->pk] = $level ? str_repeat('—', $level).' '.$model->name : $model->name;
                }

                return $list;
            };
        }

        $model = $this->getModel();
        $disabled = [];
        if (get_class($model) == get_class($relatedModel) && false === $relatedModel->getIsNewRecord()) {
            $disabled[] = $model->pk;
        }

        $value = $model->parent_id;
        if (empty($value)) {
            $value = $this->default ? $this->default : null;
        }

        return [
            'disabled' => $disabled,
            'choices' => $choices,
            'class' => $fieldClass,
            'required' => $this->isRequired(),
            'name' => $this->name,
            'label' => $this->verboseName,
            'hint' => $this->helpText,
            'value' => $value,
        ];
    }
}
