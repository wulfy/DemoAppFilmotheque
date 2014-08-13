<?php
namespace DemoApp\FilmothequeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategorieForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('nom');
    }
    
    public function getName()
    {
        return 'categorie';
    }    
}
