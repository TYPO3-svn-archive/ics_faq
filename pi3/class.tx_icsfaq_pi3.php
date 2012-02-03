<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 In Cite Solution <technique@in-cite.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(t3lib_extMgm::extPath('ics_faq') . 'class/class.tx_icsfaq_common.php');


/**
 * Plugin 'Ask a question' for the 'ics_faq' extension.
 *
 * @author	Virginie Sugere <virginie@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_icsfaq
 */
class tx_icsfaq_pi3 extends tx_icsfaq_common {
	var $prefixId      = 'tx_icsfaq_pi3';		// Same as class name
	var $scriptRelPath = 'pi3/class.tx_icsfaq_pi3.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'ics_faq';	// The extension key.
	var $pi_checkCHash = true;
	
	var $required_field = array();
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
		$storage = $this->getFolderStorage();
		
		$template = $this->getTemplateFile();
		$content = $this->cObj->getSubpart(file_get_contents($template), '###TEMPLATE_PAGE###');
		
		$this->required_field = explode(',', $this->conf['required_field']);
		
		if($this->piVars['add']) {
			$errors = $this->checkField();
			if(!$errors)
				$content = $this->saveQuestion();
			else {
				$markers = $this->getMarkers($errors);
				$content = $this->cObj->substituteMarkerArray($content, $markers);
			}
		}
		else {
			$markers = $this->getMarkers();
			$content = $this->cObj->substituteMarkerArray($content, $markers);
		}
	
		return $this->pi_wrapInBaseClass($content);
	}
	
	function getMarkers($errors = '') {
		$markers = array(
			'###FORM_URL###' =>  $this->pi_linkTP_keepPIvars_url(array(),1),
			'###FORM_NAME###' => 'form_add',
			'###PREFIX###' => $this->prefixId,
			'###MESSAGE###' => $this->pi_getLL('message_add_question'),
			'###ERROR###' => $errors,
			'###QUESTION_LABEL###' => $this->pi_getLL('question_label'),
			'###REQUIRED_FIELD_QUESTION###' => in_array('question', $this->required_field) ? '*' : '', 
			'###QUESTION_NAME###' =>  $this->prefixId.'[question]',
			'###QUESTION_VALUE###' => $this->piVars['question'] ? $this->piVars['question'] : '',
			'###DESCRIPTION_LABEL###' => $this->pi_getLL('description_label'),
			'###DESCRIPTION_NAME###' =>  $this->prefixId.'[description]',
			'###REQUIRED_FIELD_DESCRIPTION###' => in_array('description', $this->required_field) ? '*' : '',
			'###DESCRIPTION_VALUE###' => $this->piVars['description'] ? $this->piVars['description'] : '',
			'###EXPLAIN_REQUIRED_FIELD###' => $this->pi_getLL('required_field'),
			'###SUBMIT_VALUE###' => $this->pi_getLL('submit_label'),
			'###SUBMIT_NAME###' =>   $this->prefixId.'[add]',
		);
		
		return $markers;
	}
	
	function checkField() {
		$errors = '';
		foreach($this->required_field as $field) {
			if(!$this->piVars[$field])
				$errors .= $this->pi_getLL('error_missing_'.$field).'<br />';
		}
		return $errors;
	}
	
	function saveQuestion() {
		$req = $this->cObj->DBgetInsert(
			$this->tables['question'],
			$this->getFolderStorage(),
			array(
				'question' => $this->piVars['question'],
				'description' => $this->piVars['description'],
			),
			'question,description',
			TRUE
		);
		if($req) {
			$template = $this->getTemplateFile();
			$content = $this->cObj->getSubpart(file_get_contents($template), '###TEMPLATE_CONFIRM_ADD###');
			$marker_tmp = array(
				'###CONFIRM_ADD_OK###' => $this->pi_getLL('confirm_add'),
			);
			$content = $this->cObj->substituteMarkerArray($content, $marker_tmp);
			return $content;
		}
		else
			return FALSE;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ics_faq/pi3/class.tx_icsfaq_pi3.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ics_faq/pi3/class.tx_icsfaq_pi3.php']);
}

?>