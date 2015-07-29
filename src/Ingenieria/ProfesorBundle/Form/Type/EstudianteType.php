<?php
// src/Cituao/CoordBundle/Form/Type/PracticanteType.php
namespace Ingenieria\ProfesorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EstudianteType extends AbstractType
{
	protected $subgrupos;

	public function __construct($s){
		$this->subgrupos = $s;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('ci','text', array('label' => 'Cédula de identidad'))	    
		->add('codigo','text', array('label' => 'Código'))
        ->add('apellidos','text', array('label' => 'Apellidos'))
		->add('nombres','text', array('label' => 'Nombres'))
        ->add('email', 'email',  array('required' => false, 'label' => 'Email'))
		->add('subgrupo', 'entity', array('class' => 'IngenieriaProfesorBundle:Subgrupo', 'choices' => $this->subgrupos, 'empty_value' => 'Seleccione?'));
		}

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ingenieria\EstudianteBundle\Entity\Estudiante', 'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'estudiante';
    }
}
