<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\SimpleActionRequest;
use Addwiki\Mediawiki\DataModel\Revision;

/**
 * @access private
 */
class RevisionPatroller extends Service {

	public function patrol( Revision $revision ): bool {
		$this->api->postRequest( new SimpleActionRequest(
			'patrol', [
				'revid' => $revision->getId(),
				'token' => $this->getTokenForRevision( $revision ),
			] ) );
		return true;
	}

	private function getTokenForRevision( Revision $revision ): string {
		$result = $this->api->postRequest( new SimpleActionRequest( 'query', [
			'list' => 'recentchanges',
			'rcstart' => $revision->getTimestamp(),
			'rcend' => $revision->getTimestamp(),
			'rctoken' => 'patrol',
		] ) );
		$result = array_shift( $result['query']['recentchanges'] );
		return $result['patroltoken'];
	}

}
