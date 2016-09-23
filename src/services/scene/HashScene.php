<?php

namespace plato\service;

use plato\libs\FormBulider;

/**
 * Class HashScene
 * @package plato\service
 *
 * hash结构的数据场景
 *
 * @property $project
 * @property $name
 * @property $conf
 * @property $data
 */
class HashScene extends AbstractScene
{
    public function udfProperties()
    {
        return ['conf', 'data'];
    }

    public function filter($property, $value)
    {
        switch ($property) {
            case"conf":
                return json_encode($value, true);
                break;

            case"data":
                $data = [];
                foreach ($value as $row) {
                    $data[$row['key']] = $row['value'];
                }

                return json_encode($data, true);
                break;
        }
    }

    public function detail()
    {
        return [
            'alias'     => $this->alias(),
            'conf'      => json_decode($this->rtProperty('conf'), true),
            'data'      => $this->filledData(),
            'formViews' => $this->formViews(),
        ];
    }

    protected function filledData()
    {
        $conf = json_decode($this->rtProperty('conf'), true);
        if (empty($conf)) {
            return null;
        }

        $data = json_decode($this->rtProperty('data'), true);

        foreach ($conf as $key => &$alone) {
            $alone['value'] = isset($data[$key]) ? $data[$key] : '';
        }

        return $conf;
    }

    protected function formViews()
    {
        $formData = $this->filledData();
        if (empty($formData)) {
            return null;
        }
        $formBulider = new FormBulider($formData);

        return $formBulider->buildForm();
    }
}