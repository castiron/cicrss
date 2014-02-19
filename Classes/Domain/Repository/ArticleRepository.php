<?php

/**
 * Repository for Tx_Cicrss_Domain_Model_Article
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Cicrss_Domain_Repository_ArticleRepository extends Tx_Extbase_Persistence_Repository {

	/**
     * Retrieve articles
	 * @param $start
	 * @param $length
	 * @param int $updateInterval
	 * @param $feedDefaultUpdateInterval
	 * @param $feedAddress
	 * @return mixed A collection of article objects.
	 */
	public function getArticlesFromFeedService($start, $length, $updateInterval = 3600, $feedDefaultUpdateInterval, $feedAddress) {
		$this->feedService = $this->objectManager->get('Tx_Cicrss_Service_FeedService');
		$this->feedService->setFeedUrl($feedAddress);

		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
		$articles = $objectManager->create('Tx_Extbase_Persistence_ObjectStorage');
		$feedItems = $this->feedService->getFeedItems($start, $length, $updateInterval, $feedDefaultUpdateInterval);

		foreach ($feedItems as $spArticle) {
			$articleObj = $objectManager->create('Tx_Cicrss_Domain_Model_Article');
			$articleObj->setHeadline($spArticle->get_title());
			$articleObj->setContent($spArticle->get_content());
			$articleObj->setTeaser($spArticle->get_content());
			$articleObj->setPermalink($spArticle->get_permalink());
			$articleObj->setDate($spArticle->get_date());
			$author = $spArticle->get_author();
			if ($author) $articleObj->setAuthorName($author->get_name());
			$articles->attach($articleObj);
		}
		return $articles;
	}
}
?>