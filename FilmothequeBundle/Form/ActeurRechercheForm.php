<?php
namespace DemoApp\FilmothequeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;

class ActeurRechercheForm extends AbstractType
{
	private $translator;
	
	public function __construct(Translator $translator = null)
	{
		$this->translator = $translator;
		
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
		if($this->translator === null)
		{
			$motclef = "mot-clef";
		}
		else
		{
			$motclef = $this->translator->trans("recherche.motclef");
		}
			
        $builder
			->add('motcle', 'text', array('label' => $motclef));
    }
    
    public function getName()
    {        
        return 'acteurrecherche';
    }
}
