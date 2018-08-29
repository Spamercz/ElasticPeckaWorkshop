<?php declare(strict_types = 1);

namespace App\StepTwoModule;


class Search
{

	/**
	 * @var \App\StepTwoModule\ClientProvider
	 */
	private $clientProvider;


	public function __construct(
		ClientProvider $clientProvider
	)
	{
		$this->clientProvider = $clientProvider;
	}


	public function perform(\Nette\Application\UI\Form $form): array
	{
		$values = $form->getValues();

		// ARRAY
		$resultArray = $this->clientProvider->client()->search([
			'index' => \App\Settings::ELASTIC_INDEX,
			'type' => \App\Settings::ELASTIC_INDEX,
			'body' => [
				'query' => [
					'bool' => [
						'should' => [
							[
								'term' => [
									'name' => [
										'value' => $values->query,
										'boost' => 1.0,
									],
								],
							],
						],
					],
				],
			],
		]);

		$document = new \Pd\ElasticSearchModule\Model\Document(
			\App\Settings::ELASTIC_INDEX,
			new \Pd\ElasticSearchModule\Model\Document\Query(
				new \Pd\ElasticSearchModule\Model\ElasticQuery(
					new \Pd\ElasticSearchModule\Model\ElasticQuery\Options(),
					new \Pd\ElasticSearchModule\Model\ElasticQuery\QueryCollection(
						new \Pd\ElasticSearchModule\Model\ElasticQuery\Query(
							new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\ShouldCollection(
								new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\Term(
									'name',
									$values->query
								)
							)
						)
					)
				)
			),
			\App\Settings::ELASTIC_INDEX
		);

		// OBJECT
		$resultObject = $this->clientProvider->client()->search(
			$document->toArray()
		);

		\Tracy\Debugger::barDump($document->toArray());
		\Tracy\Debugger::barDump($resultObject);

		return $resultObject['hits']['hits'];
	}

}
