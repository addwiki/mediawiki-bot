<?php

namespace Addwiki\Mediawiki\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\Request\SimpleActionRequest;
use Addwiki\Mediawiki\DataModel\Revision;

/**
 * @access private
 */
class RevisionDeleter extends Service {

	public function delete( Revision $revision ): bool {
		$params = [
			'type' => 'revision',
			'hide' => 'content',
			// Note: pre 1.24 this is a delete token, post it is csrf
			'token' => $this->api->getToken( 'delete' ),
			'ids' => $revision->getId(),
		];

		$this->api->postRequest( new SimpleActionRequest(
			'revisiondelete',
			$params
		) );

		return true;
	}

}
