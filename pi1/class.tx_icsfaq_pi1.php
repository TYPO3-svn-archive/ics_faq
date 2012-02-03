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
require_once(t3lib_extMgm::extPath('ics_flexdirectory') . 'pi1/class.tx_icsflexdirectory_pi1.php');


/**
 * Plugin 'List of questions' for the 'ics_faq' extension.
 *
 * @author	Virginie Sugere <virginie@in-cite.net>
 * @package	TYPO3
 * @subpackage	tx_icsfaq
 */
class tx_icsfaq_pi1 extends tx_icsfaq_common {
	var $prefixId      = 'tx_icsfaq_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_icsfaq_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'ics_faq';	// The extension key.
	var $pi_checkCHash = true;
	
	var $storage;
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
		
		$this->storage = $this->getFolderStorage();
		
		
		$questions = $this->listRows();
		$content .= $this->renderViewList($questions);
		
		return $this->pi_wrapInBaseClass($content);
	}
	
	function listRows() {
		$where = $selectField = $join = array();
		$where[] = $this->tables['question'].'.pid = '.$this->storage;
		$order = 'crdate desc';
		
		if($this->piVars['sort']) {
			$order = str_replace('_', ' ', $this->piVars['sort']);
		}
		
		if($this->piVars['word']) {
			$where_search[] = 'question LIKE "%'.$this->piVars['word'].'%"';
			$where_search[] = 'firstname LIKE "%'.$this->piVars['word'].'%"';
			$where_search[] = 'name LIKE "%'.$this->piVars['word'].'%"';
		}
		
		//récupérer le nombre de réponse pour la question associée
		$selectField[] = '('. $GLOBALS['TYPO3_DB']->SELECTquery(
				'COUNT(`'. $this->tables['answer'] .'`.uid ) as nbanswer',
				$this->tables['answer'],
				'question_id = '.$this->tables['question'] .'.uid'
			).') AS nbanswer';
		
		//récupérer la date de la dernière réponse pour la question associée
		$selectField[] = '('. $GLOBALS['TYPO3_DB']->SELECTquery(
				'crdate',
				$this->tables['answer'],
				'question_id = '.$this->tables['question'] .'.uid',
				'',
				'crdate desc',
				'1'
			).') as last_anwser_date';
		
		//liaison avec la base contact (annuaire)
		$join[] = 'JOIN '.$this->tables['bc_index_rel'].' ON '.$this->tables['question'].'.fe_cruser_id = '.$this->tables['bc_index_rel'].'.value';
		$join[] = 'JOIN '.$this->tables['bc_index'].' ON '.$this->tables['bc_index'].'.sheet = '.$this->tables['bc_index_rel'].'.sheet';
		$join[] = 'JOIN '.$this->tables['bc'].' ON '.$this->tables['bc'].'.uid = '.$this->tables['bc_index_rel'].'.sheet';
		$where[] = $this->tables['bc_index_rel'].'.field = "feuser"';
		$where[] = $this->tables['bc_index'].'.field = "prenom"';
		$selectField[] = $this->tables['bc_index'].'.value as firstname';
		$selectField[] = $this->tables['bc'].'.name as name';
		
		$where = implode(' AND ', $where);
		$selectField = implode(', ', $selectField);
		$join = implode(' ', $join);
		
		$questions = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			$this->tables['question'].'.*, '.$selectField,
			$this->tables['question']. ' '.$join,
			$where.' '.$this->cObj->enableFields($this->tables['question']),
			'',
			$order
		);

		return $questions;
	}
	
	function renderViewList($questions) {
		$output = '';
		$template = $this->getTemplateFile();
		$content_template = $this->cObj->getSubpart(file_get_contents($template), '###TEMPLATE_PAGE###');
		
		$link_add_question = $this->pi_linkTP_keepPIvars_url(array(),1,1, $this->conf['add_page']);
		$markers = array(
			'###TITLE###' => $this->pi_getLL('title_plugin'),
			'###TEXT_ADD_QUESTION###' => $this->pi_getLL('text_add_question'),
			'###BTN_ADD_QUESTION###' =>  '<a href="'. $link_add_question .'">'. $this->pi_getLL('btn_add_question') .'</a>',
			'###FORM_SEARCH_URL###' => $this->pi_linkTP_keepPIvars_url(array(),0,1),
			'###PREFFIX###' => $this->prefixId,
			'###LABEL_WORD###' => $this->pi_getLL('label_word'),
			'###WORD_NAME###' => $this->prefixId.'[word]',
			'###WORD_VALUE###' => $this->piVars['word'] ? $this->piVars['word'] : '',
			'###SUBMIT_SEARCH_NAME###' => 'search',
			'###SUBMIT_SEARCH_VALUE###' => $this->pi_getLL('btn_search_value'),
			'###FORM_SORT_URL###' => $this->pi_linkTP_keepPIvars_url(array(),0,1),
			'###LABEL_SORT###' => $this->pi_getLL('label_sort'),
			'###SORT_NAME###' => $this->prefixId.'[sort]',
			'###SORT_SELECT###' =>$this->getOptionsSort($this->piVars['sort']),
			'###SUBMIT_SORT_NAME###' =>  'btnSort',
			'###SUBMIT_SORT_VALUE###' => $this->pi_getLL('btn_sort_value'),
			'###PAGE_ID###' => $GLOBALS['TSFE']->id,
		);
		$content_template = $this->cObj->substituteMarkerArray($content_template, $markers);
		
		if(is_array($questions) && !empty($questions)){
			foreach($questions as $question) {
				$link = $this->pi_linkTP_keepPIvars_url(array(uid => $question['uid']),0);
				$subpart = $this->cObj->getSubpart($content_template, '###TEMPLATE_FAQ_LI###');
				
				$feusers = $this->getFe_users($question['fe_cruser_id']);
				$datasUser = t3lib_div::xml2array($feusers['flex_type']);
				$name = $datasUser['data']['sDEF']['lDEF']['prenom']['vDEF'] .' '. $feusers['name'];
				$markers = array(
					'###QUESTION_TITLE###' => '<a href="'. $link .'">'. $question['question'] .'</a>',
					'###QUESTION_DATE_LABEl###' => $this->pi_getLL('question_date_label'),
					'###QUESTION_DATE###' => $this->diff_date($question['crdate']),
					'###CREATE_BY_LABEL###' => $this->pi_getLL('createBy_label'),
					'###CREATE_BY###' => $name,
					'###NB_ANSWER###' => ($question['nbanswer'] > 0) ? $question['nbanswer'] : $this->pi_getLL('no_answer'),
					'###ANSWER_LABEL###' => ($question['nbanswer'] > 1) ? $this->pi_getLL('answers_label') : (($question['nbanswer'] == 0) ? '' : $this->pi_getLL('answer_label')),
					'###LAST_ANSWER_DATE_LABEL###' => ($question['nbanswer'] > 0) ? $this->pi_getLL('lastAnswer_date_label'): '',
					'###LAST_ANSWER_DATE###' => $question['last_anwser_date'] ? $this->diff_date($question['last_anwser_date']) : '',
				);
				$output.= $this->cObj->substituteMarkerArray($subpart, $markers);	
			}
			$content_template = $this->cObj->substituteSubpart($content_template, '###TEMPLATE_FAQ_LI###', $output);
		}
		else {
			$content_template = $this->cObj->substituteSubpart($content_template, '###TEMPLATE_FAQ_LI###', '<li>'.$this->pi_getLL('no_question').'</li>');
		}
		return $content_template;
	}
	
	function diff_date($date) { 
		$second = floor(time() - $date);
		if ($second == 0) return "0";
		
		$date = '';
		if((date('Y', $second)-1970) > 1)
			$date = (date('Y', $second)-1970).' '.$this->pi_getLL('label_years');
		elseif((date('Y', $second)-1970) == 1)
			$date = (date('Y', $second)-1970).' '.$this->pi_getLL('label_year');
		elseif(((date('m', $second)-1)) >= 1)
			$date = (date('m', $second)-1).' '.$this->pi_getLL('label_month');
		elseif(((date('d', $second)-1)%7) > 1)
			$date = ((date('d', $second)-1)%7).' '.$this->pi_getLL('label_days');
		elseif(((date('d', $second)-1)%7) == 1)
			$date = ((date('d', $second)-1)%7).' '.$this->pi_getLL('label_day');
		elseif((date('H', $second)-1) > 1) 
			$date = (date('H', $second)-0).' '.$this->pi_getLL('label_hours');
		elseif((date('H', $second)-1) == 1) 
			$date = (date('H', $second)-0).' '.$this->pi_getLL('label_hour'); 
		elseif((date('i', $second)-1) >= 1) 
			$date = (date('i', $second)-0).' '.$this->pi_getLL('label_minutes'); 
		elseif((date('i', $second)-1) == 1) 
			$date = (date('i', $second)-0).' '.$this->pi_getLL('label_minute');
		elseif((date('s', $second)-0) >= 1)
			$date = (date('s', $second)).' '.$this->pi_getLL('label_seconds');
		elseif((date('s', $second)-0) ==1 )
			$date = (date('s', $second)).' '.$this->pi_getLL('label_second');

		return $date;
	}
	
	function getFe_users($users) {
		$bcpi1 = t3lib_div::makeInstance('tx_icsflexdirectory_pi1');
		$bcpi1->cObj = $this->cObj;
		$user = $bcpi1->getContacts(
			array(
				'types' => array(8) ,
				'criteria' => array(
					array(
						'field' => 't8_feuser',
						'operator' => '==',
						'value' => $users
					),
				),
			)
		);
		if($user)
			return $user[0];
		else
			return false;
	}
	
	
	function getOptionsSort($value, $getEmpty = false){
		$entries = array(
			'crdate_desc' => $this->pi_getLL('date_desc'),
			'crdate_asc' => $this->pi_getLL('date_asc'),
			'nbanswer_desc' => $this->pi_getLL('anwser_desc'),
			'nbanswer_asc' => $this->pi_getLL('anwser_asc'),
			/*'approval_desc' => $this->pi_getLL('approval_desc'),
			'approval_asc' => $this->pi_getLL('approval_asc'),*/
		);
		$output = '';
		if(empty($entries))
			return '';
			
		if($getEmpty) {
			$output .= '<option value="0"></option>';
		}
		
		foreach($entries as $key=>$entry){
			$selected = '';
			if($value == $key)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$key.'" '.$selected.'>'.$entry.'</option>';			
		}
		return $output;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ics_faq/pi1/class.tx_icsfaq_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ics_faq/pi1/class.tx_icsfaq_pi1.php']);
}

?>