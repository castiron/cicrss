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

