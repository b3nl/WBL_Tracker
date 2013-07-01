<?php
    require_once realpath(dirname(__FILE__) . '/WBL/Modules/Autoloader.php');
    $oAutoloader = new WBL_Modules_Autoloader();

    spl_autoload_register(array(
        $oAutoloader
	        ->addCoreOverride('oxsession',     'WBL_Modules_Session') // Fix against #0004262
            ->addCoreOverride('oxutilsobject', 'WBL_Modules_UtilsObject')
            ->setAutoloaderNamespaces(array('WBL')),
        'includeClass'
    ));
    unset($oAutoloader);