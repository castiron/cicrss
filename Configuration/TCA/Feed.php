<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_cicrss_domain_model_feed'] = array(
	'ctrl' => $TCA['tx_cicrss_domain_model_feed']['ctrl'],
	'interface' => array(
		'showRecordFieldList'	=> 'title,address,update_interval'
	),
	'types' => array(
		'1' => array('showitem'	=> 'title,address,update_interval')
	),
	'palettes' => array(
		'1' => array('showitem'	=> '')
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude'			=> 1,
			'label'				=> 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config'			=> array(
				'type'					=> 'select',
				'foreign_table'			=> 'sys_language',
				'foreign_table_where'	=> 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.php:LGL.default_value', 0)
				)
			)
		),
		'l18n_parent' => array(
			'displayCond'	=> 'FIELD:sys_language_uid:>:0',
			'exclude'		=> 1,
			'label'			=> 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config'		=> array(
				'type'			=> 'select',
				'items'			=> array(
					array('', 0),
				),
				'foreign_table' => 'tx_cicrss_domain_model_feed',
				'foreign_table_where' => 'AND tx_cicrss_domain_model_feed.uid=###REC_FIELD_l18n_parent### AND tx_cicrss_domain_model_feed.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array(
			'config'		=>array(
				'type'		=>'passthrough'
			)
		),
		't3ver_label' => array(
			'displayCond'	=> 'FIELD:t3ver_label:REQ:true',
			'label'			=> 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
			'config'		=> array(
				'type'		=>'none',
				'cols'		=> 27
			)
		),
		'hidden' => array(
			'exclude'	=> 1,
			'label'		=> 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'	=> array(
				'type'	=> 'check'
			)
		),
		'address' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:cicrss/Resources/Private/Language/locallang_db.xml:tx_cicrss_domain_model_feed.address',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'title' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:cicrss/Resources/Private/Language/locallang_db.xml:tx_cicrss_domain_model_feed.title',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'update_interval' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:cicrss/Resources/Private/Language/locallang_db.xml:tx_cicrss_domain_model_feed.update_interval',
			'config'	=> array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
	),
);
?>