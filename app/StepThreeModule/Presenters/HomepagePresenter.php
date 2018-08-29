<?php declare(strict_types = 1);

namespace App\StepThreeModule\Presenters;


class HomepagePresenter extends \Nette\Application\UI\Presenter
{

	/**
	 * @var \App\StepThreeModule\Search
	 */
	private $search;


	public function __construct(
		\App\StepThreeModule\Search $search
	)
	{
		parent::__construct();
		$this->search = $search;
	}


	public function createComponentSearch(): ?\Nette\ComponentModel\IComponent
	{
		$form = new \Nette\Application\UI\Form();

		$form->addText('query', 'Search');

		$form->addSubmit('Send');

		$form->onSubmit[] = [$this->search, 'perform'];

		return $form;
	}

}
