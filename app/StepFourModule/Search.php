<?php declare(strict_types = 1);

namespace App\StepFourModule;


class Search
{

	/**
	 * @var \App\StepTwoModule\ClientProvider
	 */
	private $clientProvider;


	public function __construct(
		\App\StepTwoModule\ClientProvider $clientProvider
	)
	{
		$this->clientProvider = $clientProvider;
	}


	public function perform(\Nette\Application\UI\Form $form): array
	{
		$values = $form->getValues();

		$query = new \Pd\ElasticSearchModule\Model\ElasticQuery(
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
		);
		$query->aggregations()->add(
			new \Pd\ElasticSearchModule\Model\ElasticQuery\Aggregation(
				'categories',
				new \Pd\ElasticSearchModule\Model\ElasticQuery\Aggregation\Terms(
					'category'
				)
			)
		);
		$query->aggregations()->add(
			new \Pd\ElasticSearchModule\Model\ElasticQuery\Aggregation(
				'price',
				new \Pd\ElasticSearchModule\Model\ElasticQuery\Aggregation\Range(
					'price',
					TRUE,
					new \Pd\ElasticSearchModule\Model\ElasticQuery\Aggregation\RangeValueCollection(
						... [
							new \Pd\ElasticSearchModule\Model\ElasticQuery\Aggregation\RangeValue(
								'0 až 2000',
								0,
								2000
							),
							new \Pd\ElasticSearchModule\Model\ElasticQuery\Aggregation\RangeValue(
								'2000 až 4000',
								2000,
								4000
							),
							new \Pd\ElasticSearchModule\Model\ElasticQuery\Aggregation\RangeValue(
								'4000 až 6000',
								4000,
								6000
							),
						]
					)
				)
			)
		);

		$document = new \Pd\ElasticSearchModule\Model\Document(
			\App\Settings::ELASTIC_INDEX,
			new \Pd\ElasticSearchModule\Model\Document\Query(
				$query
			),
			\App\Settings::ELASTIC_INDEX
		);

		\Tracy\Debugger::barDump($document->toArray());
		$resultObject = $this->clientProvider->client()->search($document->toArray());

		\Tracy\Debugger::barDump($resultObject);

		return $resultObject['hits']['hits'];
	}

}
