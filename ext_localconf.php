<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Cicrss',
	array(
		'Feed' => 'default',
	),
	array(
		'Feed' => 'default',
	)
);

// If cache is not already defined, define it
if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cicrss_cache'])) {
    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cicrss_cache'] = array();
}

?>