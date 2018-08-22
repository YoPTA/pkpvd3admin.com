<?php

/*
	Example
	
	$html_element['text_integer'] = new HTMLElement\HTMLTextIntegerElement();
	$html_element['text_integer']->setCaption('Количество');
	$html_element['text_integer']->setName('text_integer');
	$html_element['text_integer']->setNecessarily(false);
	$html_element['text_integer']->setMinMax(-30, 5);
	$html_element['text_integer']->setId('text_integer');
	$html_element['text_integer']->setValueFromRequest();	
	
	$html_element['text_integer']->check();		
	
	$html_element['text_integer']->render();
*/

namespace HTMLElement;

class HTMLTextIntegerElement extends HTMLTextElement
{	
    private $min = -2147483648;
	private $max = 2147483647;	

   /**
     * Устанавливает минимальное значение.
     * @param $value value - минимальное значение
     */
    public function setMin($value)
    {
        if (is_int($value))
        {
            $this->min = $value;
        }
    }

    /**
     * Возвращает минимальное значение типа.
     * @return bool OR value
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Устанавливает максимальное значение.
     * @param $value value - максимальное значение
     */
    public function setMax($value)
    {
        if (is_int($value))
        {
            $this->max = $value;
        }
    }

    /**
     * Возвращает максимальное значение.
     * @return bool OR value
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Устанавилвает минимальное и максимальное значения.
     * @param $min value - минимальное значение
     * @param $max value - максимальное значение
     */
    public function setMinMax($min, $max)
    {
        $this->setMin($min);
        $this->setMax($max);
    }	
	

	
    /**
     * Проводит необходимую проверку для текущего типа.
     */
    public function check()
    {
		if (!parent::getNecessarily() && parent::getValue() == '')
		{
			return;
		}
		if (!is_numeric(parent::getValue()))
		{
			parent::setCheck(false);
			return;
		}
		if ($this->getMin() !== false)
		{
			if (parent::getValue() < $this->getMin())
			{
				parent::setCheck(false);
				return;
			}
		}
		if ($this->getMax() !== false)
		{
			 if (parent::getValue() > $this->getMax())
			{
				parent::setCheck(false);
				return;
			}
		}
    }

    /**
     * Отрисовывает html элемент.
     * @return string
     */
    public function render()
    {
        $el_attributes = '';

        $el_attributes .= ((!parent::getConfig('type'))?' type="text"': '');
        //parent::setStyle('width: 250px;');
        if (parent::getCheck() === false)
        {
            $this->setStyle('background-color: #fffafa; border-color: #df8080;');
        }

        $full_config = parent::getFullConfig();

        foreach ($full_config as $key => $val)
        {
            if ($val !== false)
            {
                if ($el_attributes != '') $el_attributes .= ' ';

                $el_attributes .= $key .'="'.$val.'"';
            }
        }

        return ((parent::getCaption() != '')
            ? '<label'.
            ((parent::getId() != '' && parent::getId() != false)
                ? ' for="'. parent::getId().'"'
                : '').'>'.parent::getCaption().':'
            . ((parent::getNecessarily() !== false)? ' *':'').'</label><br>'
            :'')
        . '<input '
        .$el_attributes
        . ((parent::getDisabled() === true)? 'disabled ' : '')
        .'  />';
    }
}