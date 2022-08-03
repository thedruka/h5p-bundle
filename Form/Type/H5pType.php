<?php

namespace Emmedy\H5PBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class H5pType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [ "required" => false ])
            ->add('library', TextType::class, [ "required" => false ])
            ->add('parameters', TextType::class, [ "required" => false ])
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }
}
