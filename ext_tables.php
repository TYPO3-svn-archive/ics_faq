<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$TCA['tx_icsfaq_question'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_question',		
		'label'     => 'question',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_icsfaq_question.gif',
	),
);

$TCA['tx_icsfaq_answer'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_answer',		
		'label'     => 'response',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_icsfaq_answer.gif',
	),
);

$TCA['tx_icsfaq_approval'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_approval',		
		'label'     => 'uid',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_icsfaq_approval.gif',
	),
);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:ics_faq/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');


if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_icsfaq_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_icsfaq_pi1_wizicon.php';
}


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi2']='layout,select_key';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:ics_faq/locallang_db.xml:tt_content.list_type_pi2',
	$_EXTKEY . '_pi2',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');


if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_icsfaq_pi2_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi2/class.tx_icsfaq_pi2_wizicon.php';
}


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi3']='layout,select_key';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:ics_faq/locallang_db.xml:tt_content.list_type_pi3',
	$_EXTKEY . '_pi3',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');


if (TYPO3_MODE == 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_icsfaq_pi3_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi3/class.tx_icsfaq_pi3_wizicon.php';
}



t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/","List questions");
t3lib_extMgm::addStaticFile($_EXTKEY,"pi2/static/","Details question");
t3lib_extMgm::addStaticFile($_EXTKEY,"pi3/static/","Add new question");

$TCA['tx_icsfaq_question']['ctrl']['fe_cruser_id'] = 'fe_cruser_id';
$TCA['tx_icsfaq_answer']['ctrl']['fe_cruser_id'] = 'fe_cruser_id';
$TCA['tx_icsfaq_approval']['ctrl']['fe_cruser_id'] = 'fe_cruser_id';
?>