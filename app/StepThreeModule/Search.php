<?php declare(strict_types = 1);

namespace App\StepThreeModule;


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
					new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\ShouldCollection(),
					new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\MustCollection(
						... [
							new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\Term(
								'isOld',
								FALSE
							),
							new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\Term(
								'isPublic',
								TRUE
							),
							new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\Terms(
								'category',
								[
									2023,
								]
							)
						]
					)
				)
			)
		);


		$subQuery = new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\SubQuery();
		$subQuery->should()->add(new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\WildCard(
			'name',
			$values->query,
			2
		));
		$subQuery->should()->add(new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\PhrasePrefix(
			'name',
			$values->query,
			2,
			50
		));
		$subQuery->should()->add(new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\Match(
			'name',
			$values->query,
			5,
			50,
			'AUTO',
			'phrase',
			'75%',
			'or'
		));
		$query->query()->add(
			new \Pd\ElasticSearchModule\Model\ElasticQuery\Query(
				new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\ShouldCollection($subQuery)
			)
		);


		$query->query()->add(
			new \Pd\ElasticSearchModule\Model\ElasticQuery\Query(
				NULL,
				new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\MustCollection(
					new \Pd\ElasticSearchModule\Model\ElasticQuery\Query\Term(
						'hasImages',
						TRUE
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

		$resultObject = $this->clientProvider->client()->search($document->toArray());

		\Tracy\Debugger::barDump($document->toArray());
		\Tracy\Debugger::barDump($resultObject);

		return $resultObject['hits']['hits'];
	}

}
