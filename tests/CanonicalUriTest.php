<?php

use \Leo\Middlewares\CanonicalUri;
use \Leo\Fixtures\DummyRequestHandler;
use \PHPUnit\Framework\TestCase;
use \GuzzleHttp\Psr7;

/**
 * @testdox Leo\Middlewares\CanonicalUri
 */
class CanonicalUriTest extends TestCase
{
	private $handler;

	public function setUp():void
	{
		$this->handler = new DummyRequestHandler();
	}

	public function testPrefixWithPath():void
	{
		$middleware = new CanonicalUri('https://mydomain.tld/app');
		$request = new Psr7\ServerRequest('GET', 'https://hacker.tld/app/reset-password');

		$middleware->process($request, $this->handler);

		$this->assertSame(
			'https://mydomain.tld/app/reset-password',
			strval($this->handler->getRequest()->getUri())
		);
	}

	public function testPrefixWithoutPath():void
	{
		$middleware = new CanonicalUri('https://mydomain.tld');
		$request = new Psr7\ServerRequest('GET', 'https://hacker.tld/reset-password');

		$middleware->process($request, $this->handler);

		$this->assertSame(
			'https://mydomain.tld/reset-password',
			strval($this->handler->getRequest()->getUri())
		);
	}

	public function testRequestWithoutPath():void
	{
		$middleware = new CanonicalUri('https://mydomain.tld');
		$request = new Psr7\ServerRequest('GET', 'https://hacker.tld');

		$middleware->process($request, $this->handler);

		$this->assertSame(
			'https://mydomain.tld/',
			strval($this->handler->getRequest()->getUri())
		);
	}
}

?>
