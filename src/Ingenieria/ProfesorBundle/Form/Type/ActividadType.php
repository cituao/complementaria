<?php
// src/Ingenieria/ProfesorBundle/Form/Type/ProfesorType.php
namespace Ingenieria\ProfesorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActividadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('nombre','text', array('label' => 'Nombre:' , 'required' => true))	    
		->add('descripcion','text', array('label' => 'Descripción:' , 'required' => true))
		->add('url','text', array('required' => false, 'label' => 'Direccion web'))
		->add('categoria', 'choice', array(
				'choices' => array('1' => 'Organización de eventos académicos', '2' => 'Talleres prácticos', '3' => 'Aplicaciones de TICs', '4' => 'Cursar y aprobar un MOOC', '5' => 'Apoyo a la gestión'),
				'required' => true,))
        ->add('numeroCupos','integer', array('label' => 'Número de cupos:' , 'required' => true));
        
		
		
		}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ingenieria\ProfesorBundle\Entity\Actividad', 'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'director';
    }
}
