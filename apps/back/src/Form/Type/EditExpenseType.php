<?php

namespace App\Form\Type;

use App\Entity\Expense;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditExpenseType extends AbstractType
{
    /**
     * @param array{
     *  data: Expense
     * } $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $expense = $options['data'];

        $builder
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])

            ->add('amount', NumberType::class, [
                'label' => 'Montant',
            ])

            ->add('payer', EntityType::class, [
                'label' => 'Payé par',
                'class' => Person::class,
                'choices' => $expense->getGroup()->getPersons()->toArray(),
                'choice_label' => 'fullname',
            ])

            ->add('beneficiaries', EntityType::class, [
                'label' => 'Bénéficiaires',
                'class' => Person::class,
                'choices' => $expense->getGroup()->getPersons()->toArray(),
                'choice_label' => 'fullname',
                'multiple' => true,
                'expanded' => true,
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);

        $builder->get('amount')
            ->addModelTransformer(new CallbackTransformer(
                function (int $amount): string {
                    return (string) ($amount / 100);
                },

                function (string $amount): int {
                    return (int) ((float) $amount * 100);
                }
            ));
    }
}
