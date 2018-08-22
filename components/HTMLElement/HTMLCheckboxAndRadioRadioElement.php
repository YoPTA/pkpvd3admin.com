<?php

/*
	Example
	
	$html_element['radion_test1'] = new HTMLElement\HTMLCheckboxAndRadioRadioElement();
	$html_element['radion_test1']->setName('radion_test[]');
	$html_element['radion_test1']->setValue('radion_test1');		
	$html_element['radion_test1']->setId('radion_test1');
	$html_element['radion_test1']->setNecessarily(false);
	$html_element['radion_test1']->setCaption('Тест 1');
	$html_element['radion_test1']->setCheckedFromRequest();
	
	$html_element['radion_test1']->check();	
	
	$html_element['radion_test1']->render();
	
	$html_element['radion_test2'] = new HTMLElement\HTMLCheckboxAndRadioRadioElement();
	$html_element['radion_test2']->setName('radion_test[]');
	$html_element['radion_test2']->setValue('radion_test2');		
	$html_element['radion_test2']->setId('radion_test2');
	$html_element['radion_test2']->setNecessarily(false);
	$html_element['radion_test2']->setCaption('Тест 1');
	$html_element['radion_test2']->setCheckedFromRequest();
	
	$html_element['radion_test2']->check();	
	
	$html_element['radion_test2']->render();
*/

namespace HTMLElement;


class HTMLCheckboxAndRadioRadioElement extends HTMLCheckboxAndRadio
{
    /**
     * Отрисовывает html элемент.
     * @return string
     */
    public function render()
    {
        $el_attributes = '';

        if (parent::getCaption() != false)
        {
            $el_attributes .= ((!parent::getConfig('type'))?' type="radio"': '');

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