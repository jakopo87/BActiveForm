BActiveForm
===========

Active Form widget based on BootstrapYii.

Installation
------------
Create a `bActiveForm` folder into the Yii's folder of extentions  (default: protected/extentions).

Usage
-----
To initialize the widget, do as follow:
````php
    /* $this refers to the current controller */
    $form = $this->beginWidget('ext.bActiveForm.BActiveForm', array(
        'action' => 'url/to/action',
        'method' => 'METHOD',
    ));
    ...
````

To create an input controls:
````php
    echo $form->textField($model,$attribute);
````
assuming `$form=new ContactForm();` and `$attribute='name'`, the generated markup will like this:
````html
    <div class="form-group">
        <label class="control-label">Name</label>
        <input name="ContactForm[name]" id="ContactForm_name" type="text" class="form-control" />
    </div>
````
you can choose the size in columns of both label and control in this way:
````php
    $form->textField($new ConcactForm(), 'name', array('sizes' => array('md' => 10), 'labelOptions' => array('sizes' => array('md' => 2))));
````
````html
    <div class="form-group">
        <label class="control-label col-md-2">Name</label>
        <div class="col-md-10">
            <input name="ContactForm[name]" id="ContactForm_name" type="text" class="form-control" />
        </div>
    </div>
````

Finally close the widget with this line:
````php
    /* $this refers to the current controller */
    $this->endWidget();
````        
