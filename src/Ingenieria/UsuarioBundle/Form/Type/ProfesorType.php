<?php
// src/Ingenieria/DirectorBundle/Form/Type/ProfesorType.php
namespace Ingenieria\UsuarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfesorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('ci','text', array('label' => 'Cédula de identidad:' , 'required' => true))	    
		->add('nombres','text', array('label' => 'Nombres:' , 'required' => true))
        ->add('apellidos','text', array('label' => 'Apellidos:' , 'required' => true))
        ->add('email', 'email',  array('required' => false, 'label' => 'Email:'))
		->add('emailInstitucional','text', array('required' => false, 'label' => 'Email institucional:'));
		}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ingenieria\ProfesorBundle\Entity\Profesor', 'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'usuario';
    }
}
