<?php declare(strict_types = 1);

namespace App\StepTwoModule;


class ClientProvider
{

	/**
	 * @var \Elasticsearch\ClientBuilder
	 */
	private $clientBuilder;


	public function __construct(
		\Elasticsearch\ClientBuilder $clientBuilder
	)
	{
		$this->clientBuilder = $clientBuilder;
		$this->init();
	}


	public function init() : void
	{
		$this->clientBuilder->setHosts([
			\App\Settings::ELASTIC_IP
		]);
	}


	public function client() : \Elasticsearch\Client
	{
		return $this->clientBuilder->build();
	}

}
