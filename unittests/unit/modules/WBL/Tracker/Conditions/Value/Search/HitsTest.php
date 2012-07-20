<?php
	/**
	 * ./unit/modules/WBL/Tracker/Conditions/Value/Search/HitsTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version $id$
	 */

		require_once realpath(dirname(__FILE__) . '/../../../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Conditions_Value_Search_Hits.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Conditions_Value
	 * @version $id$
	 */
	class WBL_Tracker_Conditions_Value_Search_HitsTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Conditions_Value_Search_Hits
		 */
		protected $oFixture = null;

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = new WBL_Tracker_Conditions_Value_Search_Hits();
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
		 * Is an empty string returned?
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueEmtpy() {
			$this->oFixture->setView(new Search());

			modConfig::setParameter('searchparam', '');

			$this->assertSame(0, $this->oFixture->getValue());
		} // function

		/**
		 * Is the search parameter returned.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueFull() {
			$this->oFixture->setView($oMock = $this->getMock('Search', array('getArticleCount')));

			$oMock
				->expects($this->once())
				->method('getArticleCount')
				->will($this->returnValue($iCount = mt_rand(1, 999)));

			$this->assertSame($iCount, $this->oFixture->getValue());
		} // function

		/**
		 * Is an empty string returned?
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testGetValueNoView() {
			$this->assertSame(0, $this->oFixture->getValue());
		} // function

		/**
		 * Checks the type.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testType() {
			$this->assertType('WBL_Tracker_Conditions_Interface', $this->oFixture);
			$this->assertType('WBL_Tracker_Conditions_Value_Interface', $this->oFixture);
		} // function
	} // class