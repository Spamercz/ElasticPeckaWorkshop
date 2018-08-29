<?php declare(strict_types = 1);

namespace App\StepTwoModule\Presenters;


class HomepagePresenter extends \Nette\Application\UI\Presenter
{

	/**
	 * @var \App\StepTwoModule\Search
	 */
	private $search;


	public function __construct(
		\App\StepTwoModule\Search $search
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
