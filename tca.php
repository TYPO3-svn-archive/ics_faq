<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_icsfaq_question'] = array (
	'ctrl' => $TCA['tx_icsfaq_question']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,question,description,fe_cruser_id'
	),
	'feInterface' => $TCA['tx_icsfaq_question']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'question' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_question.question',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
			)
		),
		'description' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_question.description',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'fe_cruser_id' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_question.fe_cruser_id',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',  	
				'allowed' => 'fe_users',    
				'size' => 1,    
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, question, description, fe_cruser_id')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);



$TCA['tx_icsfaq_answer'] = array (
	'ctrl' => $TCA['tx_icsfaq_answer']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,answer,question_id,fe_cruser_id'
	),
	'feInterface' => $TCA['tx_icsfaq_answer']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'answer' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_answer.answer',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'question_id' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_answer.question_id',		
			'config' => array (
				'type' => 'select',	
				'foreign_table' => 'tx_icsfaq_question',	
				'foreign_table_where' => 'ORDER BY tx_icsfaq_question.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'fe_cruser_id' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_answer.fe_cruser_id',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',  	
				'allowed' => 'fe_users',    
				'size' => 1,    
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, answer, question_id, fe_cruser_id')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);



$TCA['tx_icsfaq_approval'] = array (
	'ctrl' => $TCA['tx_icsfaq_approval']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,fe_cruser_id,response_id'
	),
	'feInterface' => $TCA['tx_icsfaq_approval']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'fe_cruser_id' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_approval.fe_cruser_id',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',  	
				'allowed' => 'fe_users',    
				'size' => 1,    
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'response_id' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:ics_faq/locallang_db.xml:tx_icsfaq_approval.response_id',		
			'config' => array (
				'type' => 'select',	
				'foreign_table' => 'tx_icsfaq_answer',	
				'foreign_table_where' => 'ORDER BY tx_icsfaq_answer.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, fe_cruser_id, response_id')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>