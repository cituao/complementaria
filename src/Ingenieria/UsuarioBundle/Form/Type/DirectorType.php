<?php
// src/Ingenieria/UsuarioBundle/Form/Type/DirectorType.php
namespace Ingenieria\UsuarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DirectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('ci','text', array('label' => 'Cédula de identidad:','required' => true))	    
		->add('nombres','text', array('label' => 'Nombres:', 'required' => true))
        ->add('apellidos','text', array('label' => 'Apellidos:', 'required' => true))
 		->add('emailInstitucional', 'email',  array('required' => false, 'label' => 'Email institucional:'))
        ->add('email', 'email',  array('required' => false, 'label' => 'Email personal:'));
		}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ingenieria\UsuarioBundle\Entity\Director', 'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'director';
    }
}
