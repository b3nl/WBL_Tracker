<?php
	/**
	 * ./unit/modules/WBL/Tracker/Basket/ItemTest.php
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Basket
	 * @version $id$
	 */

		require_once realpath(dirname(__FILE__) . '/../../TestCase.php');

	/**
	 * Testing of WBL_Tracker_Basket_Item.
	 * @author blange <b.lange@wbl-konzept.de>
	 * @category unittests
	 * @package WBL_Tracker
	 * @subpackage Basket
	 * @version $id$
	 */
	class WBL_Tracker_Basket_ItemTest extends WBL_TestCase {
		/**
		 * The fixture.
		 * @var WBL_Tracker_Basket_Item
		 */
		protected $oFixture = null;

		public function getGetterAndGetterRules() {
			return array(
				array(
					'getUsedWBLCatId',
					'setUsedWBLCatId',
					'',
					array($sReturn = uniqid()),
					$sReturn
				)
			);
		} // function

		/**
		 * (non-PHPdoc)
		 * @see unittests/unit/OxidTestCase::setUp()
		 */
		public function setUp() {
			parent::setUp();

			$this->oFixture = $this->getOXIDModuleForWBL('oxbasketitem', 'WBL_Tracker_Basket_Item');
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
		 * @author blange <b.lange@wbl-konzept.de>
		 * @return void
		 */
		public function testGetUsedWBLCategoryDefault() {
			$this->assertNull($this->oFixture->getUsedWBLCategory());
		} // function

		/**
		 * Checks the full method call.
		 * @author blange <b.lange@wbl-konzept.de>
		 * @return void
		 */
		public function testGetUsedWBLCategoryFull() {
			oxTestModules::addModuleObject('oxcategory', $oCat = $this->getMock('oxcategory', array('load')));
			$this->oFixture->setUsedWBLCatId($sCatId = uniqid());

			$oCat
				->expects($this->once())
				->method('load')
				->with($sCatId)
				->will($this->returnValue(true));

			$this->assertSame($oCat, $oCat2 = $this->oFixture->getUsedWBLCategory(), 'Category object was not loaded.');
			$this->assertSame($oCat2, $this->oFixture->getUsedWBLCategory(), 'Class-Caching failed.');
			unset($oCat, $oCat2);
		} // function

		/**
		 * Checks one way of the method.
		 * @author blange <b.lange@wbl-konzept.de>
		 * @expectedException Exception
		 * @return void
		 */
		public function testInitActCategory() {
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('_setArticle'));
			oxConfig::getInstance()->setActiveView($oView = $this->getMock('oxubase'));

			$this->oFixture
				->expects($this->once())
				->method('_setArticle')
				->with($sArticleId = uniqid())
				->will($this->throwException(new Exception()));

			$oView
				->expects($this->once())
				->method('getActCategory')
				->will($this->returnValue($oCat = oxNew('oxcategory')));

			$oView
				->expects($this->never())
				->method('getActiveCategory');

			$oView
				->expects($this->never())
				->method('getCategoryId');

			$oCat->setId($sCatId = uniqid());

			unset($oCat, $oView);
			$this->oFixture->init($sArticleId, 1);
			$this->assertSame($sCatId, $this->oFixture->getUsedWBLCatId());
		} // function

		/**
		 * Checks one way of the method.
		 * @author blange <b.lange@wbl-konzept.de>
		 * @expectedException Exception
		 * @return void
		 */
		public function testInitActiveCategory() {
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('_setArticle'));
			oxConfig::getInstance()->setActiveView($oView = $this->getMock('oxubase'));

			$this->oFixture
				->expects($this->once())
				->method('_setArticle')
				->with($sArticleId = uniqid())
				->will($this->throwException(new Exception()));

			$oView
				->expects($this->once())
				->method('getActCategory');

			$oView
				->expects($this->once())
				->method('getActiveCategory')
				->will($this->returnValue($oCat = oxNew('oxcategory')));

			$oView
				->expects($this->never())
				->method('getCategoryId');

			$oCat->setId($sCatId = uniqid());

			unset($oCat, $oView);
			$this->oFixture->init($sArticleId, 1);
			$this->assertSame($sCatId, $this->oFixture->getUsedWBLCatId());
		} // function

		/**
		 * Checks one way of the method.
		 * @author blange <b.lange@wbl-konzept.de>
		 * @expectedException Exception
		 * @return void
		 */
		public function testInitCategoryId() {
			$this->oFixture = $this->getMock(get_class($this->oFixture), array('_setArticle'));
			oxConfig::getInstance()->setActiveView($oView = $this->getMock('oxubase'));

			$this->oFixture
				->expects($this->once())
				->method('_setArticle')
				->with($sArticleId = uniqid())
				->will($this->throwException(new Exception()));

			$oView
				->expects($this->once())
				->method('getActCategory');

			$oView
				->expects($this->once())
				->method('getActiveCategory');

			$oView
				->expects($this->once())
				->method('getCategoryId')
				->will($this->returnValue($sCatId = uniqid()));

			unset($oView);
			$this->oFixture->init($sArticleId, 1);
			$this->assertSame($sCatId, $this->oFixture->getUsedWBLCatId());
		} // function

		/**
		 * Checks if the object is deleted while serializing.
		 * @author blange <b.lange@wbl-konzept.de>
		 * @return void
		 */
		public function testSleep() {
			$this->oFixture->setUsedWBLCatId($sCatId = uniqid());

			$oClass = unserialize(serialize($this->oFixture));
			$this->assertSame($sCatId, $oClass->getUsedWBLCatId());
			unset($oClass);
		} // function

		/**
		 * Checks if the module is loaded.
		 * @author blange <code@wbl-konzept.de>
		 * @return void
		 */
		public function testType() {
			$this->assertType('WBL_Tracker_Basket_Item', $oClass = oxNew('oxbasketitem'));
			$this->assertType('WBL_Tracker_Basket_Item_parent', $oClass);
			unset($oClass);
		} // function
	} // class