<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppTaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Please enter your next priority',
                'label_attr' => [
                    'class' => 'text-muted of-text-small',
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'I want to make soup!',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Concentrate',
                'attr' => [
                    'class' => 'input-group-text bg-dark text-white'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            []
        );
    }

}
