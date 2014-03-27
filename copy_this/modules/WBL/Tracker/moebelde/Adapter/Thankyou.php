<?php
    /**
     * ./modules/WBL/Tracker/moebelde/Adapter/Thankyou.php
     * @author     Bjoern Simon Lange <code@wbl-konzept.de>
     * @category   modules
     * @package    WBL_Tracker
     * @subpackage moebelde
     * @version    SVN: $Id$
     */

    /**
     * Thankyou-Tracking for moebel.de.
     * @author     Bjoern Simon Lange <code@wbl-konzept.de>
     * @category   modules
     * @package    WBL_Tracker
     * @subpackage moebelde
     * @version    SVN: $Id$
     */
    class WBL_Tracker_moebelde_Adapter_Thankyou extends WBL_Tracker_Adapter_Abstract
    {
        public function getHTML()
        {
            $sReturn = '';

            if (($oBasket = $this->getView()->getBasket()) instanceof oxBasket) {
                $sList = '';

                /** @var $oItem oxBasketItem */
                foreach($oBasket->getContents() as $oItem) {
                    if (($oArticle = $oItem->getArticle(false)) && ($sArtNo = $oArticle->oxarticles__oxartnum->value)) {
                        $sList .= $sArtNo . ',';
                    } // if
                } // foreach

                $sReturn =
                    '<script type="text/javascript">/* <![CDATA[ */' .
                        'var _mo = _mo || [];' .
                        '_mo.push(["_key", "' . $this->getConfig()->getConfigParam('sWBLTrackerMoebelDeSecKey') . '"]);' .
                        '_mo.push(["_umsatz", "' . (float) $oBasket->getNettoSum() . '"]);' .
                        '_mo.push(["_versandkosten", "' . (float) $oBasket->getDeliveryCosts() . '"]);' .
                        '_mo.push(["_artikelliste", "' . trim($sList, ',') . '"]);' .
                        '(function () {' .
                        '   var mo = document.createElement("script");' .
                        '   mo.type = "text/javascript";' .
                        '   mo.async = true;' .
                        '   mo.src = "https://sales1.moebel.de/asyndic.js";' .
                        '   var s = document.getElementsByTagName("script")[0];' .
                        '   s.parentNode.insertBefore(mo, s);' .
                        '})();' .
                    '/* ]]> */</script>';
            } // if

            return $sReturn;
        } // function
    } // class