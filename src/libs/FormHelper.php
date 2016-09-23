<?php

namespace plato\libs;


class FormBulider
{
    private $eles;
    private $formKey;
    private $multi;

    const TYPE_INPUT    = 'input';
    const TYPE_FILE     = 'file';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_SELECT   = 'select';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_DATETIME = 'datetime';
    const FORMKEY       = 'PlatoForm';

    public function __construct($eles, $multi = false)
    {
        $this->eles    = $this->transEles($eles);
        $this->formKey = self::FORMKEY;
        $this->multi   = $multi;
    }

    private function transEles($eles)
    {
        foreach ($eles as $key => &$ele) {
            $ele['key'] = $key;
        }

        return $eles;
    }

    public function buildForm()
    {
        $html = '{formEles}';

        $formEles = '';

        foreach ($this->eles as $ele) {
            $formEles .= $this->buildEle($ele);
        }

        return StrHelper::interpolate($html, [
            'formEles' => $formEles,
        ]);
    }

    private function buildEle($ele)
    {
        $type = strtolower($ele['type']);
        switch ($type) {
            case self::TYPE_INPUT:
                return $this->buildInput($ele);
                break;
            case self::TYPE_FILE:
                return $this->buildFile($ele);
                break;
            case self::TYPE_SELECT:
                return $this->buildSelect($ele);
                break;
            case self::TYPE_CHECKBOX:
                return $this->buildCheckbox($ele);
                break;
            case self::TYPE_TEXTAREA:
                return $this->buildTextarea($ele);
                break;
            case self::TYPE_DATETIME:
                return $this->buildDatetime($ele);
                break;
            default:
                throw new \ErrorException("不支持该类型," . $type);
                break;
        }
    }

    private function buildInput($ele)
    {
        $tpl = '
            <tr>
                <td>
                    {title}
                    {requiredHtml}
                </td>
                <td>
                    <input type="text" name="{name}"  value="{value}" class="{class} form-control" validator="{validator}" {requireInput}   />
                   ' . $this->getDescHtml() . '
                 </td>
            </tr>';

        $ele = $this->buildEleHtml($ele);

        return StrHelper::interpolate($tpl, $ele);
    }

    private function buildFile($ele)
    {
        $tpl = '
            <tr>
                <td>
                    {title}
                    {requiredHtml}
                </td>
                <td>
                    <input type="file" name="{name}"  class="{class} form-control"  validator="{validator}" {requireInput}   />
                   ' . $this->getDescHtml() . '
                 </td>
            </tr>';

        $ele = $this->buildEleHtml($ele);

        return StrHelper::interpolate($tpl, $ele);
    }

    private function buildTextarea($ele)
    {
        $tpl = '
           <tr>
               <td>
                  {title}
                  {requiredHtml}
               </td>
               <td>
                  <textarea name="{name}"  row="4" validator="{validator}"   class="{class}  form-control"  {requireInput}  >{value}</textarea>
                   ' . $this->getDescHtml() . '
                </td>
            </tr>';

        $ele = $this->buildEleHtml($ele);

        return StrHelper::interpolate($tpl, $ele);
    }

    private function buildSelect($ele)
    {
        $tpl = '
            <tr>
                 <td>
                    {title}
                    {requiredHtml}
                 </td>
                 <td>
                    <select  name="{name}" validator="{validator}" class="{class}" {requireInput}  >
                        {optionStr}
                    </select>
                    ' . $this->getDescHtml() . '
                 </td>
            </tr>';

        $ele = $this->buildEleHtml($ele);
        $ele = $this->addSelectHtml($ele);

        return StrHelper::interpolate($tpl, $ele);
    }

    private function buildCheckbox($ele)
    {
        $tpl = '
            <tr>
                 <td>
                    {title}
                    {requiredHtml}
                 </td>
                <td>
                    {checkboxStr}
                    ' . $this->getDescHtml() . '
                </td>
            </tr>';

        $ele = $this->buildEleHtml($ele);
        $ele = $this->addCheckboxHtml($ele);

        return StrHelper::interpolate($tpl, $ele);
    }

    private function buildDatetime($ele)
    {

        $tpl = '
            <tr>
                <td>
                    {title}
                    {requiredHtml}
                </td>
                <td>
                    <input type="text" name="{name}" ui-date-time  view="date" value="{value}" partial="true" ng-model="' . uniqid() . '_{datetime}"  class="form-control {class}"  validator="{validator}" {requireInput}  />
                   ' . $this->getDescHtml() . '
                 </td>
            </tr>';

        $ele = $this->buildEleHtml($ele);

        return StrHelper::interpolate($tpl, $ele);
    }

    private function buildEleHtml($ele)
    {
        $ele['title'] = $ele['name'];
        $ele['name']  = "{$this->formKey}[" . $ele["key"] . "]";
        if ($this->multi) {
            $ele['name'] = $ele['name'] . '[]';
        }

        $ele['angularName']  = $this->formKey . "." . $ele["key"];
        $ele['class']        = $this->formKey . "_" . $ele["key"];
        $ele['value']        = $ele["value"];
        $ele['requiredHtml'] = $ele['required'] == true ? $this->getRequireHtml() : "";
        $ele['requireInput'] = $ele['required'] == true ? 'required' : "";

        return $ele;
    }

    private function addCheckboxHtml($ele)
    {
        $checkBoxTpl = '
            <label class="checkbox-inline">
               <input type="checkbox" value="{value}" name="{name}" {checked}   class="{class}"> {title}
            </label>';
        $checkboxStr = '';


        if (is_array($ele['options'])) {
            foreach ($ele['options'] as $option) {
                $checkboxStr .= StrHelper::interpolate($checkBoxTpl, [
                    'checked'     => in_array($option['value'], explode(',', $ele['value'])) ? "checked=true" : '',
                    'name'        => "{$this->formKey}[" . $ele["key"] . "]",
                    'value'       => $option['value'],
                    'title'       => $option['title'],
                    'class'       => $this->formKey . "_" . $ele["key"],
                    'angularName' => $this->formKey . "." . $ele["key"] . "['" . $option['value'] . "']",
                ]);
            }
        }
        $ele['checkboxStr'] = $checkboxStr;

        return $ele;
    }

    private function addSelectHtml($ele)
    {
        $optionStr = '';
        if (is_array($ele['options'])) {
            foreach ($ele['options'] as $option) {

                if ($ele['value'] == $option['value']) {
                    $selected = "selected='selected'";
                } else {
                    $selected = '';
                }

                $optionStr .= "<option value='" . $option['value'] . "' $selected >" . $option['title'] . "</option>";
            }
        }
        $ele['optionStr'] = $optionStr;

        return $ele;
    }

    private function getRequireHtml()
    {
        return '<span style="color: red;">*</span>';
    }

    private function getDescHtml()
    {
        return '<span class="description">{description}</span>';
    }

}