<?php declare(strict_types = 1);

namespace App\PlayGroundModule\Presenters;


class FillPresenter extends \Nette\Application\UI\Presenter
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


	public function actionDefault()
	{
		$data = \file_get_contents(__DIR__ . '/../../../data/products.json');
		$decoded = \Nette\Utils\Json::decode($data);

		foreach ($decoded->data as $item) {
			$this->clientProvider->client()->index([
				'body' => $item->_source + [
					'price' => \rand(0, 6000)
				],
				'index' => \App\Settings::ELASTIC_INDEX,
				'type' => \App\Settings::ELASTIC_INDEX,
			]);
		}

		$this->terminate();
	}

}
