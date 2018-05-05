<?php

declare(strict_types=1);

namespace AppBundle\Form;

use AppBundle\Model\User\UserRegistrationCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    private $userTypeCommon;

    public function __construct(UserTypeCommon $userTypeCommon)
    {
        $this->userTypeCommon = $userTypeCommon;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->userTypeCommon->buildForm($builder, $options);

        $builder
            ->add('username')
            ->add('Register', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationCommand::class
        ]);
    }
}