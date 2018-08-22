<?php

/*
	Example
	
	$optgroup = array();
	
	$optgroup['optgroup1'] = new HTMLElement\HTMLSelectOptgroupElement();
	$optgroup['optgroup1']->setLabel('Группа 1');
		
	$optgroup['optgroup2'] = new HTMLElement\HTMLSelectOptgroupElement();
	$optgroup['optgroup2']->setLabel('Группа 2');		
	
	$option = array();
	
	$option['option0'] = new HTMLElement\HTMLSelectOptionElement();
	$option['option0']->setText('[выбрать]');		
	
	$option['option1'] = new HTMLElement\HTMLSelectOptionElement();
	$option['option1']->setValue(1);
	$option['option1']->setGroup('optgroup1');
	$option['option1']->setText('Привет 1.1');
	
	$option['option2'] = new HTMLElement\HTMLSelectOptionElement();
	$option['option2']->setValue(2);
	$option['option2']->setGroup('optgroup1');
	$option['option2']->setText('Привет 1.2');		

	$option['option3'] = new HTMLElement\HTMLSelectOptionElement();
	$option['option3']->setValue(3);
	$option['option3']->setGroup('optgroup2');
	$option['option3']->setText('Привет 2.1');
	
	$option['option4'] = new HTMLElement\HTMLSelectOptionElement();
	$option['option4']->setValue(4);
	$option['option4']->setGroup('optgroup2');
	$option['option4']->setText('Привет 2.2');				
			
	//Single Select
			
	$html_element['select_test'] = new HTMLElement\HTMLSelectElement($option, $optgroup);
	$html_element['select_test']->setName('select_test');
	$html_element['select_test']->setId('select_test');
	$html_element['select_test']->setNecessarily(true);
	$html_element['select_test']->setCaption('Тест');		
	$html_element['select_test']->setConfig('class', 'chosen-select');		
	$html_element['select_test']->setValueFromRequest();	
	
	//Set Single Default Value
	$html_element['select_test']->setDefaultValue(4);	
	
	//Multiple Select
			
	$html_element['select_test'] = new HTMLElement\HTMLSelectElement($option, $optgroup);
	$html_element['select_test']->setName('select_test[]');
	$html_element['select_test']->setId('select_test');
	$html_element['select_test']->setNecessarily(true);
	$html_element['select_test']->setCaption('Тест');		
	$html_element['select_test']->setConfig('class', 'chosen-select');	
	$html_element['select_test']->setConfig('multiple', '');		
	$html_element['select_test']->setValueFromRequest();		
	
	//Set Multiple Default Values 
	$multival = array();
	$multival[] = 3;
	$multival[] = 4;
	$html_element['select_test']->setDefaultValue($multival);

	
	$html_element['select_test']->check();
            
	if (!$html_element['select_test']->getCheck())
	{
		$errors['error_test'] = 'Необходимо выбрать';
	}
	
	$html_element['select_test']->render();
*/

namespace HTMLElement;

include 'HTMLSelectOptionElement.php';
include 'HTMLSelectOptgroupElement.php';

use HTMLElement\HTMLSelectOptgroupElement;
use HTMLElement\HTMLSelectOptionElement;

