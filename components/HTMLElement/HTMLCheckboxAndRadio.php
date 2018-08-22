<?php

namespace HTMLElement;


class HTMLCheckboxAndRadio extends HTMLElementBase
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    // Установки конфигурации элемента
    const HTML_E_CONFIG_VALUE_NAME = 'value';

    /**
     * Флаг для элемента.
     * Флаг может принимать 2 состояния (отмечен/не отмечен).
     * true - элемент отмечен.
     * false - элемент не отмечен.
     * @var bool
     */
    private $checked = false;

    /*******************************************************
     ******************** Методы класса ********************
     *******************************************************/
    /**
     * Устанавливает значение элемента.
     * @param $value string - значение
     */
    public function setValue($value)
    {
        parent::setConfig(self::HTML_E_CONFIG_VALUE_NAME, $value);
    }

    /**
     * Возвращает значение элемента.
     * @return bool OR string
     */
    public function getValue()
    {
        return parent::getConfig(self::HTML_E_CONFIG_VALUE_NAME);
    }

    /**
     * Устанавливает флаг для элемента.
     * @param $value bool - флаг элемента
     * @return bool
     */
    public function setChecked($value)
    {
        if (!is_bool($value))
        {
            return false;
        }
        $this->checked = $value;
    }

    /**
     * Возвращает значение флага элемента.
     * @return bool
     */
    public function getChecked()
    {
        return $this->checked;
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

        if ($this->getChecked() !== true)
        {
			parent::setCheck(false);
			return;
        }

    }	

/**
     * Устанавливает значение из запроса формы методом $_REQUEST.
	 * @return bool
     */	
    public function setCheckedFromRequest()
    {	
		$name = parent::getName();
		
		if ($name == '')
		{
			return false;
		}
			
		$is_array = false;	
		
		if (substr($name, -1) == ']')
		{
			$start_pos = strpos($name, '[');
			if ($start_pos !== false)
			{
				$name = substr($name, 0, $start_pos); 
				$is_array = true;
			}
		}
		
        if (!isset($_REQUEST[$name]))
        {
            return false;
        }
		
		parent::setExists(true);

		$data = $_REQUEST[$name];
		
		if ($is_array)
		{
			if (array_key_exists(array_search($this->getValue(), $data), $data))
			{
				$this->setChecked(true);
			}		
		}else{
			if ($data == $this->getValue());
			{
				$this->setChecked(true);
			}			
		}
		return true;
    }
}