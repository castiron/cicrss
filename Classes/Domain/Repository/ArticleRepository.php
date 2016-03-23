<?php

namespace CIC\Cicrss\Domain\Repository;

/**
 * Class ArticleRepository
 * @package CIC\Cicrss\Domain\Repository
 */
class ArticleRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
    /**
     * @var \CIC\Cicrss\Service\FeedService
     * @inject
     */
    var $feedService;

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
		$this->feedService->setFeedUrl($feedAddress);

		$articles = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');
		$feedItems = $this->feedService->getFeedItems($start, $length, $updateInterval, $feedDefaultUpdateInterval);

        /** @var \SimplePie_Item $spArticle */
        foreach ($feedItems as $spArticle) {
            /** @var \CIC\Cicrss\Domain\Model\Article $articleObj */
			$articleObj = $this->objectManager->get('CIC\\Cicrss\\Domain\\Model\\Article');
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
