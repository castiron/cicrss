<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	"CIC.$_EXTKEY",
	'Cicrss',
	'RSS Feed (CIC RSS Feed)'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'CIC RSS Feed');

$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_cicrss'] = 'pi_flexform';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_cicrss'] = 'layout,select_key';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY . '_cicrss', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_cicrss.xml');


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cicrss_domain_model_feed', 'EXT:cicrss/Resources/Private/Language/locallang_csh_tx_cicrss_domain_model_feed.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cicrss_domain_model_feed');
$TCA['tx_cicrss_domain_model_feed'] = array(
	'ctrl' => array(
		'title'						=> 'LLL:EXT:cicrss/Resources/Private/Language/locallang_db.xml:tx_cicrss_domain_model_feed',
		'label'						=> 'title',
		'tstamp'					=> 'tstamp',
		'crdate'					=> 'crdate',
		'versioningWS'				=> 2,
		'versioning_followPages'	=> TRUE,
		'origUid'					=> 't3_origuid',
		'languageField'				=> 'sys_language_uid',
		'transOrigPointerField'		=> 'l18n_parent',
		'transOrigDiffSourceField'	=> 'l18n_diffsource',
		'delete'					=> 'deleted',
		'enablecolumns'				=> array(
			'disabled'		=> 'hidden'
		),
		'dynamicConfigFile'			=> \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Feed.php',
		'iconfile'					=> \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_cicrss_domain_model_feed.gif'
	)
);
