<?php
	/**
	 * ./unit/modules/WBL/Tracker/OutputTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage oxOutput
	 * @version $id$
	 */

	require_once realpath(dirname(__FILE__) . '/../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Output.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage oxOutput
	 * @version $id$
	 */
	class WBL_Tracker_OutputTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Output
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/modules/WBL/WBL_TestCase::getGetterAndGetterRules()
		 */
		public function getGetterAndGetterRules() {
			return array(
				array(
					'getViewData',
					'processViewArray',
					array(),
					array($aReturn = array('name' => uniqid()), uniqid()),
					$aReturn,
					null,
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

			$this->oFixture = $this->getOXIDModuleForWBL('oxoutput', 'WBL_Tracker_Output');
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
		 * There should be no action in the admin area.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessAdminSkip() {
			oxConfig::getInstance()->setAdminMode(true);

			$this->assertEquals(
				$sHTML = uniqid(),
				$this->oFixture->process($sHTML, $sClass = uniqid())
			);
		} // function

		/**
		 * The tracker codes should be added to the bottom of the valid html.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessBottomAddText() {
			oxConfig::getInstance()->setAdminMode(false);
			WBL_Tracker_Adapter_Loader::setInstance(
				$oMock = $this->getMock('WBL_Tracker_Adapter_Loader', array('getAdaptersForClass'))
			);

			$this->oFixture = $this->getMock(get_class($this->oFixture), array('processWBLAdapters'));

			$oMock
				->expects($this->once())
				->method('getAdaptersForClass')
				->with($sClass = uniqid())
				->will($this->returnValue($aAdapters = array($this->getMock('WBL_Tracker_Adapter_Interface'))));

			$this->oFixture
				->expects($this->once())
				->method('processWBLAdapters')
				->with($aAdapters, $sClass)
				->will($this->returnValue($sTracker = uniqid()));

			$this->assertEquals(
				($sText = uniqid()) . $sTracker,
				$this->oFixture->process($sText, $sClass)
			);
			unset($oMock);
		} // function

		/**
		 * The tracker codes should be added to the bottom of the valid html.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessBottomAddValidHTML() {
			oxConfig::getInstance()->setAdminMode(false);
			WBL_Tracker_Adapter_Loader::setInstance(
				$oMock = $this->getMock('WBL_Tracker_Adapter_Loader', array('getAdaptersForClass'))
			);

			$this->oFixture = $this->getMock(get_class($this->oFixture), array('processWBLAdapters'));

			$oMock
				->expects($this->once())
				->method('getAdaptersForClass')
				->with($sClass = uniqid())
				->will($this->returnValue($aAdapters = array($this->getMock('WBL_Tracker_Adapter_Interface'))));

			$this->oFixture
				->expects($this->once())
				->method('processWBLAdapters')
				->with($aAdapters, $sClass)
				->will($this->returnValue($sTracker = uniqid()));

			$this->assertEquals(
				"test{$sTracker}</body>",
				$this->oFixture->process('test</body>', $sClass)
			);
			unset($oMock);
		} // function

		/**
		 * There should be no action if there are no adapters.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessNoAdapterSkip() {
			oxConfig::getInstance()->setAdminMode(false);
			WBL_Tracker_Adapter_Loader::setInstance(
				$oMock = $this->getMock('WBL_Tracker_Adapter_Loader', array('getAdaptersForClass'))
			);

			$oMock
				->expects($this->once())
				->method('getAdaptersForClass')
				->with($sClass = uniqid())
				->will($this->returnValue(array()));

			$this->assertEquals(
				$sHTML = uniqid(),
				$this->oFixture->process($sHTML, $sClass)
			);
			unset($oMock);
		} // function

		/**
		 * Checks if the HTML-Getter is called.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testProcessWBLAdapters() {
			$this->oFixture = $this->getProxyClass(get_class($this->oFixture));
			$oMock = $this->getMock('WBL_Tracker_Adapter_Interface');
			oxConfig::getInstance()->setActiveView($oViewMock = $this->getMock('oxubase'));
			modInstances::addMod('oxUtilsView', $oUtilsViewMock = $this->getMock('oxutilsview'));

			$this->oFixture->processViewArray($aViewData = array(uniqid()), $sClass = uniqid());

			$oMock
				->expects($this->once())
				->method('getHTML')
				->will($this->returnValue($sReturn = uniqid()));

			$oMock
				->expects($this->once())
				->method('init')
				->will($this->returnValue($oMock));

			$oMock
				->expects($this->once())
				->method('setSmarty')
				->with($oSmarty = $this->getMock('Smarty'))
				->will($this->returnValue($oMock));

			$oMock
				->expects($this->once())
				->method('setView')
				->with($oViewMock)
				->will($this->returnValue($oMock));

			$oMock
				->expects($this->once())
				->method('setViewData')
				->with($aViewData)
				->will($this->returnValue($oMock));

			$oUtilsViewMock
				->expects($this->once())
				->method('getSmarty')
				->will($this->returnValue($oSmarty));

			$this->assertEquals($sReturn, $this->oFixture->processWBLAdapters(array($oMock), $sClass));
			unset($oMock, $oSmarty, $oUtilsViewMock, $oViewMock);
		} // function

		/**
		 * Checks if the module is loaded.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testType() {
			$this->assertType('WBL_Tracker_Output', $oClass = oxNew('oxoutput'));
			$this->assertType('WBL_Tracker_Output_parent', $oClass);
			unset($oClass);
		} // function
	} // class