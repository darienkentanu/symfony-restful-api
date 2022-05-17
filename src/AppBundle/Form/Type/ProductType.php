<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Product;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        // ->add('id', IntegerType::class)
        ->add('name', TextType::class)
        ->add('price', IntegerType::class)
        ->add('description', TextType::class);
        // ->add('save', SubmitType::class, array('label' => 'Add Product'));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \AppBundle\Entity\Product::class,
            'csrf_protection' => false,
        ]);
    }

    // public function getBlockPrefix()
    // {
        // return 'product';
//     }
}