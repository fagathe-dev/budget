<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\Category;
use App\Repository\BudgetRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class BudgetType extends AbstractType
{

    public function __construct(
        public BudgetRepository $repository
    ){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', IntegerType::class, [
                'label' => 'Montant',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'constraints' => [
                    new Assert\Callback([
                        'callback' => function (mixed $value, ExecutionContextInterface $context) {
                            if (!$value) {
                                return;
                            }
                            if ($this->repository->hasAlreadyCategory($value) instanceof Budget) {
                                return $context
                                    ->buildViolation('Vous avez déjà défini ce budget !')
                                    ->atPath('[category]')
                                    ->addViolation();
                            }
                        }
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Budget::class,
        ]);
    }
}
