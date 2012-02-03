<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 In cite solution <technique@in-cite.net>
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

require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_icsfaq_common extends tslib_pibase
{
	var $tables	= array(
		'fe_users' => 'fe_users',
		'question' => 'tx_icsfaq_question',
		'answer' => 'tx_icsfaq_answer',
		'approval' => 'tx_icsfaq_approval',
		'bc_index_rel' => 'tx_icsflexdirectory_index_rel',
		'bc_index' => 'tx_icsflexdirectory_index',
		'bc' => 'tx_icsflexdirectory_sheets',
	);
	var $confCommon = array();
	
	function tx_icsfaq_common()
	{
		tslib_pibase::tslib_pibase();
		global $TYPO3_CONF_VARS, $TSFE;
		if (isset($TYPO3_CONF_VARS['EXT']['extConf'][$this->extKey]) && !empty($TYPO3_CONF_VARS['EXT']['extConf'][$this->extKey]))
		{
			if (is_array($TYPO3_CONF_VARS['EXT']['extConf'][$this->extKey]))
			{
				$this->extConf = $TYPO3_CONF_VARS['EXT']['extConf'][$this->extKey];
			}
			else
			{
				$this->extConf = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$this->extKey]);
			}
		}
		$this->confCommon = $TSFE->tmpl->setup['plugin.']['tx_icsfaq_common.'];
		
	}

    /**
	 * Retrieve template file name.
	 *
	 * @param $mode string The rendering mode.
	 * @return string The template filename and path.
	 */
	function getTemplateFile($mode = '')
	{
		$template = '';
		$templates = $this->getTemplateFiles($mode);
		if (!empty($templates))
			$template = $templates[0];
		return $template;
	}
	
    /**
	 * Retrieve available template file names.
	 *
	 * @param $mode string The rendering mode.
	 * @return array All available template filename <ith full path.
	 */
	function getTemplateFiles($mode)
	{
		$templates = array();
		if (isset($this->conf['templatePath']) && is_dir(t3lib_div::getFileAbsFileName($this->conf['templatePath'])))
		{
			if (isset($this->conf['defaultTemplate']) && is_file(t3lib_div::getFileAbsFileName($this->conf['templatePath']) . $this->conf['defaultTemplate']))
			{
				$templates[] = t3lib_div::getFileAbsFileName($this->conf['templatePath']) . $this->conf['defaultTemplate'];
			}
			if (isset($this->conf['template']) && is_file(t3lib_div::getFileAbsFileName($this->conf['templatePath']) . $this->conf['template']))
			{
				$templates[] = t3lib_div::getFileAbsFileName($this->conf['templatePath']) . $this->conf['template'];
			}
		}
		if (isset($this->conf[$mode]['templatePath']) && is_dir(t3lib_div::getFileAbsFileName($this->conf[$mode]['templatePath'])))
		{
			if (isset($this->conf[$mode]['defaultTemplate']) && is_file(t3lib_div::getFileAbsFileName($this->conf[$mode]['templatePath']) . $this->conf[$mode]['defaultTemplate']))
			{
				$templates[] = t3lib_div::getFileAbsFileName($this->conf[$mode]['templatePath']) . $this->conf[$mode]['defaultTemplate'];
			}
			if (isset($this->conf[$mode]['template']) && is_file(t3lib_div::getFileAbsFileName($this->conf[$mode]['templatePath']) . $this->conf[$mode]['template']))
			{
				$templates[] = t3lib_div::getFileAbsFileName($this->conf[$mode]['templatePath']) . $this->conf[$mode]['template'];
			}
		}
		array_reverse($templates);
		// TODO: plugin configuration take precedence.
		return $templates;
	}
	
	/**
	 * Retrieve the specified subpart from the first available template in order of priority which declares the part.
	 *
	 * @param $mode string The rendering mode.
	 * @param $partMarker string The name of the subpart marker to extract.
	 * @return array The template name as first element and the template content as second. Empty if inexistant.
	 */
	function getTemplate($mode, $partMarker)
	{
		$templates = $this->getTemplateFiles($mode);
		$return = array();
		foreach($templates as $template)
		{
			$content = $this->cObj->getSubpart(file_get_contents($template), $partMarker);
			if (trim($content) != '')
			{
				$return[0] = basename($template);
				$return[1] = $content;
				break;
			}
		}
		return $return;
	}
    
    /**
	 * Retrieve template configuration.
	 *
	 * @param $mode string The rendering mode.
	 * @param $template string The template name.
	 * @param $field string The field name.
	 * @param $property string The needed property. (if needed)
	 * @return array The conf data.
	 */
	function getTemplateConf($mode, $template, $field, $property = '')
	{
		$modes = array($mode . '.', 'default.');
		$templates = array($template . '.', 'default.');
		if (!empty($property))
		{
			foreach ($modes as $m)
				foreach ($templates as $t)
				{
					if (isset($this->conf['templates.'][$t][$m][$field . '.'][$property . '.']))
						return $this->conf['templates.'][$t][$m][$field . '.'][$property . '.'];
				}
		}
		foreach ($modes as $m)
			foreach ($templates as $t)
			{
				if (isset($this->conf['templates.'][$t][$m][$field . '.']))
					return $this->conf['templates.'][$t][$m][$field . '.'];
			}
		return array();
	}
	
	/**
	 * Retrieve template configuration value.
	 *
	 * @param $mode string The rendering mode.
	 * @param $template string The template name.
	 * @param $field string The field name.
	 * @param $property string The needed property. (if needed)
	 * @return string The conf value.
	 */
	function getTemplateConfValue($mode, $template, $field, $property = '')
	{
		$modes = array($mode . '.', 'default.');
		$templates = array($template . '.', 'default.');
		if (!empty($property))
		{
			foreach ($modes as $m)
				foreach ($templates as $t)
				{
					if (isset($this->conf['templates.'][$t][$m][$field . '.'][$property]))
						return $this->conf['templates.'][$t][$m][$field . '.'][$property];
				}
		}
		foreach ($modes as $m)
			foreach ($templates as $t)
			{
				if (isset($this->conf['templates.'][$t][$m][$field]))
					return $this->conf['templates.'][$t][$m][$field];
			}
		return '';
	}
	
	function getFolderStorage() {
		$storage = $this->extConf['storage'];
		if($this->cObj->data['pages'])
			$storage = $this->cObj->data['pages'];
		elseif($this->conf['storage'])
			$storage = $this->conf['storage'];
		return $storage;
	}
	

    function sendMail($subject, $from_email, $from_name, $content, $registers){
       if(is_array($registers) && !empty($registers)){
            $mail = t3lib_div::makeInstance('t3lib_htmlmail'); // Instanciation de l'objet mail
            $mail->start();	// Initilisation
            $mail->useBase64();	// Choix du type d'encodage ? utiliser
            $mail->subject = $subject; // Sujet du mail
            $mail->from_email = $from_email; // Valeur du champ FROM du mail
            $mail->from_name = $from_name; // Valeur du champ FROM du mail
            $mail->replyto_email = $from_email; // Valeur du champ REPLY TO
            $mail->replyto_name = $from_name; // Valeur du champ REPLY TO
            
            
            $mail->addPlain($content); // ??
			$mail->addPlain($content); // ??
			 $mail->setHTML ($mail->encodeMsg($content)); // IMPORTANT
			 $mail->setHeaders();
			$mail->setContent();
		
            foreach($registers as $dest){
                $mail->send($dest); // DESTINATAIRES
            }	
        }
    }
}