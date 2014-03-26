<?php
    /**
     * ./modules/WBL/Tracker/Output.php
     * @author     Bjoern Simon Lange <code@wbl-konzept.de>
     * @category   modules
     * @package    WBL_Tracker
     * @subpackage oxOutput
     * @version    SVN: $Id$
     */

    /**
     * Extension for oxOutput.
     * @author     Bjoern Simon Lange <code@wbl-konzept.de>
     * @category   modules
     * @package    WBL_Tracker
     * @subpackage oxOutput
     * @todo       Testing of oxshopcontrol and oxemail
     * @version    SVN: $Id$
     */
    class WBL_Tracker_Output extends WBL_Tracker_Output_parent
    {
        /**
         * The view data of the oxid process.
         * @var array
         */
        private $_aViewDataForTracker = array();

        /**
         * Returns the used view data.
         * @author blange <code@wbl-konzept.de>
         * @return array
         */
        protected function getViewData()
        {
            return $this->_aViewDataForTracker;
        } // function

        /**
         * (non-PHPdoc)
         * @see http/core/oxOutput::process()
         */
        public function process($sValue, $sClassName)
        {
            $sReturn = parent::process($sValue, $sClassName);

            if ((strpos($sClassName, 'oxw') !== 0) && (strpos(get_parent_class($sClassName), 'oxw') !== 0) &&
                (!$this->getConfig()->isAdmin()) &&
                ($aAdapters = WBL_Tracker_Adapter_Loader::getInstance()->getAdaptersForClass($sClassName)))
            {
                $oStr          = getStr();
                $sTrackerCodes = $this->processWBLAdapters($aAdapters, $sClassName);

                // TODO Verzweigung ob top or bottom.
                if ($oStr->strpos($sValue, $sSearch = '</body>') !== false) {
                    $sReturn = $oStr->preg_replace(
                        '#' . $sSearch . '#',
                        $sTrackerCodes . $sSearch,
                        $sValue
                    );
                } elseif (strtolower($sClassName) === 'oxemail') {
                    $sReturn = ($sReturn . $sTrackerCodes);
                } // elseif
            } // if

            return $sReturn;
        } // function

        /**
         * (non-PHPdoc)
         * @see http/core/oxOutput::processViewArray()
         */
        public function processViewArray($aData, $sClass)
        {
            $aData = parent::processViewArray($aData, $sClass);
            return $this->_aViewDataForTracker = $aData;
        } // function

        /**
         * Concatinates the HTML of the tracker.
         * @author blange <code@wbl-konzept.de>
         * @param WBL_Tracker_Adapter_Interface[] $aAdapters
         * @param string                          $sClassName
         * @return string
         */
        protected function processWBLAdapters(array $aAdapters, $sClassName)
        {
            $aArray  = $this->getViewData();
            $oSmarty = oxUtilsView::getInstance()->getSmarty();
            $oView   = null;
            $sHTML   = '';

            if (strtolower($sClassName) !== 'oxemail') {
                $oView = $this->getConfig()->getActiveView();
            } // if

            /* @var $oAdapter WBL_Tracker_Adapter_Interface */
            foreach ($aAdapters as $oAdapter) {
                $oAdapter->setSmarty($oSmarty)->setViewData($aArray);

                if ($oView) {
                    $oAdapter->setView($oView);
                } // if

                $oAdapter->init();
                $sHTML .= $oAdapter->getHTML();
            } // foreach
            unset($aAdapters, $aArray, $oAdapter, $oSmarty, $oView);

            return $sHTML;
        } // function
    } // class