<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Zach Davis <zach@castironcoding.com>, Cast Iron Coding, Inc
 *			  Lucas Thurston <lucas@castironcoding.com>, Cast Iron Coding, Inc
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Service to retrieve feed information. Essentially a Simplepie wrapper.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

require_once(t3lib_extMgm::extPath('cicrss') . 'Classes/Domain/Vendor/SimplePie/autoloader.php');
require_once(t3lib_extMgm::extPath('cicrss') . 'Classes/Domain/Vendor/SimplePie/idn/idna_convert.class.php');

class Tx_Cicrss_Service_FeedService {

		protected $feedUrl = null;
		protected $cache = false;
//		protected $dateFormat = 'j F Y, g:i a'; // See documentation for PHP date() function - passed into simplepie

		public function __construct($feedUrl = false)
		{
			$this->setFeedUrl($feedUrl);
		}

		public function setFeedUrl($feedUrl)
		{
			$this->feedUrl = $feedUrl;
		}

		protected function getFeedUrl()
		{
			return $this->feedUrl;
		}

		protected function getCache()
		{
			try {
				$cache = $GLOBALS['typo3CacheManager']->getCache('cicrss_cache');
			} catch (t3lib_cache_exception_NoSuchCache $e) {
				// Unable to load
			}
			return $cache;
		}

		public function setDateFormat($format)
		{
			$this->dateFormat = $format;
		}

		public function getFeedItems($start = 0, $length = 10, $updateInterval = 3600, $feedDefaultUpdateInterval)
		{
			// Initialize the cache
			$cache = $this->getCache();

			// Generate our cache ids.
			$shortTermId = md5($this->feedUrl . '_' . $start . '_' . $length . '_' . $updateInterval);
			$farTermId = md5($this->feedUrl . '_' . $start . '_' . $length . '_FALLBACK');

			// Check the cache for this feed and other passed values.
			if ($cache->has($shortTermId) && $updateInterval) {
				$items = unserialize($cache->get($shortTermId));
				$out = $items;
			} else {
				if (!$updateInterval) {
					// If there's no update interval, then we must be clearing the cache. Regenerate the short-term id using the feed's specified update interval.
					$updateInterval = $feedDefaultUpdateInterval;
					$shortTermId = md5($this->feedUrl . '_' . $start . '_' . $length . '_' . $updateInterval);
				}
				$feed = new SimplePie();

				$feed->set_feed_url($this->feedUrl);
				$feed->init();
				$feed->handle_content_type();
				$items = $feed->get_items($start, $length);
				if (!$items) {
					$fallbackItems = unserialize($cache->get($farTermId));
					$out = $fallbackItems;
				} else {
					$cache->set($shortTermId, serialize($items), array(), $updateInterval);
					$cache->set($farTermId, serialize($items), array(), 77760000);
					$out = $items;
				}
			}
			return $out;
		}
	}

?>