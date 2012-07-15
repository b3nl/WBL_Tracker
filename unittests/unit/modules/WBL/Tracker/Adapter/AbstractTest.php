<?php
	/**
	 * ./unit/modules/WBL/Tracker/Adapter/AbstractTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Adapter
	 * @version $id$
	 */

	require_once realpath(dirname(__FILE__) . '/../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Adapter_Abstract.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Adapter
	 * @version $id$
	 */
	class WBL_Tracker_Adapter_AbstractTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Adapter_Abstract
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/modules/WBL/WBL_TestCase::getGetterAndGetterRules()
		 */
		public function getGetterAndGetterRules() {
			return array(
				array(
					'getSmarty',
					'setSmarty',
					null,
					array($oMock = $this->getMock('Smarty')),
					$oMock
				),
				array(
					'getView',
					'setView',
					null,
					array($oMock = $this->getMock('oxUbase')),
					$oMock
				),
				array(
					'getViewData',
					'setViewData',
					array(),
					array($aReturn = array('name' => uniqid())),
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

			$this->oFixture = $this->getMockForAbstractClass('WBL_Tracker_Adapter_Abstract');
		} // function

		/**
		 * (non-PHPdoc)
		 * @see oxid_additionals/unittests/unit/OxidTestCase::tearDown()
		 */
		public function tearDown() {
			parent::tearDown();

			$this->oFixture = null;
		} // function

		/**
		 * Checks the fluent interface.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testInitFluent() {
			$this->assertSame($this->oFixture, $this->oFixture->init());
		} // function

		/**
		 * Checks the default return.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function isForClassDefault() {
			$this->assertTrue($this->oFixture->isForClass(uniqid()));
		} // function

		/**
		 * Testing of the control method.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testIsXHTML() {
			$this->assertTrue($this->oFixture->isXHTML(), '1. check failed.');
			$this->assertTrue($this->oFixture->isXHTML(false), '2. check failed.');
			$this->assertFalse($this->oFixture->isXHTML(), '3. check failed.');
			$this->assertFalse($this->oFixture->isXHTML(true), '4. check failed.');
			$this->assertTrue($this->oFixture->isXHTML(), '5. check failed.');
		} // function
	} // class