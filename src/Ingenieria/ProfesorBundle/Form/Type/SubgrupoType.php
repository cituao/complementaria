<?php
// src/Cituao/ExternoBundle/Form/Type/HojadevidaType.php
namespace Ingenieria\ProfesorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SubgrupoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('nombre','text', array('label' => 'Nombre:' , 'required' => true));  
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
