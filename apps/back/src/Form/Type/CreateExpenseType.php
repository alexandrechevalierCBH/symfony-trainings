<?php

namespace App\Form\Type;

use App\Entity\Group;
use App\Entity\Person;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;

/**
 * @phpstan-type ExpenseTypeFormData = array{
 *  slug: string,
 *  description: string,
 *  amount: integer,
 *  beneficiaries: Collection<int, Person>,
 *  payer: Person,
 * }
 */
class CreateExpenseType extends AbstractType
{
    /**
     * @param array{
     *  data: array{
     *      group: Group
     *      }
     *  } $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $group = $options['data']['group'];

        if (!$group instanceof Group) {
            throw new \Exception('Group not found');
        }

        $builder
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => true,
            ])

            ->add('amount', NumberType::class, [
                'label' => 'Montant',
                'required' => true,
                'constraints' => [
                    new GreaterThan(0),
                ]
            ])

            ->add('beneficiaries', EntityType::class, [
                'class' => Person::class,
                'choices' => $group->getPersons()->toArray(),
                'label' => 'Bénéficiaires',
                'choice_label' => 'firstname',
                'multiple' => true,
                'expanded' => true,
            ])

            ->add('payer', EntityType::class, [
                'class' => Person::class,
                'choices' => $group->getPersons()->toArray(),
                'label' => 'Payé par',
                'choice_label' => 'firstname',
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Créer',
            ]);
    }
}
