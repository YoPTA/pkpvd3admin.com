<?php

namespace HTMLElement;


class HTMLTextTextareaElement extends HTMLTextElement
{
    private $min = false;
    private $max = false;
	private $preg_match = null;
   /**
     * Устанавливает регулярное выражение.
     * @param $value value - регулярное выражение
     */
    public function setPregMatch($value)
    {
        if (!empty($value))
        {
            $this->preg_match = $value;
        }
    }

    /**
     * Возвращает регулярное выражение.
     * @return string
     */
    public function getPregMatch()
    {
        return $this->preg_match;
    }

   /**
     * Устанавливает минимальное значение.
     * @param $value value - минимальное значение
     */
    public function setMin($value)
    {
        if (is_int($value))
        {
            $this->min = $value;
			if ($value > 0)
			{
				parent::setNecessarily(true);
			}
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
		if (!parent::getNecessarily() && $this->getValue() == '')
		{
			return;
		}	
        if ($this->getPregMatch() !== null)
        {
			if (preg_match($this->getPregMatch(), parent::getValue()) !== 1) 
			{
                parent::setCheck(false);
                return;
			}		
        }	
        if ($this->getMin() !== false)
        {
            if (strlen(parent::getValue()) < $this->getMin())
            {
                parent::setCheck(false);
                return;
            }
        }
        if ($this->getMax() !== false)
        {
            if (strlen(parent::getValue()) > $this->getMax())
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

        parent::setStyle('width: 250px;');
        if (parent::getCheck() === false)
        {
            $this->setStyle('border: 1px solid red;');
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
        . '<textarea '
        .$el_attributes
        . (($this->getDisabled() === true)? 'disabled ' : '')
        .'>' . parent::getValue() . '</textarea>';
    }
}