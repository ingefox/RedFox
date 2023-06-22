<?php

 /**
  * creates an html form according to declared arrays
  *
  * @param array $entityForm
  * @param array $data
  * @return string
  */

define('RFFORM_SC_OK',0);
define('RFFORM_SC_ERROR',RFFORM_SC_OK+1);
define('RFFORM_SC_FORM_KEY_EMPTY',RFFORM_SC_ERROR+1);


function rfform($entityForm, $data, $layout = null){
    helper('form');

    $scriptPhone = ['before' => '', 'after' => ''];
    $ret = ['html' => '', 'scriptPhone' => $scriptPhone];
    
    // check form key 
    if(!array_key_exists('form',$entityForm))
        return RFFORM_SC_FORM_KEY_EMPTY;
    $form = $entityForm['form'];

    // check if it has to manage Ajax
    if((!key_exists('useAjax', $form)) && (key_exists('action', $form))) 
        $formAction = 'action="'.$form['action'].'"';
    else 
        $formAction ='';
    $ret['html'] .= "<form method='POST' ".$formAction." id='".$form['id']."' accept-charset='utf-8'>";

    //translate php table to html
    $ret = translate($entityForm, $data, $scriptPhone, $ret, $form);

    //tell if data has been sent
    if(isset($data['alert']) && $data['alert']['type'] == 'success')
    $ret['html'] .= "<div class=\"alert alert-" . $data['alert']['type'] . "\" role=\"alert\">".$data['alert']['message']."</div>";

    $ret['html'] .= form_close();

    //script
    $script =
    [
        "scriptPhone" => $ret['scriptPhone'],
        "formScript" => $entityForm['formScript'] ?? "",
        "formId" => $form['id'],
    ];

    //javascript is different if ajax is used
    if(in_array('useAjax', $form))
    {
        $script['action'] = "\n e.preventDefault();loadHTML(base_url(".$form['action'].", form.serialize(), form.parent());";
    }
    else
    {
        $script['action'] = "";
    }


    //call view
    $ret['html'] .= view('RFCore\Views\V_FormScript', $script);

    return $ret;

    //return render($ret['html'], [], [], $layout, false);
}

/**
 * make structure of form (with cols and rows)
 *
 * @param array $entityForm
 * @param array $data data to put in inputs and errors to display
 * @param array $scriptPhone phone keys
 * @param array $ret html returned
 * @param array $form form keys
 * @return array|void
 */
function translate($entityForm, $data, $scriptPhone, $ret, $form)
{
    foreach($entityForm as $formKey => $formValues)
    {
        if($formKey == 'content')
        {
            //check first if col or row or nothing
            foreach($formValues as $contentKey => $contentValues)
            {
                $ret = checkType($contentKey, $contentValues, $data, $scriptPhone, $ret, $form);
            }
        }
    }
    return $ret;
}

/**
 * make a row and check what is inside: row, col or element
 *
 * @param array $values keys inside row
 * @param array $data data to put in inputs and errors to display
 * @param array $scriptPhone phone keys
 * @param array $ret html returned
 * @param array $form form keys
 * @return array|void
 */
function row($values, $data, $scriptPhone, $ret, $form)
{
    foreach($values as  $row)
    {
        //check if col has special class
        if(key_exists('class', $row)) $ret['html'].= '<div class = "form-row '.$row['class'].'">';
        else $ret['html'].= '<div class = "form-row">';
        foreach ($row as $rowKey => $rowValues)
        {
            $ret = checkType($rowKey, $rowValues, $data, $scriptPhone, $ret, $form);
        }
        $ret['html'].= '</div>';
    }
    return $ret;
}

/**
 * make a col and check what is inside: row, col or element
 *
 * @param array $values keys inside col
 * @param array $data data to put in inputs and errors to display
 * @param array $scriptPhone phone keys
 * @param array $ret html returned
 * @param array $form form keys
 * @return array|void
 */
