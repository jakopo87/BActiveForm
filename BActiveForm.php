<?php

/*
 * The MIT License
 *
 * Copyright (c) 2014 Jacopo Galati <jacopo.galati@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class BActiveForm extends CWidget
{

    /**
     * Action url of the form.
     * @var string
     */
    public $action;

    /**
     * Method of the form
     * @var string
     */
    public $method;

    /**
     * List of attributes and other options:<br/>
     * boolean <b>labels</b>: render the label of the controls;
     * see{@link BHtml::openForm()};
     * @var array
     */
    public $htmlOptions;

    /**
     * Extract a value from the array.
     * @param string $option Index of the value;
     * @param array $htmlOptions List of attributes;
     * @return mixed
     */
    private function getOption($option, &$htmlOptions)
    {
        $value = isset($htmlOptions[$option]) ? $htmlOptions[$option] : null;
        unset($htmlOptions[$option]);

        return $value;
    }

    /**
     * Render the label of a control.
     * @param object $model Model object;
     * @param string $attribute Name of the attribute;
     * @param array $htmlOptions List of attributes;
     * @return string
     */
    private function renderLabel($model, $attribute, &$htmlOptions)
    {
        if($this->getOption('label', $this->htmlOptions) !== false && $this->getOption('label', $htmlOptions) !== false)
        {
            return BHtml::label($model->getAttributeLabel($attribute), $this->getOption('labelOptions', $htmlOptions));
        }
        else
        {
            return '';
        }
    }

    /**
     * Open the form.
     */
    public function init()
    {
        $this->htmlOptions['action'] = $this->action;
        $this->htmlOptions['method'] = $this->method;

        echo BHtml::openForm($this->htmlOptions);
    }

    /**
     * Closes the form.
     */
    public function run()
    {
        echo BHtml::closeForm();
    }

    /**
     * Render a text field.
     * @param object $model Model object;
     * @param string $attribute Name of the attribute;
     * @param array $htmlOptions    List of attributes and other options:<br/>
     *                              boolean <b>label</b>: render the label of the controls;
     *                              array <b>labelOptions</b>: List of attributes and other options for the label, see
     *                              {@link BHtml::label()};
     *                              see {@link BHtml::input()};
     *                              see{@link BHtml::tag()};
     * @return string
     */
    public function textField($model, $attribute, $htmlOptions = array())
    {
        CHtml::resolveNameID($model, $attribute, $htmlOptions);

        $render = BHtml::openFormGroup();

        $render.=$this->renderLabel($model, $attribute, $htmlOptions);

        $render.=BHtml::input('text', $htmlOptions['name'], $htmlOptions);

        $render.=BHtml::closeFormGroup();

        return $render;
    }

    /**
     * Render a password field.
     * @param object $model Model object;
     * @param string $attribute Name of the attribute;
     * @param array $htmlOptions    List of attributes and other options:<br/>
     *                              boolean <b>label</b>: render the label of the control;
     *                              array <b>labelOptions</b>: List of attributes and other options for the label, see
     *                              {@link BHtml::label()};
     *                              see {@link BHtml::input()};
     *                              see{@link BHtml::tag()};
     * @return string
     */
    public function passwordField($model, $attribute, $htmlOptions = array())
    {
        CHtml::resolveNameID($model, $attribute, $htmlOptions);
        $htmlOptions['value'] = CHtml::resolveValue($model, $attribute);

        $render = BHtml::openFormGroup();

        $render.=$this->renderLabel($model, $attribute, $htmlOptions);

        $render.=BHtml::input('password', $htmlOptions['name'], $htmlOptions);

        $render.=BHtml::closeFormGroup();

        return $render;
    }

    /**
     * Render a text area.
     * @param object $model Model object;
     * @param string $attribute Name of the attribute;
     * @param array $htmlOptions    List of attributes and other options:<br/>
     *                              boolean <b>label</b>: render the label of the control;
     *                              array <b>labelOptions</b>: List of attributes and other options for the label, see
     *                              {@link BHtml::label()};
     * @return string
     */
    public function textArea($model, $attribute, $htmlOptions = array())
    {
        CHtml::resolveNameID($model, $attribute, $htmlOptions);
        $htmlOptions['value'] = CHtml::resolveValue($model, $attribute);

        $render = BHtml::openFormGroup();

        $render.=$this->renderLabel($model, $attribute, $htmlOptions);

        $render.=BHtml::textArea($htmlOptions['name'], $model->attributes[$attribute], $htmlOptions);

        $render.=BHtml::closeFormGroup();

        return $render;
    }

    /**
     * Render a checkbox list.
     * @param object $model Model object;
     * @param string $attribute Name of the attribute;
     * @param array $htmlOptions    List of attributes and other options:<br/>
     *                              array <b>labelOptions</b>: List of attributes and other options for the label, see
     *                              {@link BHtml::label()};
     * @return string
     */
    public function checkboxList($model, $attribute, $data, $htmlOptions = array())
    {
        CHtml::resolveNameID($model, $attribute, $htmlOptions);

        $render = BHtml::openFormGroup();

        $value = CHtml::resolveValue($model, $attribute);
        if(!is_array($value))
        {
            $value = array($value);
        }

        $render.=BHtml::checkBoxList($htmlOptions['name'], $value, $data, $htmlOptions);

        $render.=BHtml::closeFormGroup();
        
        return $render;
    }

}
