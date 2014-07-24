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
		->add('descripcion','textarea', array('label' => 'Descripción:' , 'required' => true , 'attr' => array('cols' => '50', 'rows' => '5')))
		->add('url','text', array('required' => false, 'label' => 'Recurso digital:'))
		->add('categoria','entity', array('required' => true, 'class' => 'IngenieriaUsuarioBundle:Categoria' , 'property'=>'nombre', 'label' => 'Categoría:'))
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
        return 'profesor';
    }
}
