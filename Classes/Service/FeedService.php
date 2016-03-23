<?php
namespace CIC\Cicrss\Service;
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
require_once(ExtensionManagementUtility::extPath('cicrss') . 'Classes/Domain/Vendor/SimplePie/autoloader.php');
require_once(ExtensionManagementUtility::extPath('cicrss') . 'Classes/Domain/Vendor/SimplePie/idn/idna_convert.class.php');

/**
 * Class FeedService
 * @package CIC\Cicrss\Service
 */
class FeedService {

    /**
     * @var \TYPO3\CMS\Core\Cache\CacheManager
     * @inject
     */
    var $cacheManager;

    protected $feedUrl = '';
    protected $cache = false;
    protected $dateFormat = 'j F Y, g:i a';

    /**
     * @param $feedUrl
     */
    public function setFeedUrl($feedUrl) {
        $this->feedUrl = $feedUrl;
    }

    /**
     * @return null
     */
    protected function getFeedUrl() {
        return $this->feedUrl;
    }

    /**
     * @return bool|\TYPO3\CMS\Core\Cache\Frontend\FrontendInterface
     */
    protected function getCache() {
        $out = false;
        try {
            $out = $this->cacheManager->getCache('cicrss_cache');
        } catch (NoSuchCacheException $e) {
            // Unable to load
        }
        return $out;
    }

    /**
     * @param $format
     */
    public function setDateFormat($format) {
        $this->dateFormat = $format;
    }

    /**
     * @param int $start
     * @param int $length
     * @param int $updateInterval
     * @param $feedDefaultUpdateInterval
     * @return mixed
     */
    public function getFeedItems($start = 0, $length = 10, $updateInterval = 3600, $feedDefaultUpdateInterval) {
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
            $feed = new \SimplePie();

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
