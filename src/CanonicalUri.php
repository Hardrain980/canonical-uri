<?php

namespace Leo\Middlewares;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \GuzzleHttp\Psr7;

class CanonicalUri implements MiddlewareInterface
{
	private string $canonical_host;

	public function __construct(string $prefix)
	{
		// Retrieve scheme, host and port if presented as:
		// scheme://host[:port]
		preg_match('/^.*?:\/\/.*?(?=(\/|$))/', $prefix, $match);

		$this->canonical_host = $match[0];
	}

	public function process(
		ServerRequestInterface $request,
		RequestHandlerInterface $handler
	):ResponseInterface
	{
		$request = $request->withUri(new Psr7\Uri(
			$this->canonical_host .
			$request->getRequestTarget()
		));

		return $handler->handle($request);
	}
}

?>
