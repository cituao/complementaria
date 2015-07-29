<?php
// src/Cituao/ExternoBundle/Form/Type/HojadevidaType.php
namespace Ingenieria\ProfesorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SubgrupoType extends AbstractType
{
	protected $subgrupo;

	public function __construct($s=null){
		$this->subgrupo = $s;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$id_subgrupo = $this->subgrupo;
		
		if ($this->subgrupo  == null) {
			$builder
				->add('nombre','text', array('label' => 'Nombre:' , 'required' => true));
		}
		else{
			$builder
				->add('nombre','text', array('label' => 'Nombre:' , 'required' => true))
				->add('lider', 'entity', array('label' => 'Lider:', 'class' => 'IngenieriaEstudianteBundle:Estudiante', 
													  'query_builder' => function(EntityRepository $er) use ($id_subgrupo) {
														  return $er->createQueryBuilder('e')
														  ->where('e.subgrupo =:id_subgrupo')
													      ->setParameter('id_subgrupo', $id_subgrupo); },
													  'empty_value' => 'Seleccione?'));
		}
	}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ingenieria\ProfesorBundle\Entity\Subgrupo', 'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'subgrupo';
    }
}
