<?php
	/**
	 * ./unit/modules/WBL/Tracker/Conditions/HelperTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions
	 * @version $id$
	 */

	$sWblTrackerConditionsHelperTestFile = dirname(__FILE__) . DIRECTORY_SEPARATOR;
	require_once realpath($sWblTrackerConditionsHelperTestFile . '../../TestCase.php');
	require_once realpath($sWblTrackerConditionsHelperTestFile . '/Callback/Dummy.php');

	/**
	 * Testing of WBL_Tracker_Conditions_Helper.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Conditions
	 * @subpackage Piwik
	 * @version $id$
	 */
	class WBL_Tracker_Conditions_HelperTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Conditions_Helper
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

			$this->oFixture = new WBL_Tracker_Conditions_Helper();
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
		 * Checks the default return of the method.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testCheckConditionDefault() {
			$this->assertTrue($this->oFixture->checkCondition());
		} // function

		/**
		 * Checks if the method returns true if the parameter is filled.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testCheckConditionNoCallbackMatchWithoutView() {
			modConfig::setParameter($sField = uniqid(), uniqid());

			$this->assertTrue($this->oFixture->checkCondition('', '', $sField));
		} // function

		/**
		 * Checks if the method returns true if the parameter is filled.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testCheckConditionNoCallbackMatchWithView() {
			$this->oFixture->setView($oView = new Start());
			$oView->setClassName('Start');
			unset($oView);

			modConfig::setParameter($sField = uniqid(), uniqid());

			$this->assertTrue($this->oFixture->checkCondition('', 'start', $sField));
		} // function

		/**
		 * Checks if the method returns false if the function does not allow the value.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testCheckConditionNoCallbackNoMatch() {
			$this->oFixture->setView($oView = new Start());
			$oView->setClassName('Start');
			unset($oView);

			$this->assertFalse($this->oFixture->checkCondition('trim', 'start'));
		} // function

		/**
		 * Checks if the method returns false if the value check is needed but no value given.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testCheckConditionNoValueMatch() {
			$this->oFixture->setView($oView = new Start());
			$oView->setClassName('Start');
			unset($oView);

			$this->assertFalse($this->oFixture->checkCondition(uniqid(), 'start'));
		} // function

		/**
		 * Checks if the method returns false if the view check fails.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testCheckConditionNoViewMatch() {
			$this->oFixture->setView(new Start());

			$this->assertFalse($this->oFixture->checkCondition(uniqid(), uniqid(), 'field'));
		} // function

		/**
		 * Checks if the method returns true if the value and view check is successfull.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testCheckConditionValueAndViewMatch() {
			$this->oFixture->setView($oView = new Start());
			$oView->setClassName('Start');
			unset($oView);

			modConfig::setParameter($sField = uniqid(), $sValue = uniqid());

			$this->assertTrue($this->oFixture->checkCondition($sValue, 'start', $sField));
		} // function

		/**
		 * Checks if the method returns true if the function allows the value.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testCheckConditionValueCallbackCheck() {
			$this->oFixture->setView($oView = new Start());
			$oView->setClassName('Start');
			unset($oView);

			modConfig::setParameter($sField = uniqid(), $sValue = uniqid());

			$this->assertTrue($this->oFixture->checkCondition('trim', 'start', $sField));
		} // function

		/**
		 * Checks if the method returns true if the function allows the value.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testCheckConditionValueCallbackCheckNoView() {
			modConfig::setParameter($sField = uniqid(), $sValue = uniqid());

			$this->oFixture->checkCondition('trim', $sField);
		} // function

		/**
		 * Checks if the callback is called for checking.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testCheckConditionWithCallbackClass() {
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('getCallback'));

			$this->oFixture
				->expects($this->once())
				->method('getCallback')
				->with($sName = uniqid(), $sParam = uniqid())
				->will($this->returnValue(
					$oCall = $this->getMock('WBL_Tracker_Conditions_Check_Interface')
				));

			$oCall
				->expects($this->once())
				->method('match')
				->will($this->returnValue(true));
			unset($oCall);

			$this->assertTrue($this->oFixture->checkCondition($sName, '', $sParam), 'Callback not checked');
		} // function

		/**
		 * Checks the constants.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testConstants() {
			$this->assertSame('|', WBL_Tracker_Conditions_Helper::DEFAULT_SEPARATOR);
		} // function

		/**
		 * Checks the default return of the method.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetCallbackDefault() {
			$this->assertNull($this->oFixture->getCallback(uniqid()));
		} // function

		/**
		 * Checks if an object is returned.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetCallbackSuccess() {
			$this->oFixture
				->setAdapter($oAdapter = $this->getMock('WBL_Tracker_Adapter_Interface'))
				->setView($oView = new Start());

			modConfig::setParameter($sField = uniqid(), $sValue = uniqid());

			$this->assertType(
				'WBL_Tracker_Conditions_Callback_Dummy',
				$oCall = $this->oFixture->getCallback(
					'WBL_Tracker_Conditions_Callback_Dummy',
					$sField
				)
			);

			$this->assertSame($oAdapter, $oCall->getAdapter());
			$this->assertSame($oView, $oCall->getView());
			$this->assertSame($sField, $oCall->getParameterName(), 'Name failed.');
			$this->assertSame($sValue, $oCall->getParameterValue(), 'value failed.');
			unset($oAdapter, $oCall, $oView);
		} // function
	} // class