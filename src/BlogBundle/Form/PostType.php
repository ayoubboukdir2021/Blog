<?php

namespace BlogBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')
                ->add('description')
                ->add('datepublish' ,  DateType::class , [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'data'   => new \DateTime()
                ])
                ->add('image',FileType::class,[
                    'label' => 'image png ou jpeg' , 'data_class' => null
                ])
                ->add('categories' , EntityType::class,[
                    'class' => 'BlogBundle\Entity\Category',
                    'choice_label' => 'libelle',
                    'expanded' => false,
                    'multiple' => false
                ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_post';
    }


}
