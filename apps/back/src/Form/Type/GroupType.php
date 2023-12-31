<?php

namespace App\Form\Type;

use App\Entity\Person;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *  @phpstan-type GroupTypeFormData = array{
 * label: string,
 * description: ?string,
 * persons: Collection<int, Person>,
 * slug: string
 *  }
 */
class GroupType extends AbstractType
{
    public function __construct(private ParameterBagInterface $parameterBag)
    {
    }

    /**
     * @param array{
     *  data?: array{
     *      data?: array{
     *          time?: string
     *          }
     *      }
     *  } $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $time = $options['data']['data']['time'] ?? time();
        /** @var string $appUrl */
        $appUrl = $this->parameterBag->get('APP_URL');

        $builder
            ->add('label', TextType::class, [
                'label' => 'Nom du groupe',
            ])

            ->add('description', TextType::class, [
                'required' => false,
            ])

            ->add('persons', EntityType::class, [
                'class' => Person::class,
                'label' => 'Membres du groupe',
                'choice_label' => 'firstname',
                'multiple' => true,
            ])

            ->add('slug', TextType::class, [
                'help' => "Le groupe sera accessible à l'adresse $appUrl/group/$time-",
                'label' => 'URL du groupe',
                'attr' => [
                    'placeholder' => 'Mon super nom de groupe',
                    'data-time' => $time,
                    'data-appUrl' => $appUrl,
                ],
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Créer',
            ]);
    }
}