class HTMLSelectElement extends HTMLElementBase
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    /**
     * Выбранное значение элемента.
     * @var string
     */	 
	private $value = null;
	 
    // Установки конфигурации элемента
    const HTML_E_CONFIG_VALUE_NAME = 'value';

	private $html_option_element = array();
	private $html_optgroup_element = array();
	
    /*******************************************************
     ******************** Методы класса ********************
     *******************************************************/

    /**
     * Конструктор
	 * @param $option Object
	 * @param $optgroup Object
     */	
	function __construct($option, $optgroup = null) 
	{
		$this->html_option_element = $option;
		if (is_array($optgroup))
		{
			$this->html_optgroup_element = $optgroup;
		}
	}		 
	
    /**
     * Возвращает выбранное значение элемента.
     * @return string
     */	
    public function getValue()
    {
        return $this->value;
    }	
	
    /**
     * Устанавливает выбранное значение элемента.
     * @param string
     */	
    public function setValue($value)
    {
		if ($value == null)
		{
			return;
		}
		
		if ($value == $this->value)
		{
			return;
		}

		foreach ($this->html_option_element as $option)
		{	
			$option->setSelected(false);		
		}

		if (is_array($value))
		{
			$multival = array();
			
			foreach ($this->html_option_element as $option)
			{	
				$option_value = $option->getValue();
				if ($option_value !== null)
				{
					foreach ($value as $val)
					{
						if ($val == $option_value)
						{
							$option->setSelected(true);
							$multival[] = $option_value;
							break;
						}					
					}
				}
			}	

			$this->value = $multival;		
		}else{
			foreach ($this->html_option_element as $option)
			{	
				$option_value = $option->getValue();			
				if ($option_value !== null)
				{
					if ($value == $option_value)
					{
						$option->setSelected(true);
						$this->value = $option_value;
						break;
					}
				}
			}			
		}
    }	
	
    /**
     * Устанавливает выбранное значение элемента по умолчанию.
     * @param string
     */	
    public function setDefaultValue($value)
	{
		if ($this->getValue() == null)
		{
			$this->setValue($value);
		}
	}
	
    /**
     * @param $options
     * @param $optgroups
     * @return string
     */
    public function render()
    {
        $default_group_index = null;
        $result = '';
        $el_attributes = '';

        if (parent::getCheck() === false)
        {
            $this->setStyle('background-color: #fffafa; border-color: #df8080;');
        }		
		
        foreach ($this->html_option_element as $option)
        {		
			$group_index = null;
			
			$temp_index = $option->getGroup();
			if ($temp_index !== null)
			{
				if (isset($this->html_optgroup_element[$temp_index]))
				{
					$group_index = $temp_index;
				}
			}
						
			if ($group_index !== null)
			{
				if ($default_group_index !== $group_index)
				{
					$result.= $this->html_optgroup_element[$group_index]->render();
				
					$default_group_index = $group_index;
				}
			}
			
			$result.= $option->render();
        }

        $full_config = parent::getFullConfig();

        foreach ($full_config as $key => $val)
        {
            if ($val !== false)
            {
                if ($el_attributes != '') $el_attributes .= ' ';

                if ($key != self::HTML_E_CONFIG_VALUE_NAME) $el_attributes .= $key .'="'.$val.'"';
            }
        }
		
        return ((parent::getCaption() != '')
            ? '<label'.
            ((parent::getId() != '' && parent::getId() != false)
            ? ' for="'. parent::getId().'"'
            : '').'>'.$this->getCaption().':'
            . ((parent::getNecessarily() !== false)? ' *':'').'</label><br>'
            :'')
            .'<select '. $el_attributes .'>'.$result.'</select>';
    }

    /**
     * Установить значение из запроса.
     * @return bool
     */
    public function setValueFromRequest()
    {
		$name = parent::getName();
		
		if ($name == '')
		{
			return false;
		}
		
		$is_multiple = false;	

		if ( parent::getConfig('multiple') !== false)
		{		
			$start_pos = strpos($name, '[');
			if ($start_pos !== false)
			{
				$name = substr($name, 0, $start_pos); 
				$is_multiple = true;
			}
		}
		
        if (!isset($_REQUEST[$name]))
        {
            return false;
        }
		
		parent::setExists(true);
		
		if ($is_multiple)
		{
			$multiple_data = array();
			
			foreach ($_REQUEST[$name] as $data)
			{
				$data = htmlspecialchars($data, null, parent::getDefaultCharset());
				$multiple_data[] = $data;
			}
			
			if (sizeof($multiple_data) > 0)
			{
				$this->setValue($multiple_data);
			}
		}else{
			$this->setValue(htmlspecialchars($_REQUEST[$name], null, parent::getDefaultCharset()));
		}
		return true;
    }

    /**
     * Проводит необходимую проверку для текущего типа.
     */
    public function check()
    {
		if (!parent::getNecessarily())
		{
			return;
		}
        if ($this->getValue() === null)
        {
			parent::setCheck(false);
            return;
        }
    }	
}