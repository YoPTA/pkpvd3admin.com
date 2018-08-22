<?php

/*
	Example
	
	$html_element['text_date'] = new HTMLElement\HTMLTextDateElement();
	$html_element['text_date']->setCaption('Дата');
	$html_element['text_date']->setName('text_date');
	$html_element['text_date']->setNecessarily(true);
	$html_element['text_date']->setMax('01.01.2018');
	$html_element['text_date']->setId('text_date');
	$html_element['text_date']->setValueFromRequest();	
	
	$html_element['text_date']->check();	
	
	$html_element['text_date']->render();
*/

namespace HTMLElement;
use \Datetime;

class HTMLTextDateTimeElement extends HTMLTextElement
{		
	private $format = 'd.m.Y H:i:s';
	
    private $min = false;
    private $max = false;

   /**
     * Устанавливает формат.
     * @param $value value
     */
    public function setFormat($value)
    {
        if (!empty($value))
        {
            $this->format = $value;
        }
    }

    /**
     * Возвращает формат.
     * @return value
     */
    public function getFormat()
    {
        return $this->format;
    }	
	
	private function CheckDateTime($value)
	{
		$format = $this->getFormat();
		
		$datetime = DateTime::createFromFormat($format, $value);
		if ($datetime === false)
		{
			return false;
		}
		return ($datetime->format($format) == $value) ? true : false;
	}	

	private function CompareDateTime($value1, $value2)
	{
		$format = $this->getFormat();
		
		$datetime1 = DateTime::createFromFormat($format, $value1);
		if ($datetime1 === false)
		{
			return false;
		}
		if ($datetime1->format($format) != $value1)
		{
			return false;
		}

		$datetime2 = DateTime::createFromFormat($format, $value2);
		if ($datetime2 === false)
		{
			return false;
		}
		if ($datetime2->format($format) != $value2)
		{
			return false;
		}

		return ($datetime1 == $datetime2) ? 0 : (($datetime1 > $datetime2) ? 1 : -1);	
	}	
	
   /**
     * Устанавливает минимальное значение.
     * @param $value value - минимальное значение
     */
    public function setMin($value)
    {
        if ($this->CheckDateTime($value))
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
        if ($this->CheckDateTime($value))
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
		if (!$this->CheckDateTime(parent::getValue()))
		{
			parent::setCheck(false);
			return;
		}
		if ($this->getMin() !== false)
		{
			if ($this->CompareDateTime(parent::getValue(), $this->getMin()) == -1)
			{
				parent::setCheck(false);
				return;
			}
		}
		if ($this->getMax() !== false)
		{
			if ($this->CompareDateTime(parent::getValue(), $this->getMax()) == 1)
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

class HTMLTextDateElement extends HTMLTextDateTimeElement
{
   function __construct() 
   {
       parent::setFormat('d.m.Y');
   }
}
class HTMLTextTimeElement extends HTMLTextDateTimeElement
{
   function __construct() 
   {
       parent::setFormat('H:i:s');
   }
}