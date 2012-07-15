<?php
	/**
	 * ./unit/modules/WBL/Tracker/OutputTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Adapter
	 * @version $id$
	 */

	$sWblTrackerAdapterLoaderTestDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
	require_once realpath($sWblTrackerAdapterLoaderTestDir . '../../TestCase.php');
	require realpath($sWblTrackerAdapterLoaderTestDir . 'Test1/Test/Adapter/Details.php');
	require realpath($sWblTrackerAdapterLoaderTestDir . 'Test1/Test/Adapter/Sub/Details.php');
	require realpath($sWblTrackerAdapterLoaderTestDir . 'Test1/Test/Adapter.php');
	unset($sWblTrackerAdapterLoaderTestDir);

	/**
	 * Testing of WBL_Tracker_Adapter_Loader.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Adapter
	 * @version $id$
	 */
	class WBL_Tracker_Adapter_LoaderTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Adapter_Loader
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/modules/WBL/WBL_TestCase::getGetterAndGetterRules()
		 */
		public function getGetterAndGetterRules() {
			return array(
				array(
					'getBasePaths',
					'addBasePath',
					array(),
					array($sReturn = dirname(__FILE__)),
					array($sReturn . DIRECTORY_SEPARATOR)
				),
				array(
					'getMetaFileName',
					'setMetaFileName',
					WBL_Tracker_Adapter_Loader::DEFAULT_META_FILE,
					array($sReturn = uniqid()),
					$sReturn
				),
				array(
					'getTrackerConfigs',
					'setTrackerConfigs',
					array(),
					array($aReturn = array(uniqid())),
					$aReturn
				)
			);
		} // function

		/**
		 * (non-PHPdoc)
		 * @see oxid_additionals/unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = $this->getMock(
				'WBL_Tracker_Adapter_Loader', array('_dummyMethod'), array(), '', false
			);
		} // function

		/**
		 * (non-PHPdoc)
		 * @see oxid_additionals/unittests/unit/OxidTestCase::tearDown()
		 */
		public function tearDown() {
			$this->oFixture = null;
			WBL_Tracker_Adapter_Loader::unsetInstance();

			parent::tearDown();
		} // function

		/**
		 * Checks the declared constants.
		 * @return void
		 */
		public function testConstants() {
			$this->assertEquals('aWBLTrackerConfigsArray', WBL_Tracker_Adapter_Loader::CACHE_KEY_PREFIX);
			$this->assertEquals('bWBLTrackerIsXMLValid',   WBL_Tracker_Adapter_Loader::CONFIG_KEY_IS_XML);
			$this->assertEquals('Adapter',          WBL_Tracker_Adapter_Loader::DEFAULT_ADAPTER);
			$this->assertEquals('php',              WBL_Tracker_Adapter_Loader::DEFAULT_FILE_ENDING);
			$this->assertEquals('trackerdata.php',  WBL_Tracker_Adapter_Loader::DEFAULT_META_FILE);
			$this->assertEquals('WBL_Tracker',      WBL_Tracker_Adapter_Loader::DEFAULT_NAMESPACE);
			$this->assertEquals('file_extension',   WBL_Tracker_Adapter_Loader::TRACKER_CONFIG_KEY_FILE_EXTENSION);
			$this->assertEquals('files',            WBL_Tracker_Adapter_Loader::TRACKER_CONFIG_KEY_FILES);
			$this->assertEquals('namespace',        WBL_Tracker_Adapter_Loader::TRACKER_CONFIG_KEY_NAMESPACE);
			$this->assertEquals('standard_adapter', WBL_Tracker_Adapter_Loader::TRACKER_CONFIG_KEY_STANDARD_ADAPTER);
		} // function

		/**
		 * Checks if the return is empty when the matching tacker is not allowed.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetAdaptersForClassSuccessActiveButNotAllowed() {
			modConfig::getInstance()->setConfigParam('bIsWBLTrackerTestActive', true);

			$this->oFixture->setTrackerConfigs(array('Test' => array(
				'files' => array('details' => 'WBL_Tracker_Test1_Test_Adapter_Details')
			)));
			$this->assertEquals(array(), $this->oFixture->getAdaptersForClass('details'));
			unset($aAdapters);
		} // function

		/**
		 * Checks the default return.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetAdaptersForClassDefault() {
			$this->assertEquals(array(), $this->oFixture->getAdaptersForClass(uniqid()));
		} // function

		/**
		 * Checks if the return matches the standard adapter.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetAdaptersForClassStandard() {
			modConfig::getInstance()->setConfigParam('bIsWBLTrackerTestActive', true);

			$this->oFixture->setTrackerConfigs(array('Test' => array(
				'standard_adapter' => 'WBL_Tracker_Test1_Test_Adapter'
			)));
			$aAdapters = $this->oFixture->getAdaptersForClass('sub_details');
			$this->assertType(
				'WBL_Tracker_Test1_Test_Adapter',
				reset($aAdapters)
			);
			unset($aAdapters);
		} // function

		/**
		 * Checks if the return matches the requested class.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetAdaptersForClassSuccess() {
			$oConfig = modConfig::getInstance();
			$oConfig->setConfigParam('bWBLTrackerIsXMLValid', true);
			$oConfig->setConfigParam('bIsWBLTrackerTestActive', true);
			unset($oConfig);

			$this->oFixture->setTrackerConfigs(array('Test' => array(
				'files' => array('sub_details' => 'WBL_Tracker_Test1_Test_Adapter_Sub_Details')
			)));
			$aAdapters = $this->oFixture->getAdaptersForClass('sub_details');
			$this->assertType(
				'WBL_Tracker_Test1_Test_Adapter_Sub_Details',
				$oAdapter = reset($aAdapters),
				'Adapter was not returned correctly.'
			);
			unset($aAdapters);

			$this->assertTrue($oAdapter->isXHTML(), 'XML Marker was not set.');
		} // function

		/**
		 * Checks if the return is empty when the matching tacker is not active.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetAdaptersForClassSuccessNotActive() {
			modConfig::getInstance()->setConfigParam('bIsWBLTrackerTestActive', false);

			$this->oFixture->setTrackerConfigs(array('Test' => array(
				'files' => array('sub_details' => 'WBL_Tracker_Test1_Test_Adapter_Sub_Details')
			)));
			$this->assertEquals(array(), $this->oFixture->getAdaptersForClass('sub_details'));
			unset($aAdapters);
		} // function

		/**
		 * Checks the default return of the getter.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetExtendArrayForMetaDataDefault() {
			$this->assertSame(array(), $this->oFixture->getExtendArrayForMetaData());
		} // function

		/**
		 * Checks the filled return of the getter.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetExtendArrayForMetaDataFull() {
			$this->oFixture = $this->getProxyClass(get_class($this->oFixture));

			$this->oFixture->setTrackerConfigs(array('Test' =>
				$this->oFixture->loadTrackerConfigForVendor(
					array(),
					dirname(__FILE__) . '/Test1/Test/trackerdata.php',
					'Test'
				)
			));

			$this->assertEquals(
				array(
					array('group' => 'WBL_Tracker_Test', 'name' => 'bIsWBLTrackerTestActive', 'type' => 'bool',  'value' => false),
					array('group' => 'WBL_Tracker_Test', 'name' => 'sWBLTrackerID',           'type' => 'str')
				),
				$this->oFixture->getExtendArrayForMetaData()
			);
		} // function

		/**
		 * Checks the Singleton getter.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetInstance() {
			$this->assertType('WBL_Tracker_Adapter_Loader', $oInst = WBL_Tracker_Adapter_Loader::getInstance());
			$this->assertSame($oInst, WBL_Tracker_Adapter_Loader::getInstance(), 'Lazy load check failed.');
			unset($oInst);
		} // function

		/**
		 * Checks if the array filled correctly.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testLoadTrackerConfigForVendor() {
			$this->oFixture = $this->getProxyClass(get_class($this->oFixture));
			// TODO Nicer array check
			$this->assertSame(
				array(
					'files'            => array(
						'sub_details'  => 'WBL_Tracker_Test1_Test_Adapter_Sub_Details',
						'details'      => 'WBL_Tracker_Test1_Test_Adapter_Details'
					),
					'standard_adapter' => 'WBL_Tracker_Test1_Test_Adapter',
					'settings'         => array(array('name' => 'sWBLTrackerID', 'type' => 'str'))
				),
				$this->oFixture->loadTrackerConfigForVendor(
					array(),
					dirname(__FILE__) . '/Test1/Test/trackerdata.php',
					'Test'
				)
			);
		} // function

		/**
		 * Checks if an empty is returned as default.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testLoadTrackerConfigForVendorDefault() {
			$this->oFixture = $this->getProxyClass(get_class($this->oFixture));

			$this->assertSame(
				array(),
				$this->oFixture->loadTrackerConfigForVendor(
					array(),
					dirname(__FILE__) . '/Test1/Test/' . uniqid(),
					'Test'
				)
			);
		} // function

		/**
		 * Checks the call for loading the config from the cache.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testLoadTrackerConfigsFromCache() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('getBasePaths', 'getCachedTrackerConfigs'),
				array(),
				'',
				false
			);

			$this->oFixture
				->expects($this->once())
				->method('getBasePaths')
				->will($this->returnValue($aPaths = array(uniqid())));

			$this->oFixture
				->expects($this->once())
				->method('getCachedTrackerConfigs')
				->with($aPaths)
				->will($this->returnValue($aReturn = array(uniqid())));

			$this->assertSame(
				$aReturn,
				$this->oFixture->loadTrackerConfigs(),
				'The cached config was not returned.'
			);
		} // function

		/**
		 * Checks the calls for loading the configs.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testLoadTrackerConfigs() {
			$this->oFixture = $this->getMock(
				get_class($this->getProxyClass(get_class($this->oFixture))),
				array('loadTrackerConfigForVendor'),
				array(),
				'',
				false
			);

			$this->oFixture
				->addBasePath($sDir1 = dirname(__FILE__) . '/Test1')
				->addBasePath($sDir2 = dirname(__FILE__) . '/Test2')
				->addBasePath(dirname(__FILE__) . '/Test3');
			$sSep = DIRECTORY_SEPARATOR;

			$this->oFixture
				->expects($this->at(0))
				->method('loadTrackerConfigForVendor')
				->with(array('Test' => array()), $sDir2 . "{$sSep}Test{$sSep}trackerdata.php", 'Test')
				->will($this->returnValue($aFirst = array($sFirst = uniqid(), 'name' => 'value')));

			$this->oFixture
				->expects($this->at(1))
				->method('loadTrackerConfigForVendor')
				->with(array('Test' => $aFirst), $sDir1 . "{$sSep}Test{$sSep}trackerdata.php", 'Test')
				->will($this->returnValue(array($sSecond = uniqid())));

			$this->assertSame(
				array('Test' => array($sFirst, 'name' => 'value', $sSecond)),
				$this->oFixture->loadTrackerConfigs()
			);
		} // function

		/**
		 * checks if the instance can be changed.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testSetInstance() {
			$this->oFixture = WBL_Tracker_Adapter_Loader::getInstance();
			$oMock          = $this->getMock(
				'WBL_Tracker_Adapter_Loader',
				array('addBasePath', 'setMetaFileName', 'setTrackerConfigs'),
				array(),
				'',
				false
			);

			$this->oFixture
				->addBasePath($sDir = dirname(__FILE__))
				->setMetaFileName($sFile = uniqid())
				->setTrackerConfigs($aConfigs = array(uniqid()));

			$oMock
				->expects($this->atLeastOnce())
				->method('addBasePath');

			$oMock
				->expects($this->once())
				->method('setMetaFileName')
				->with($sFile)
				->will($this->returnValue($oMock));

			$oMock
				->expects($this->once())
				->method('setTrackerConfigs')
				->with($aConfigs);

			$this->assertSame($oMock, WBL_Tracker_Adapter_Loader::setInstance($oMock), 'Fluent interface does not work.');
			$this->assertNotSame($this->oFixture, WBL_Tracker_Adapter_Loader::getInstance(), 'Instance could not be changed..');
			$this->assertSame($oMock, WBL_Tracker_Adapter_Loader::getInstance(), 'Instance is not changed correctly.');

			unset($oMock);
		} // function

		/**
		 * Can the class delete the instance?
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testUnsetInstance() {
			$this->assertType('WBL_Tracker_Adapter_Loader', $oInst = WBL_Tracker_Adapter_Loader::getInstance());
			WBL_Tracker_Adapter_Loader::unsetInstance();
			$this->assertNotSame(
				$oInst, WBL_Tracker_Adapter_Loader::getInstance(), 'Lazy load check failed.'
			);
			unset($oInst);
		} // function
	} // class