<?php

namespace Addwiki\Mediawiki\Api\Client\Auth;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;

/**
 * For use with plain MediaWiki and no authentication (anon)
 */
class NoAuth implements AuthMethod {

	public function preRequestAuth( string $method, ActionRequest $request, ActionApi $api ): ActionRequest {
		// Verify that the user is logged in if set to user, not logged in if set to anon, or has the bot user right if bot.
		$request->setParam( 'assert', 'anon' );
		return $request;
	}

	public function identifierForUserAgent(): ?string {
		return null;
	}

}
