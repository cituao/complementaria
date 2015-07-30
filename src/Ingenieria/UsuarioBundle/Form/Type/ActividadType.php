<?php
// src/Ingenieria/ProfesorBundle/Form/Type/ProfesorType.php
namespace Ingenieria\UsuarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActividadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('mentor','text', array('label' => 'Mentor:' , 'required' => true))
		->add('email','text', array('label' => 'Email:' , 'required' => true))	 
   		->add('nombre','text', array('label' => 'Nombre actividad:' , 'required' => true))
		->add('proposito','text', array('label' => 'Propósito formativo:' , 'required' => true))
		->add('dirigida','text', array('label' => 'Dirigida preferentemente:' , 'required' => true))
		->add('descripcion','textarea', array('label' => 'Descripción:' , 'required' => true , 'attr' => array('cols' => '60', 'rows' => '7', 'placeholder' => 'Máximo 1000 carácteres')))
		->add('horario','text', array('label' => 'Horario:' , 'required' => true))
		->add('trabajo', 'checkbox', array('required' => false, 'label' => 'Trabajo en equipo:'))
		->add('aprendizaje', 'checkbox', array('required' => false, 'label' => 'Aprendizaje autónomo y continuo:'))
		->add('pensamiento', 'checkbox', array('required' => false, 'label' => 'Pensamiento crítico:'))
		->add('autonomia', 'checkbox', array('required' => false, 'label' => 'Autonomía:'))
		->add('integralidad', 'checkbox', array('required' => false, 'label' => 'Integralidad:'))
		->add('excelencia', 'checkbox', array('required' => false, 'label' => 'Excelencia:'))
		->add('creatividad', 'checkbox', array('required' => false, 'label' => 'Creatividad:'))
		->add('eticidad', 'checkbox', array('required' => false, 'label' => 'Eticidad:'))
		->add('responsabilidad', 'checkbox', array('required' => false, 'label' => 'Responsabilidad:'))
		->add('pertenencia', 'checkbox', array('required' => false, 'label' => 'Pertenencia:'))
		->add('honestidad', 'checkbox', array('required' => false, 'label' => 'Honestidad:'))
		->add('url','text', array('required' => false, 'label' => 'Recurso digital:'))
		->add('categoria','entity', array('required' => true, 'class' => 'IngenieriaUsuarioBundle:Categoria' , 'property'=>'nombre', 'label' => 'Categoría:', 'empty_value' => 'Seleccione?'))
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
        return 'usuario';
    }
}
