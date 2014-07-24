<?php
// src/Ingenieria/UsuarioBundle/Form/Type/DirectorType.php
namespace Ingenieria\UsuarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('nombre','text', array('label' => 'Nombre:','required' => true, 'max_length' => '100'));
		}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ingenieria\UsuarioBundle\Entity\Categoria', 'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'usuario';
    }
}
