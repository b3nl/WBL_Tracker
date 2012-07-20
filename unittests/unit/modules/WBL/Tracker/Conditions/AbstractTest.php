<?php
	/**
	 * ./unit/modules/WBL/Tracker/Conditions/AbstractTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions
	 * @version $id$
	 */

		require_once realpath(dirname(__FILE__) . '/../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Conditions_Abstract.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions
	 * @version $id$
	 */
	class WBL_Tracker_Conditions_AbstractTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Conditions_Abstract
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/modules/WBL/WBL_TestCase::getGetterAndGetterRules()
		 */
		public function getGetterAndGetterRules() {
			return array(
				array(
					'getAdapter',
					'setAdapter',
					null,
					array($oReturn = $this->getMock('WBL_Tracker_Adapter_Interface')),
					$oReturn
				),
				array(
					'getParameterName',
					'setParameterName',
					'',
					array($sReturn = uniqid()),
					$sReturn
				),
				array(
					'getParameterValue',
					'setParameterValue',
					null,
					array($sReturn = uniqid()),
					$sReturn
				),
				array(
					'getView',
					'setView',
					null,
					array($oReturn = $this->getMock('oxUBase')),
					$oReturn
				)
			);
		} // function

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = $this->getMock('WBL_Tracker_Conditions_Abstract', array('_dummyMethod'));
		} // function

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::tearDown()
		 */
		public function tearDown() {
			$this->oFixture = null;

			parent::tearDown();
		} // function

		/**
		 * Checks the type.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testType() {
			$this->assertType('WBL_Tracker_Conditions_Interface', $this->oFixture);
		} // function
	} // class