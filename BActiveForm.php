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
     * Folder of published assets.
     * @var string
     */
    private $assetFolder;

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
     * Append one or more classes to $htmlOptions.
     * @param mixed $class  It can be of two types:<br/>
     *                      <b>string</b>: name of the class to be added;
     *                      <b>array</b>: Key-value pair in which the key is the name of the class, and the vlue is an 
     *                      expression that determines if the class should be added;
     * @param array $htmlOptions    List of attributes;
     */
    private function addClass($class, &$htmlOptions)
    {
        if(is_string($class))
        {
            $class = array($class => true);
        }

        foreach($class as $c => $exp)
        {
            if($exp === true)
            {
                $htmlOptions['class'] = isset($htmlOptions['class']) ? "{$htmlOptions['class']} $c" : $c;
            }
        }
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
            return BHtml::inputLabel($model->getAttributeLabel($attribute), $this->getOption('labelOptions', $htmlOptions));
        }
        else
        {
            return '';
        }
    }

    /**
     * Set the validation state.
     * @param object $model Model object;
     * @param string $attribute Name of the attribute;
     * @param array $formGroupOptions List of attributes of the form group;
     * @param array $inputOptions List of attributes of the input control;
     */
    private function setValidationState($model, $attribute, &$formGroupOptions, &$inputOptions)
    {
        if($model->hasErrors($attribute))
        {
            $inputOptions['helpText'] = implode($model->errors[$attribute]);
            $this->addClass('has-error', $formGroupOptions);
        }
    }

    /**
     * Open the form.
     */
    public function init()
    {
        $this->htmlOptions['action'] = $this->action;
        $this->htmlOptions['method'] = $this->method;

        /* HACK: It tries to use C:\xampp\php\assets as basepath */
        Yii::app()->getAssetManager()->setBasePath(Yii::app()->basePath);

        $this->assetFolder = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');

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

        $formGroupOptions = array();
        $this->setValidationState($model, $attribute, $formGroupOptions, $htmlOptions);

        $render = BHtml::openFormGroup($formGroupOptions);

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

        $formGroupOptions = array();
        $this->setValidationState($model, $attribute, $formGroupOptions, $htmlOptions);

        $render = BHtml::openFormGroup($formGroupOptions);

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

        $formGroupOptions = array();
        $this->setValidationState($model, $attribute, $formGroupOptions, $htmlOptions);

        $render = BHtml::openFormGroup($formGroupOptions);

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

    /**
     * Render a radiobutton group.
     * @param object $model Model object;
     * @param string $attribute Name of the attribute;
     * @param array $htmlOptions    List of attributes and other options:<br/>
     *                              array <b>labelOptions</b>: List of attributes and other options for the label, see
     *                              {@link BHtml::label()};
     * @return string
     */
    public function radioButtonList($model, $attribute, $data, $htmlOptions = array())
    {
        CHtml::resolveNameID($model, $attribute, $htmlOptions);

        $render = BHtml::openFormGroup();

        $value = CHtml::resolveValue($model, $attribute);

        $render.=BHtml::radioButtonList($htmlOptions['name'], $value, $data, $htmlOptions);

        $render.=BHtml::closeFormGroup();

        return $render;
    }

    /**
     * Render a text editor
     * @param object $model Model object;
     * @param string $attribute Name of the attribute;
     * @param array $htmlOptions    List of attributes and other options:<br/>
     *                              array <b>editorOptions</b>: options for the wysihtml5 plugin, 
     *                              see {@link https://github.com/xing/wysihtml5};
     *                              see {@link BHtml::textArea()};
     * @return string
     */
    public function textEditor($model, $attribute, $htmlOptions = array())
    {
        CHtml::resolveNameID($model, $attribute, $htmlOptions);

        $this->addClass('wysihtml5-textarea', $htmlOptions);

        $editorOptions = $this->getOption('editorOptions', $htmlOptions, true);

        $ruleSet = $this->getOption('ruleSet', $editorOptions, true);
        if($ruleSet === NULL)
        {
            $ruleSet = 'simple';
        }
        else if($ruleSet === 'advanced')
        {
            $editorOptions['stylesheets'] = "{$this->assetFolder}/wysihtml5/stylesheet.css";
        }

        $editorOptionsJSON = json_encode($editorOptions !== NULL ? $editorOptions : array());

        $render = $this->textArea($model, $attribute, $htmlOptions);

        Yii::app()->clientScript->registerScriptFile("{$this->assetFolder}/wysihtml5/parser_rules/{$ruleSet}.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile("{$this->assetFolder}/wysihtml5/wysihtml5-0.3.0.min.js", CClientScript::POS_END);

        Yii::app()->clientScript->registerScript('wysihtml5-' . $htmlOptions['id'], <<<JS
var editor = new wysihtml5.Editor("{$htmlOptions['id']}",$editorOptionsJSON);
JS
                , CClientScript::POS_READY);
        return $render;
    }

    /**
     * Render a text input with a datetime picker.
     * @param object $model Model object;
     * @param string $attribute Name of the attribute;
     * @param array $htmlOptions    List of attributes and other options:<br/>
     *                              array <b>dateTimePickerOptions</b>: List of options for the jQuery plugin;<br/>
     *                              see {@link BHtml::tag()};
     * @return string
     */
    public function dateTimePicker($model, $attribute, $htmlOptions = array())
    {
        $dateTimePickerOptions = json_encode($this->getOption('dateTimePickerOptions', $htmlOptions));

        CHtml::resolveNameID($model, $attribute, $htmlOptions);

        $render = BHtml::input('text', $htmlOptions['name'], $htmlOptions);

        $min = defined('YII_DEBUG') ? '.min' : '';
        Yii::app()->clientScript->registerCssFile("{$this->assetFolder}/bootstrap-datetimepicker/css/bootstrap-datetimepicker{$min}.css");
        Yii::app()->clientScript->registerScriptFile("{$this->assetFolder}/bootstrap-datetimepicker/js/moment-with-langs.min.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile("{$this->assetFolder}/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScript('dateTimePicker-' . $htmlOptions['id'], <<<JS
$('#{$htmlOptions['id']}').datetimepicker({$dateTimePickerOptions});
JS
                , CClientScript::POS_READY);
        return $render;
    }

}