function col($values, $data, $scriptPhone, $ret, $form)
{
    foreach ($values as $col)
    {
        //check if col has special class
        if(key_exists('class', $col)) $ret['html'].= '<div class = "col '.$col['class'].'">';
        else $ret['html'].= '<div class = "col">';
        foreach($col as $colKey => $colValues)
        {
            $ret = checkType($colKey, $colValues, $data, $scriptPhone, $ret, $form);
        }
        $ret['html'].= '</div>';
    }
    return $ret;
}

/**
 * check if next key is row, col or element
 *
 * @param string $key name of row/col/element
 * @param array $values keys to parse
 * @param array $data data to put in inputs and errors to display
 * @param array $scriptPhone phone keys
 * @param array $ret html returned
 * @param array $form form keys
 *
 * @return array|void
 */
function checkType($key, $values, $data, $scriptPhone, $ret, $form)
{
    if($key != 'class')
    {
        //if column in row
        if($key == 'cols')
        {
            $ret = col($values, $data, $scriptPhone, $ret, $form);
        }
        //if row in row
        else if($key == 'rows')
        {
            $ret = row($values, $data, $scriptPhone, $ret, $form);
        }
        //if element
        else
        {
            $ret = makeElement($key, $values, $data, $scriptPhone, $ret, $form);
        }
    }
    return $ret;
}

/**
 * From a php array, returns a html element
 *
 * @param string $key name of array
 * @param array $values options of that array
 * @param array $data data to put in inputs and errors to display
 * @param array $scriptPhone phone keys
 * @param array $ret html returned
 * @param array $form form keys
 *
 * @return array
 */
