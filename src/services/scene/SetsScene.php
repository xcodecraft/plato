<?php

namespace plato\service;

use plato\libs\FormBulider;

/**
 * Class SetsScene
 * @package plato\service
 *
 * sets结构的数据场景
 *
 * @property $project
 * @property $name
 * @property $rptstruct
 * @property $data
 */
class SetsScene extends AbstractScene
{
    public function filter($property, $value)
    {
        switch ($property) {
            case"rptstruct":
                return json_encode($value, true);
                break;

            case"data":
                // value  = PlatoForm%5Binput1%5D%5B%5D=1111&PlatoForm%5Bselect2%5D%5B%5D=1&PlatoForm%5Binput1%5D%5B%5D=2222&PlatoForm%5Bselect2%5D%5B%5D=1
                parse_str($value, $data);
                $data = $data['PlatoForm'];
                if (empty($data)) {
                    throw new \InvalidArgumentException("参数格式错误");
                }
                $keys = array_keys($data);
                $len  = count($data[$keys[0]]);
                $ret  = [];
                for ($i = 0; $i < $len; $i++) {
                    $single = [];
                    foreach ($keys as $key) {
                        $single[$key] = $data[$key][$i];
                    }
                    array_push($ret, $single);
                }

                return json_encode($ret, true);
                break;
        }
    }

    public function udfProperties()
    {
        return ['rptstruct', 'data'];
    }

    public function detail()
    {
        return [
            'alias'     => $this->alias(),
            'rptstruct' => json_decode($this->rtProperty('rptstruct'), true),
            'formViews' => $this->formViews(),
            'newForm'   => $this->newForm(),
        ];
    }

    protected function newForm()
    {
        $rptstruct = json_decode($this->rtProperty('rptstruct'), true);
        if (empty($rptstruct)) {
            return $rptstruct;
        }

        $formBulider = new FormBulider(
            $rptstruct, true
        );

        return $formBulider->buildForm();
    }

    /**
     * form视图 HTML
     */
    public function formViews()
    {
        $lists = json_decode($this->rtProperty('data'), true);
        $ret   = [];
        foreach ($lists as $data) {

            $formBulider = new FormBulider(
                $this->filledData($data), true
            );
            array_push($ret, [
                'html' => $formBulider->buildForm()
            ]);
        };

        return $ret;
    }

    protected function filledData($data)
    {
        $conf = json_decode($this->rtProperty('rptstruct'), true);
        if (empty($conf)) {
            return null;
        }

        foreach ($conf as $key => &$alone) {
            $alone['value'] = isset($data[$key]) ? $data[$key] : '';
        }

        return $conf;
    }

}