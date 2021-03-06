<?php

/**
 * Artificial Neural Network - Version 2.3
 *
 * For updates and changes visit the project page at http://ann.thwien.de/
 *
 *
 *
 * <b>LICENCE</b>
 *
 * The BSD 2-Clause License
 *
 * http://opensource.org/licenses/bsd-license.php
 *
 * Copyright (c) 2007 - 2012, Thomas Wien
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in the
 * documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Thomas Wien <info_at_thwien_dot_de>
 * @version ANN Version 2.3 by Thomas Wien
 * @copyright Copyright (c) 2007-2012 by Thomas Wien
 * @package ANN
 */

namespace SalesMan\TheSalesManBundle\Lib\ANN;

/**
 * @package ANN
 * @access public
 */

final class Classification extends Filesystem implements InterfaceLoadable
{
	/**#@+
	 * @ignore
	 */

	/**
	 * @var integer
	 */
	protected $intMaxClassifiers;
	
	/**
	 * @var array
	 */
	protected $arrClassifiers = array();
	
	/**#@-*/
	
	/**
	 * @param integer $intMaxClassifiers
	 * @throws Exception
	 */
	
	public function __construct($intMaxClassifiers)
	{
	  if(!is_integer($intMaxClassifiers) || $intMaxClassifiers <= 0)
	    throw new Exception('Constraints: $intMaxClassifiers should be a positive integer number');
	
	  $this->intMaxClassifiers = $intMaxClassifiers;
	}
	
	/**
	 * @param string $strValue
	 * @throws Exception
	 * @uses existsClassifier()
	 */
	
	public function addClassifier($strValue)
	{
		if(count($this->arrClassifiers) == $this->intMaxClassifiers)
			throw new Exception('Maximal count of classifiers reached');
			
		if($this->existsClassifier($strValue))
			throw new Exception('Classifier "'. $strValue .'" does already exist');
		
		$this->arrClassifiers[] = $strValue;
	}
	
	/**
	 * @param string $strValue
	 * @return boolean
	 */
	
	protected function existsClassifier($strValue)
	{
		foreach($this->arrClassifiers as $strClassifier)
		{
			if(strtolower($strClassifier) == strtolower($strValue))
				return TRUE;
		}
	
		return FALSE;
	}
	
	/**
	 * @param string|array $mixedValues
	 * @return array
	 * @uses calculateOutputValues()
	 * @throws Exception
	 */
	
	public function getOutputValue($mixedValues)
	{
		if(!is_string($mixedValues) && !is_array($mixedValues))
			throw new Exception('$mixedValues should be either string or array');
			
		$arrValues = array();
			
		if(is_string($mixedValues))
		{
			$arrValues = array($mixedValues);
		}
		else
		{
			$arrValues = $mixedValues;
		}
		
	  return $this->calculateOutputValues($arrValues);
	}
	
	/**
	 * @param array $arrValues
	 * @return array
	 * @throws Exception
	 */
	
	protected function calculateOutputValues($arrValues)
	{
		$arrReturn = array();
		
		$boolFound = FALSE;
		
		foreach($this->arrClassifiers as $intKey => $strClassifier)
		{
			$arrReturn[$intKey] = (in_array($strClassifier, $arrValues)) ? 1 : 0;
			
			if($arrReturn[$intKey] == 1)
				$boolFound = TRUE;
		}
		
		if(!$boolFound)
			throw new Exception('Classifier(s) "'. implode(', ', $arrValues) .'" not found');
		
		$intCountRemainingOutputs = $this->intMaxClassifiers - count($arrReturn);
	
		for($intIndex = 0; $intIndex < $intCountRemainingOutputs; $intIndex++)
		{
			$arrReturn[] = 0;
		}
		
		return $arrReturn;
	}
	
	/**
	 * @param string|array $mixedValues
	 * @return array
	 * @uses getOutputValue()
	 */
	
	public function __invoke($mixedValues)
	{
		return $this->getOutputValue($mixedValues);
	}
	
	/**
	 * @param array $arrValues
	 * @return array
	 */
	
	public function getRealOutputValue($arrValues)
	{
		$arrReturn = array();
	
		foreach($this->arrClassifiers as $intKey => $strClassifier)
		{
			if($arrValues[$intKey] == 1)
				$arrReturn[] = $strClassifier;
		}
		
		return $arrReturn;
	}
}