function makeElement($key, $values, $data, $scriptPhone, $ret, $form)
{
    if($values['type'] != 'form')
    {
        $labelClass = null;
        $attributes = [];
        //html attributes
        $areNotAttributes = ['phoneOptions', 'label', 'small', 'type', 'text', 'options', 'elements', 'form', 'icon', 'selected', 'titleClass', 'data', 'label-class'];

        //input elements
        $areInputs= ['text', 'password', 'date', 'time', 'textarea', 'dropdown', 'multiselect'];

        //assign attributes to $attributes array
        foreach($values as $aKey => $aValue)
        {
            //if is attribute
            if(!in_array($aKey, $areNotAttributes))
            {
                //if attributes has no value
                if(is_numeric($aKey)) 
                    $aKey = $aValue;
                //make attribute key if it doesn't exists
                $attributes[$aKey] = $aValue;
            }
        }


            
        //assign $key to id attribute if it doesn't exists
        if(!key_exists('id', $values))
            $attributes['id'] = $key;

        //assign $key to name attribute if it doesn't exists
        if(!key_exists('name', $values))
            $attributes['name'] = $key;


        if(isset($attributes['class']))
        {
            $formGroupClass='';    
        }
        $formGroupClass=  $attributes['class'] ?? '';

        if($values['type']!="hidden")
            $ret['html'] .= '<div class="form-group '.$formGroupClass.'"> ';

        //generate icon or label (with or without custom classes)
        if(in_array($values['type'], $areInputs))
        {
            $attributes['class'] = 'form-control';
            $labelColSize = $values['label-colSize'] ?? $form['label-colSize'] ?? '';
            $icon = '';
            $label = '';
            $labelClass = null;
            if (key_exists('icon', $values))
            {
                $labelClass = ["class" => $values['label-class'] ?? ''];
                $ret['html'] .= form_label($values['label'], $key, $labelClass);
                $icon = '<i class="'.$values['icon'].'"></i>';
            }
            else if(key_exists('label', $values))
            {
                $labelClass = ["class" => $values['label-class'] ?? null];
                $label = $values['label'] ?? '';
            }

            if(key_exists('label', $values) || key_exists('icon', $values))
            {
                if($labelClass != null)
                {
                    $ret['html'] .= '<div class="input-group"> ';
                    $ret['html'] .= '<div class="input-group-prepend '.$labelColSize.' input-group-text">'.$icon.$label.'</div>';
                }
                else
                {
                    $ret['html'] .= form_label($values['label'], $key, $labelClass);
                    $ret['html'] .= '<div class="input-group"> ';
                }
            }
        }//if element is not an input but label is declared
        else if(key_exists('label', $values) && $values['type'] != 'file')
        {
            $ret['html'] .= form_label($values['label'], $key, []);
        }

        //if error, insert 'is-invalid' class in element's class
        if(key_exists('alert', $data))
        {
            if(key_exists('class', $attributes) && key_exists($attributes['id'], $data['alert']))
            {
                $attributes['class'] .= ' is-invalid';
            }
            else if(!key_exists('class', $attributes) && key_exists($attributes['id'], $data['alert']))
            {
                $attributes['class'] = 'is-invalid';
            }
        }

        //generate appropriate html element
        switch($values['type'])
        {

                case "text":
                    //if is phone input
                    $required = 'false';
                    $mobile = 'false';
                    if(array_key_exists('phoneOptions', $values))
                    {
                        if(in_array('required', $values['phoneOptions']))
                            $required = 'true';
                        if(in_array('mobile', $values['phoneOptions']))
                            $mobile = 'true';
                        


                        if(in_array('useAjax', $form))
                        {
                            $ret['scriptPhone']['before'] .= 'if(verifyPhone($(\'#'.$attributes['id'].'\').val(),'.$required.','.$mobile.')){';
                            $ret['scriptPhone']['after'] .= '}';
                        }
                        else
                        {
                            $ret['scriptPhone']['before'] .= 'if(!verifyPhone($(\'#'.$attributes['id'].'\').val(),'.$required.','.$mobile.')){';
                            $ret['scriptPhone']['after'] .= '$(\'#'.$attributes['id'].'Error\').removeAttr(\'hidden\');
                            $(\'#'.$attributes['id'].'Error\').html(\'Numéro de téléphone invalide.\');
                            $(\'#'.$attributes['id'].'\').addClass("is-invalid");}';
                        }


                    }

                    //assign previous typed value if page reloaded (if first time loading)
                    $text_value = $data[$key] ?? set_value($attributes['id']);
                    $ret['html'] .= form_input($attributes, $text_value, '', $values['type']);
                break;

                case "password":
                    $ret['html'] .= form_password($attributes, '', '');
                break;

                case "label":
                    $id = $attributes['id'];
                    $ret['html'] .= form_label($values['text'], $id, $values['attributes']);
                break;
                
                case "button_submit":
                    $button_value = $values['value'] ?? $key;
                    $attributes['class'] = $attributes['class'] ?? 'btn btn-primary';

                    $ret['html'] .= form_submit('', $button_value, $attributes);
                break;

                case "button_reset":
                    $button_value = $values['value'] ?? $key;
                    $ret['html'] .= form_reset('', $button_value, $attributes);
                break;
                
                case "button":
                    $button_value = $values['value'] ?? $key;
                    $ret['html'] .= form_button('', $button_value, $attributes);
                break;

                case "dropdown":
                    $text_value = $data[$key] ?? set_value($attributes['id']);
                    $options = $values["options"];

                    $ret['html'] .= form_dropdown('', $options, $values["selected"] ?? [], $attributes);
                break;


                case "multiselect":
                    $name = $values['name'] ?? $key;
                    $selected = $values['selected'] ?? [];
                    $attributes['class'] = $attributes['class'] ?? 'custom-select';

                    $ret['html'] .= form_multiselect($name, $values['options'], $selected, $attributes);
                break;
                
                //data must be name => value
                case "hidden":
                    $ret['html'] .= form_hidden($values['data']);
                break;

                case "textarea":
                    $text_value = $data[$key] ?? '';
                    $attributes['class'] = $attributes['class'] ?? 'form-control';

                    $ret['html'] .= form_textarea($attributes, $text_value, '', $values['type']);
                break;
                
                //generate radio buttons with same name but different id to make them work
                case "radio":

                    foreach($values['elements'] as $label=>$id)
                    {
                        $ret['html'] .= '<div class="form-check">';
                        $data =
                        [
                                'name' => $key,
                                'id' => $id
                            ];
                        $labelData = ["class" => "form-check-label"];
                        $attributes['class'] = $attributes['class'] ?? 'form-check-input';
                        $ret['html'] .= form_radio($data, '', false, $attributes);
                        $ret['html'] .= form_label($label, '', $labelData);
                        $ret['html'] .= '</div>';
                    }

                break;

                case "checkbox":
                    foreach($values['elements'] as $label=>$id)
                    {
                        $ret['html'] .= '<div class="form-check">';
                        $data =
                        [
                                'name' => $key,
                                'id' => $id
                            ];
                        $labelData = ["class" => "form-check-label"];
                        $attributes['class'] = $attributes['class'] ?? 'form-check-input';

                        $ret['html'] .= form_checkbox($data, '', false, $attributes);
                        $ret['html'] .= form_label($label, '', $labelData);
                        $ret['html'] .= '</div>';
                    }
                break;

            case "title":
                $id = $attributes['id'];
                $level = $values['level'] ?? 'h3';
                $class = $values['class'] ?? 'homeTitle';
                $text = $values['text'] ?? $key;
                $icon = $values['icon'] ?? '';

                $ret['html'] .= '<'.$level.' class="'.$class.'">'.$text.'<i class="'.$icon.'"></i></'.$level.'>';
            break;

            case "file":
                $id = $attributes['id'];
                $class = $attributes["class"] ?? 'custom-file-input';
                $label = $values['label'] ?? 'Choisir un fichier...';
                $labelClass = $values['label-class'] ?? 'custom-file-label';

                $ret['html'] .= '<div class="custom-file">';
                $ret['html'] .= '<input type="file" class="'.$class.'" id="'.$id.'">';
                $ret['html'] .= '<label class="'.$labelClass.'" for="'.$id.'">'.$label.'</label>';
                $ret['html'] .= '</div>';
            break;

            case "date":
                $id = $attributes['id'];
                $min = $values['min'] ?? '1900-1-1';
                $max = $values['max'] ?? '2100-12-30';
                $class = $attributes['class'] ?? 'form-control';

                $ret['html'] .= '<input type="date" class="'.$class.'" id="'.$id.'" min="'.$min.'" max ="'.$max.'">';
            break;

            case "time":
                $id = $attributes['id'];
                $min = $values['min'] ?? '00:00';
                $max = $values['max'] ?? '24:00';
                $class = $attributes['class'] ?? 'form-group';

                $ret['html'] .= '<input type="time" class="'.$class.'" id="'.$id.'" min="'.$min.'" max ="'.$max.'">';
            break;

            case "hr":
                $ret['html'] .= "<hr>";
            break;

            case "custom":
                $ret['html'] .= $values['html'];
            break;


        }

        //show error below concerned input if there is one
        if(key_exists('phoneOptions', $values))
            $ret['html'] .= '<div class="invalid-feedback" hidden id="'.$key.'Error"></div>';
        else if(key_exists('phoneOptions', $values) && isset($data['alert']))
            $ret['html'] .= '<div class="invalid-feedback" id="'.$key.'Error">'.$data['alert'][$key].'</div>';
        else if(isset($data['alert']))
            if(isset($data['alert']) && $data['alert']['type'] == 'error' && isset($data['alert'][$key]))
                $ret['html'] .= '<div class="invalid-feedback">'.$data['alert'][$key].'</div>';

        //close input-group
        if((key_exists('label', $values) || key_exists('icon', $values)) && $values['type'] != 'file')
        { 
            if(in_array($values['type'], $areInputs))
            {
                $ret['html'] .= '</div>';
        }
    }

        //generate a small label for input if "small" key exists
        if(key_exists('small', $values))
            $ret['html'] .= '<small class="form-text text-muted">'.$values['small'].'</small>';


        //close form-group
        if($values['type']!="hidden")
            $ret['html'] .= '</div>';
    }
    //if element is another form
    else
    {
        $ret = translate($values['form'], $data, $scriptPhone, $ret, $form);
    }
    
    return $ret;
}
