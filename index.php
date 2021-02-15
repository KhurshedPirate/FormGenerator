<?php

interface IFormGenerator
{
    /**
     * IFormGenerator constructor.
     * @param $jsonForm
     */
    public function __construct($jsonForm);
    /**
     * @return string
     */
    public function toHtml();
}

class FormGenerator implements IFormGenerator
{
    /**
     * @var $form
     */
    private $form;

    /**
     * FormGenerator constructor.
     * @param $jsonForm
     */
    public function __construct($jsonForm)
    {
        $this->form = json_decode($jsonForm, false, 20)->form;
    }

    /**
     * @return string
     */
    public function toHTML()
    {
        $formName = $this->form->name;

        return "<form ".'name="'.$formName.'"'.'>'.$this->getContents().'</form>';
    }

    /**
     * @return string
     */
    private function getContents()
    {
        $result = "";
        foreach ($this->form->items as $item) {
            $type = $item->type;
            $result .= $this->$type($item)."\n";
        }
        return $result;
    }

    /**
     * @param $filler
     * @return string
     */
    private function filler($filler)
    {
        return '<p>'.$filler->attributes->message.'</p>';
    }

    /**
     * @param $input
     * @return string
     */
    private function text($input)
    {
        $name = $input->attributes->name;
        $placeholder = $input->attributes->placeholder;
        $required = $input->attributes->required ? 'required' : '';
        $value = $input->attributes->value;
        $class = $input->attributes->class;
        $disabled = $input->attributes->disabled ? 'disabled':'';

        $label = '<label>'.$input->attributes->label.'</label>'."\n";

        return $label.'<input'.' name="'.$name.'"'.'placeholder="'.$placeholder.'" '
            .$required.' value="'.$value.'" '.'class="'.$class.'" '
            .$disabled."> <br>";
    }

    /**
     * @param $textarea
     * @return string
     */
    private function textarea($textarea)
    {
        $name = $textarea->attributes->name;
        $placeholder = $textarea->attributes->placeholder;
        $required = $textarea->attributes->required ? 'required' : '';
        $value = $textarea->attributes->value;
        $class = $textarea->attributes->class;
        $disabled = $textarea->attributes->disabled ? 'disabled':'';

        $label = '<label>'.$textarea->attributes->label.'</label>'."\n";

        return $label.'<textarea rows="5" cols="100"'.' name="'.$name.'"'.'placeholder="'.$placeholder.'" '
            .$required.' value="'.$value.'" '.'class="'.$class.'" '
            .$disabled."></textarea><br> ";
    }

    /**
     * @param $checkbox
     * @return string
     */
    private function checkbox($checkbox)
    {
        $name = $checkbox->attributes->name;
        $placeholder = $checkbox->attributes->placeholder;
        $required = $checkbox->attributes->required ? 'required' : '';
        $value = $checkbox->attributes->value;
        $class = $checkbox->attributes->class;
        $disabled = $checkbox->attributes->disabled ? 'disabled':'';
        $checked = $checkbox->attributes->checked ? 'checked':'';

        $label = '<label>'.$checkbox->attributes->label.'</label>'."\n";

        return $label.'<input type="checkbox"'.' name="'.$name.'"'.'placeholder="'.$placeholder.'" '
            .$required.' value="'.$value.'" '.'class="'.$class.'" '
            .$disabled.' '.$checked."><br>";
    }

    /**
     * @param $button
     * @return string
     */
    private function button($button)
    {
        $text = $button->attributes->text;
        $class = $button->attributes->class;

        return '<button class="'.$class.'">'.$text.'</button><br>';
    }

    /**
     * @param $select
     * @return string
     */
    private function select($select)
    {
        $name = $select->attributes->name;
        $placeholder = $select->attributes->placeholder;
        $required = $select->attributes->required ? 'required' : '';
        $value = $select->attributes->value;
        $class = $select->attributes->class;
        $disabled = $select->attributes->disabled ? 'disabled':'';

        $label = '<label>'.$select->attributes->label.'</label>'."\n";

        return $label.'<select'.' name="'.$name.'"'.'placeholder="'.$placeholder.'" '
            .$required.' value="'.$value.'" '.'class="'.$class.'" '
            .$disabled.'>'.$this->selectOptions($select->attributes->options).'</select><br>';
    }

    /**
     * @param $options
     * @return string
     */
    private function selectOptions($options)
    {
        if (!$options || count($options) === 0) return
            $result = '';
        foreach ($options as $option) {
            $text = $option->text;
            $selected = $option->selected ? 'selected' : '';
            $value = $option->value;

            $result .= '<option value="'.$value.'" '.$selected.'>'.$text.'</option>';
        }
        return $result;
    }

    /**
     * @param $radio
     * @return string
     */
    private function radio($radio)
    {
        $options = $radio->attributes->options;
        if (!$options || count($options) === 0) return '';
        $result = '';
        foreach ($options as $option) {
            $checked = $option->checked ? 'checked' : '';
            $value = $option->value;

            $label = '<label>'.$option->label.'</label>'."\n";

            $result .= $label.'<input name="radioName" type="radio" value="'.$value.' '.$checked.">";
        }

        return $result;
    }
}

$x = '{"form":{"name":"Название формы","postmessage":"Сообщение в случае успешного заполнения формы","items":[{"type":"filler","attributes":{"message":"Произвольные текст"}},{"type":"text","attributes":{"name":"Имя элемента","placeholder":"Текст для placeholder","required":true,"value":"","label":"Label для элемента","class":"css-class","validationRules":[{"type":"email"}],"disabled":false}},{"type":"textarea","attributes":{"name":"Имя текстареа","placeholder":"Текст для placeholder Текст для placeholder Текст для placeholder Текст для placeholder Текст для placeholder Текст для placeholder","required":true,"value":"","label":"Label для текстареа","class":"css-class","validationRules":[{"type":""}],"disabled":false}},{"type":"checkbox","attributes":{"name":"Имя чекбокса","placeholder":"Текст для placeholder Текст для placeholder Текст для placeholder Текст для placeholder Текст для placeholder Текст для placeholder","required":true,"value":"1","label":"Label для чекбокса","class":"css-class","checked":true,"validationRules":[{"type":"email"}],"disabled":false}},{"type":"button","attributes":{"text":"Кнопка","class":" button class"}},{"type":"select","attributes":{"name":"Имя опции","text":"Текст для placeholder","required":true,"value":"2","label":"Label для элемента","class":"css-class","selected":true,"validationRules":[{"type":"email"}],"disabled":false}},{"type":"radio","attributes":{"name":"Имя radio","required":true,"value":"2","label":"Label для элемента","class":"css-class","checked":true,"validationRules":[{"type":"email"}],"disabled":false}}]}}';

$form = new FormGenerator($x);
$result = $form->toHTML();
echo $result ;