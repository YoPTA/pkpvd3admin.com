<?php

namespace HTMLElement;


class HTMLTextElement extends HTMLElementBase
{

    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    // Установки конфигурации элемента
    const HTML_E_CONFIG_VALUE_NAME = 'value';



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
     * Устанавливает значение из запроса.
     * @return bool
     */
    public function setValueFromRequest()
    {
        if (!isset($_REQUEST[$this->getName()]))
        {
            return false;
        }
		parent::setExists(true);
        $this->setValue(htmlspecialchars($_REQUEST[$this->getName()], null, parent::getDefaultCharset()));
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
}