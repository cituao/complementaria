<?php
namespace Ingenieria\UsuarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BitacoraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('nombreActividad','text', array('label' => 'Nombre actividad:' , 'required' => true , 'disabled' => true))
		->add('descripcion','textarea', array('label' => 'Descripción:' , 'required' => true , 'attr' => array('cols' => '60', 'rows' => '7', 'placeholder' => '¡Máximo 1200 carácteres!' ) ,'disabled' => true ))
		->add('fechaInicio', 'date', array('label' => 'Fecha inicio:','widget' => 'single_text',  'format' => 'dd-MM-yyyy', 'read_only' => 'true'))
		->add('fechaFin', 'date', array('label' => 'Fecha fin','widget' => 'single_text',  'format' => 'dd-MM-yyyy', 'read_only' => 'true'))
		->add('finalizada', 'checkbox', array('required' => false, 'label' => '¿Finalizado?:'));
		}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ingenieria\EstudianteBundle\Entity\Bitacora', 'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'usuario';
    }
}