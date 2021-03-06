<?php
	/**
	 * Module-Metadata for the WBL Tracker.
	 * @author blange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Modules
	 * @subpackage oxAutoload
	 * @version SVN: $Id$
	 */

	$sMetadataVersion = '1.0';

	$aModule = array(
		'author'       => 'WBL Konzept',
		'description'  => array(
			'de' => 'Framework um verschiedenste Tracker mit wenig Anpassungen zu implementieren',
			'en' => 'Special Framework for integrating various tracker'
		),
		'email'        => 'code@wbl-konzept.de',
		'extend'       => array(
			'oxbasket'     => 'WBL_Tracker_Basket',
			'oxbasketitem' => 'WBL_Tracker_Basket_Item',
			'oxoutput'     => 'WBL_Tracker_Output',
		),
		'files'        => array(
			'WBL_Tracker_Basket'      => 'WBL/Tracker/Basket.php',
			'WBL_Tracker_Basket_Item' => 'WBL/Tracker/Basket/Item.php',
			'WBL_Tracker_Output'      => 'WBL/Tracker/Output.php'
		),
		'id'           => 'WBL_Tracker',
		'settings'     => array_merge(
			array(
				array(
					'group' => 'WBL_Tracker_Main',
					'name'  => 'bWBLTrackerIsXMLValid',
					'type'  => 'bool',
					'value' => true
				)
			),
			class_exists('WBL_Tracker_Adapter_Loader', true)
				? WBL_Tracker_Adapter_Loader::getInstance()->getExtendArrayForMetaData()
				: array()
		),
		'title'        => 'WBL Webtracking Framework',
		'thumbnail'    => 'wbl_logo.jpg',
		'url'          => 'http://wbl-konzept.de',
		'version'      => '1.0.0'
	);