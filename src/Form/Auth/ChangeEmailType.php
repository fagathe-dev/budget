<?php

namespace App\Form\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ChangeEmailType extends AbstractType
{

    public function __construct(
        private UserRepository $repository,
        private Security $security
    ){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Nouvelle adresse e-mail',
                'attr' => [
                    'placeholder' => 'Nouvelle adresse e-mail',
                ],
                'constraints' => [
                    new NotBlank([], 'Ce champ est obligatoire !'),
                    new Callback([
                        'callback' => function (mixed $value, ExecutionContextInterface $context) use ($builder) {
                            if ($this->security->getUser()->getEmail() === $value) {
                                return $context
                                    ->buildViolation('Veuillez saisir une autre adresse e-mail !')
                                    ->atPath('[currentPassword]')
                                    ->addViolation();
                            }
                            if ($this->repository->findOneBy(['email' => $value]) instanceof User) {
                                return $context
                                    ->buildViolation('Cette adresse email est déjà utilisée !')
                                    ->atPath('[currentPassword]')
                                    ->addViolation();
                            }
                        }
                    ]),
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Changer mon adresse e-mail',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
