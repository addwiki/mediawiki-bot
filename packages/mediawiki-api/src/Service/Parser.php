<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\SimpleActionRequest;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use GuzzleHttp\Promise\PromiseInterface;
use RuntimeException;

/**
 * @access private
 */
class Parser extends Service {

	/**
	 * @param PageIdentifier $pageIdentifier
	 *
	 * @return array the parse result (raw from the api)
	 */
	public function parsePage( PageIdentifier $pageIdentifier ): array {
		return $this->parsePageAsync( $pageIdentifier )->wait();
	}

	/**
	 * @param PageIdentifier $pageIdentifier
	 *
	 * @return PromiseInterface of array the parse result (raw from the api)
	 */
	public function parsePageAsync( PageIdentifier $pageIdentifier ): \GuzzleHttp\Promise\PromiseInterface {
		$params = [];
		if ( $pageIdentifier->getId() !== null ) {
			$params['pageid'] = $pageIdentifier->getId();
		} elseif ( $pageIdentifier->getTitle() !== null ) {
			$params['page'] = $pageIdentifier->getTitle()->getText();
		} else {
			throw new RuntimeException( 'No way to identify page' );
		}

		$promise = $this->api->getRequestAsync( new SimpleActionRequest( 'parse', $params ) );

		return $promise->then( fn( $result ) => $result['parse'] );
	}

}
