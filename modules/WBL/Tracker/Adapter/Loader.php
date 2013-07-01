<?php
	/**
	 * ./modules/WBL/Tracker/Loader.php
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Adapter
	 * @version SVN: $Id$
	 */

	/**
	 * Loading factory for the adapter classes.
	 * @author Bjoern Simon Lange <code@wbl-konzept.de>
	 * @category modules
	 * @package WBL_Tracker
	 * @subpackage Adapter
	 * @version SVN: $Id$
	 */
	class WBL_Tracker_Adapter_Loader extends oxSuperCfg {
		/**
		 * Where to look for the meta files without the vendor dir.
		 * @var array
		 */
		protected $aBasePaths = array();

		/**
		 * The evaluated meta files for the trackers.
		 * @var array|void
		 */
		protected $aTrackerConfigs = null;

		/**
		 * Singleton
		 * @var WBL_Tracker_Adapter_Loader
		 */
		static protected $oSelf = null;

		/**
		 * The name of the meta file.
		 * @var string
		 */
		protected $sMetaFileName = self::DEFAULT_META_FILE;

		/**
		 * Cache key for the config.
		 * @var string
		 */
		const CACHE_KEY_PREFIX = 'aWBLTrackerConfigsArray';

		/**
		 * Cache key marking if the tracker should be rendered xml valid?
		 * @var string
		 */
		const CONFIG_KEY_IS_XML = 'bWBLTrackerIsXMLValid';

		/**
		 * This name is used for the Dir of the adapter trackers and as the default adapter name.
		 * @var string
		 */
		const DEFAULT_ADAPTER = 'Adapter';

		/**
		 * The default file ending for adapter files.
		 * @var string
		 */
		const DEFAULT_FILE_ENDING = 'php';

		/**
		 * The default meta file name.
		 * @var string
		 */
		const DEFAULT_META_FILE = 'trackerdata.php';

		/**
		 * The default namespace for the tracker adapter.
		 * @var string
		 */
		const DEFAULT_NAMESPACE = 'WBL_Tracker';

		/**
		 * The alias used as vendor id, if the vendor name is too long.
		 * @var string
		 */
		const TRACKER_CONFIG_KEY_ID_ALIAS = 'alias';

		/**
		 * Config-Key for the tracker adapter file extension.
		 * @var string
		 */
		const TRACKER_CONFIG_KEY_FILE_EXTENSION = 'file_extension';

		/**
		 * Config-Key for the tracker adapter files.
		 * @var string
		 */
		const TRACKER_CONFIG_KEY_FILES = 'files';

		/**
		 * Custom-Namespace for the tracker classes.
		 * @var string
		 */
		const TRACKER_CONFIG_KEY_NAMESPACE = 'namespace';

		/**
		 * Overriding class name for a standard adapter.
		 * @var string
		 */
		const TRACKER_CONFIG_KEY_STANDARD_ADAPTER = 'standard_adapter';

		/**
		 * Adds a tracker base path.
		 *
		 * The proccessing order is LIFO.
		 * @author blange <code@wbl-konzept.de>
		 * @param string $sPath
		 * @return WBL_Tracker_Adapter_Loader
		 */
		public function addBasePath($sPath) {
			if ($sPath = realpath($sPath)) {
				$this->aBasePaths[] = $sPath . DIRECTORY_SEPARATOR;
				$this->aBasePaths   = array_unique($this->aBasePaths);
			} // if

			return $this;
		} // function

		/**
		 * Cacht die Config fuer die entsprechenden Pfade.
		 * @author blange <code@wbl-konzept.de>
		 * @param array $aConfigs
		 * @param array $aPaths
		 * @return WBL_Tracker_Adapter_Loader
		 */
		public function cacheTrackerConfigs(array $aConfigs, array $aPaths) {
			oxUtils::getInstance()->toPhpFileCache(self::CACHE_KEY_PREFIX . md5(serialize($aPaths)), $aConfigs);

			return $this;
		} // function

		/**
		 * Returns the tracker adapter for the view or email.
		 * @author blange <code@wbl-konzept.de>
		 * @param string $sClass View- or classname.
		 * @return WBL_Tracker_Adapter_Interface[]
		 * @throws oxSystemComponentException If an adapter can not be found.
		 */
		public function getAdaptersForClass($sClass) {
			$aTrackers = array();

			if ($aConfig = $this->getTrackerConfigs()) {
				$oConfig      = $this->getConfig();
				$bIsXML       = $oConfig->getConfig()->getConfigParam(self::CONFIG_KEY_IS_XML);
				$sClass       = strtolower($sClass);
				$sFilesKey    = self::TRACKER_CONFIG_KEY_FILES;
				$sStandardKey = self::TRACKER_CONFIG_KEY_STANDARD_ADAPTER;

				foreach ($aConfig as $sVendor => $aVendorConfig) {
					$sActiveVendorKey = ($sAlias = $aVendorConfig[self::TRACKER_CONFIG_KEY_ID_ALIAS])
						? $sAlias
						: $sVendor;

					if ($oConfig->getConfigParam('bIsWBLTracker' . $sActiveVendorKey .'Active')) {
						/* @var $oTracker WBL_Tracker_Adapter_Interface */
						$oTracker = null;

						if ((@$aFiles = $aVendorConfig[$sFilesKey]) && (@$sAdapter = $aFiles[$sClass])) {
							$oTracker = wblNew($sAdapter);
						} // if
						elseif ($sStandard = @$aVendorConfig[$sStandardKey]) {
							$oTracker = wblNew($sStandard);
						} // elseif

						if (($oTracker instanceof WBL_Tracker_Adapter_Interface) &&
							($oTracker->isForClass($sClass))) {
							$aTrackers[] = $oTracker;

							$oTracker->isXHTML($bIsXML);
						} // if
					} // if
				} // foreach
				unset($oTracker);
			} // if

			return $aTrackers;
		} // function

		/**
		 * Returns the used base paths for find the meta data files.
		 * @author blange <code@wbl-konzept.de>
		 * @return array
		 */
		protected function getBasePaths() {
			return $this->aBasePaths;
		} // function

		/**
		 * Returns the cached config for the base paths.
		 * @author blange <code@wbl-konzept.de>
		 * @return array|void
		 * @todo fill!
		 */
		protected function getCachedTrackerConfigs(array $aPaths) {
			$mTmp = oxUtils::getInstance()->fromPhpFileCache(self::CACHE_KEY_PREFIX . md5(serialize($aPaths)));

			return $mTmp ? $mTmp : null;
		} // function

		/**
		 * Returns the extend array for the metadata file.
		 * @author blange <code@wbl-konzept.de>
		 * @return array
		 */
		public function getExtendArrayForMetaData() {
			$aExtend = array();

			if ($aConfig = $this->getTrackerConfigs()) {
				foreach ($aConfig as $sVendor => $aVendorConfig) {
					$sActiveVendorKey = ($sAlias = $aVendorConfig[self::TRACKER_CONFIG_KEY_ID_ALIAS])
						? $sAlias
						: $sVendor;


					$aExtend[] = array(
						'group' => 'WBL_Tracker_' . $sVendor,
						'name'  => "bIsWBLTracker{$sActiveVendorKey}Active",
						'type'  => 'bool',
						'value' => false
					);

					if ($aVendorConfig['settings']) {
						foreach ($aVendorConfig['settings'] as $aSetting) {
							$aExtend[] = array_merge(
								array('group' => 'WBL_Tracker_' . $sVendor),
								$aSetting
							);
						} // foreach
					} // if
				} // foreach
			} // if

			return $aExtend;
		} // function

		/**
		 * Singleton.
		 * @author blange <code@wbl-konzept.de>
		 * @return WBL_Tracker_Adapter_Loader
		 */
		static public function getInstance()
		{
			if (!self::$oSelf)
			{
				self::setInstance(wblNew('WBL_Tracker_Adapter_Loader'))
				->addBasePath(dirname(__FILE__) . '/..');
			} // if

			return self::$oSelf;
		} // function

		/**
		 * Returns the used meta file name.
		 * @author blange <code@wbl-konzept.de>
		 * @return string
		 */
		protected function getMetaFileName() {
			return $this->sMetaFileName;
		} // function

		/**
		 * Returns the config array for the tracker configs.
		 * @author blange <code@wbl-konzept.de>
		 * @return array
		 */
		public function getTrackerConfigs() {
			if ($this->aTrackerConfigs === null) {
				$this->setTrackerConfigs($this->loadTrackerConfigs());
			} // if

			return $this->aTrackerConfigs;
		} // function

		/**
		 * Loads the tracker config from the meta file.
		 * @author blange <code@wbl-konzept.de>
		 * @param  array  $aOverallConfig The allready created config.
		 * @param  string $sMetaFile      The meta file.
		 * @param  string $sVendor        The name of the vendor.
		 * @return array
		 * @todo   Caching
		 */
		protected function loadTrackerConfigForVendor(array $aOverallConfig, $sMetaFile, $sVendor) {
			$aConfig = array();

			if ((is_readable($sMetaFile)) && (is_array($aConfigFromFile = include $sMetaFile))) {
				$aConfig    = array(self::TRACKER_CONFIG_KEY_FILES => array());
				$bDefNameS  = true;
				$sFileExt   = ($sTemp = @$aConfigFromFile[self::TRACKER_CONFIG_KEY_FILE_EXTENSION]) ? $sTemp : self::DEFAULT_FILE_ENDING;
				$sNamespace = self::DEFAULT_NAMESPACE;
				$sVendorDir = dirname($sMetaFile);
				$sSep       = DIRECTORY_SEPARATOR;
				$sStandard  = ($sTemp =  @$aConfigFromFile[self::TRACKER_CONFIG_KEY_STANDARD_ADAPTER]) ? $sTemp : 'Adapter';

				if ($sTemp = @$aConfigFromFile[self::TRACKER_CONFIG_KEY_NAMESPACE]) {
					$bDefNameS  = false;
					$sNamespace = $sTemp;
				} // if

				$sNameSep = strpos($sNamespace, '\\') !== false ? '\\' : '_';

				if (is_readable($sAdapterDir = $sVendorDir . $sSep . $sStandard . $sSep))
				{
					$oIt = new RegexIterator(
						new RecursiveIteratorIterator(
							new RecursiveDirectoryIterator($sAdapterDir)
						),
						'/^.+(\.' . $sFileExt . ')$/i',
						RecursiveRegexIterator::GET_MATCH
					);

					foreach ($oIt as $aFile) {
						$sFile      = reset($aFile);
						$sClass     = str_replace('.' . $sFileExt, '', str_replace($sAdapterDir, '', $sFile));

						$aConfig[self::TRACKER_CONFIG_KEY_FILES][strtolower(str_replace($sSep, '_', $sClass))] =
							$sNamespace . $sNameSep . $sVendor .  $sNameSep . $sStandard . $sNameSep .
								str_replace($sSep, $sNameSep, $sClass);
					} // foreach
				} // if

				if (is_readable($sVendorDir . $sSep . $sStandard . ".{$sFileExt}")) {
					$aConfig[self::TRACKER_CONFIG_KEY_STANDARD_ADAPTER] =
						$sNamespace . $sNameSep . $sVendor . $sNameSep . $sStandard;
				} // if

				// No constant because it is the default name of the oxid.
				$aConfig['settings'] = ($aConfigFromFile['settings'] && is_array($aConfigFromFile['settings']))
					? $aConfigFromFile['settings']
					: array();

@				$aConfig['alias'] = (string) $aConfigFromFile[self::TRACKER_CONFIG_KEY_ID_ALIAS];
			} // if

			return $aConfig;
		} // function

		/**
		 * Loads the tracker configs from the met afiles.
		 * @author blange <code@wbl-konzept.de>
		 * @return array
		 * @todo   Caching.
		 */
		protected function loadTrackerConfigs() {
			if (!$aPaths = $this->getBasePaths()) {
				return array();
			} // if

			if (!is_null($mConfigs = $this->getCachedTrackerConfigs($aPaths))) {
				return $mConfigs;
			} // if

			$aConfigs  = array();
			$sSep      = DIRECTORY_SEPARATOR;
			$sMetaFile = $this->getMetaFileName();

			/*
			 * The array reverese allows to add adapter files and configs from a more specialized
			 * module to the "original" module, ...
			 */
			foreach (array_reverse($aPaths) as $sPath) {
				if (!$aFiles = glob($sPath . "*{$sSep}{$sMetaFile}")) {
					continue;
				} // if

				foreach ($aFiles as $sFile) {
					if (!array_key_exists($sVendor = basename(dirname($sFile)), $aConfigs)) {
						$aConfigs[$sVendor] = array();
					} // if

					/*
					 *  ... but the module files of the original module
					 * override anything with the same key. If you want to overload a tracker adapter use
					 * the "oxNew" chain instead.
					 */
					$aConfigs[$sVendor] =
						array_merge_recursive(
							$aConfigs[$sVendor],
							$this->loadTrackerConfigForVendor($aConfigs, $sFile, $sVendor)
						);
				} // foreach
			} // foreach

			$this->cacheTrackerConfigs($aConfigs, $aPaths);

			return $aConfigs;
		} // function

		/**
		 * Sets the instance.
		 * @author blange <code@wbl-konzept.de>
		 * @param  WBL_Tracker_Adapter_Loader $oInst The new instance.
		 * @return WBL_Tracker_Adapter_Loader The used instance.
		 */
		static public function setInstance(WBL_Tracker_Adapter_Loader $oInst)
		{
			if (self::$oSelf && self::$oSelf !== $oInst)
			{
				$oInst
					->setMetaFileName(self::$oSelf->getMetaFileName())
					->setTrackerConfigs(self::$oSelf->getTrackerConfigs());

				foreach (self::$oSelf->getBasePaths() as $sPath) {
					$oInst->addBasePath($sPath);
				} // foreach
			} // if

			return self::$oSelf = $oInst;
		} // function

		/**
		 * Sets the file name for the meta files.
		 * @author blange <code@wbl-konzept.de>
		 * @param string $sName
		 * @return WBL_Tracker_Adapter_Loader
		 */
		public function setMetaFileName($sName) {
			$this->sMetaFileName = $sName;

			return $this;
		} // function

		/**
		 * Sets the used tracker configs.
		 * @author blange <code@wbl-konzept.de>
		 * @param array $aConfigs The config.
		 * @return WBL_Tracker_Adapter_Loader
		 */
		public function setTrackerConfigs(array $aConfigs) {
			$this->aTrackerConfigs = $aConfigs;

			return $this;
		} // function

		/**
		 * Loescht die Instanz.
		 * @return void
		 * @author blange <code@wbl-konzept.de>
		 */
		static public function unsetInstance()
		{
			self::$oSelf = null;
		} // function
	} // class