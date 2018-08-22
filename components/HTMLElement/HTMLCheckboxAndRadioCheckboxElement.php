<?php

/*
	Example
	
	$html_element['checkbox_test'] = new HTMLElement\HTMLCheckboxAndRadioCheckboxElement();
	$html_element['checkbox_test']->setName('checkbox_test');
	$html_element['checkbox_test']->setValue('checkbox_test');		
	$html_element['checkbox_test']->setId('checkbox_test');
	$html_element['checkbox_test']->setNecessarily(false);
	$html_element['checkbox_test']->setCaption('Тест');
	$html_element['checkbox_test']->setCheckedFromRequest();
	
	$html_element['checkbox_test']->check();
	
	$html_element['checkbox_test']->render();
*/

namespace HTMLElement;


class HTMLCheckboxAndRadioCheckboxElement extends HTMLCheckboxAndRadio
{
    /**
     * Отрисовывает html элемент.
     * @return string
     */
    public function render($in = false)
    {
        $el_attributes = '';

        if (parent::getCaption() != false)
        {
            $el_attributes .= ((!parent::getConfig('type'))?' type="checkbox"': '');

            $full_config = parent::getFullConfig();

            foreach ($full_config as $key => $val)
            {
                if ($val !== false)
                {
                    if ($el_attributes != '') $el_attributes .= ' ';

                    $el_attributes .= $key .'="'.$val.'"';
                }
            }

            if (parent::getChecked() === true)
            {
                $el_attributes .= ' checked';
            }

			return  (($in)? '<label'
                . ((parent::getId() != '' && parent::getId() != false)
                ? ' for="'.parent::getId().'"' : '') .'>' :'')
				. '<input '. $el_attributes .((parent::getDisabled() === true)? ' disabled ' : '').'/>'
				.((!$in)? '<label'
                . ((parent::getId() != '' && parent::getId() != false)
                ? ' for="'.parent::getId().'"' : '') .'>' :'')
				.'&nbsp;' .  parent::getCaption() .((parent::getNecessarily() !== false)? ' *':'').'</label>';
        }
        else
        {
            return parent::getNoElement();
        }
    }
	
    
}