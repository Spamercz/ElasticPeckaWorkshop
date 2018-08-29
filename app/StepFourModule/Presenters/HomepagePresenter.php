<?php declare(strict_types = 1);

namespace App\StepFourModule\Presenters;


class HomepagePresenter extends \Nette\Application\UI\Presenter
{

	/**
	 * @var \App\StepFourModule\Search
	 */
	private $search;


	public function __construct(
		\App\StepFourModule\Search $search
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
