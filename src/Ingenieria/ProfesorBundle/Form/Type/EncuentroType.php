<?php
// src/Ingenieria/ProfesorBundle/Form/Type/ProfesorType.php
namespace Ingenieria\ProfesorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EncuentroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('fecha', 'date', array('label' => 'Fecha:','widget' => 'single_text',  'format' => 'dd-MM-yyyy', 'read_only' => 'true'))
		->add('resumen','textarea', array('label' => 'Resumen:' , 'required' => true , 'attr' => array('cols' => '150', 'rows' => '12', 'placeholder' => 'Máximo 1000 carácteres')))
		->add('observaciones','textarea', array('label' => 'Observaciones:' , 'attr' => array('cols' => '150', 'rows' => '7', 'placeholder' => 'Máximo 1000 carácteres')));
		}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ingenieria\ProfesorBundle\Entity\Encuentro', 'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'profesor';
    }
}
