<?php
namespace DemoApp\FilmothequeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FilmForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
            ->add('titre')
            ->add('description')
            ->add('categorie', 'entity', array(
					'class' => 'DemoApp\FilmothequeBundle\Entity\Categorie',
					'property' => 'Nom',
					'expanded' => true,
					'multiple' => false,
					'required' => false
				))
			->add('acteurs', 'entity', array(
					'class' => 'DemoApp\FilmothequeBundle\Entity\Acteur',
					'property' => 'PrenomNom',
					'expanded' => true,
					'multiple' => true,
					'required' => false
				))
			->add('Enregistrer', 'submit');
        ;
    }
    
    public function getName()
    {
        return 'film';
    }    
}
