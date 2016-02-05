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
		->add('mentor','text', array('label' => 'Mentor:' , 'read_only' => true))
		->add('email','text', array('label' => 'Email:' , 'disabled' => true))	 
   		->add('nombre','text', array('label' => 'Nombre actividad:' , 'disabled' => true))
		->add('proposito','text', array('label' => 'Propósito formativo:' , 'disabled' => true))
		->add('dirigida','text', array('label' => 'Dirigida preferentemente:' , 'disabled' => true))
		->add('descripcion','textarea', array('label' => 'Descripción:' , 'disabled' => true , 'attr' => array('cols' => '60', 'rows' => '7', 'placeholder' => 'Máximo 1000 carácteres')))
		->add('horario','text', array('label' => 'Franja de atención:' , 'disabled' => true))
        ->add('espacio','text', array('label' => 'Espacio de atención:' , 'disabled' => true))
		->add('trabajo', 'checkbox', array('disabled' => true, 'label' => 'Trabajo en equipo:'))
		->add('aprendizaje', 'checkbox', array('disabled' => true, 'label' => 'Aprendizaje autónomo y continuo:'))
		->add('pensamiento', 'checkbox', array('disabled' => true, 'label' => 'Pensamiento crítico:'))
        ->add('comunicacion', 'checkbox', array('disabled' => true, 'label' => 'Comunicación oral, escrita y gráfica:'))
		->add('autonomia', 'checkbox', array('disabled' => true, 'label' => 'Autonomía:'))
		->add('integralidad', 'checkbox', array('disabled' => true, 'label' => 'Integralidad:'))
		->add('excelencia', 'checkbox', array('disabled' => true, 'label' => 'Excelencia:'))
		->add('creatividad', 'checkbox', array('disabled' => true, 'label' => 'Creatividad:'))
		->add('eticidad', 'checkbox', array('disabled' => true, 'label' => 'Eticidad:'))
		->add('responsabilidad', 'checkbox', array('disabled' => true, 'label' => 'Responsabilidad:'))
		->add('pertenencia', 'checkbox', array('disabled' => true, 'label' => 'Pertenencia:'))
		->add('honestidad', 'checkbox', array('disabled' => true, 'label' => 'Honestidad:'))
		->add('url','text', array('disabled' => true, 'label' => 'Recurso digital:'))
		->add('categoria','entity', array('disabled' => true, 'class' => 'IngenieriaUsuarioBundle:Categoria' , 'property'=>'nombre', 'label' => 'Categoría:', 'empty_value' => 'Seleccione?'))
		->add('numeroCupos','integer', array('label' => 'Número de cupos:' , 'disabled' => true))
        ->add('recomendacion','textarea', array('label' => 'Recomendaciones y observaciones:' , 'disabled' => true , 'attr' => array('cols' => '60', 'rows' => '7', 'placeholder' => 'Máximo 1000 carácteres')));

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
