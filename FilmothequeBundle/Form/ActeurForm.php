<?php
namespace DemoApp\FilmothequeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ActeurForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
            ->add('nom', 'text', array('label' => 'acteur.nom'))
            ->add('prenom','text', array('label' => 'acteur.prenom'))
            ->add('dateNaissance', 'birthday', array('label' => 'Date de naissance'))
            ->add('sexe', 'choice', array('choices' => array('F'=>'Féminin','M'=>'Masculin')))
        ;
    }
    
    public function getName()
    {
        return 'acteur';
    }    
}
