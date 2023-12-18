<?php

namespace App\UseCase\Group\Create;

use App\Entity\Group;
use App\Repository\PersonRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class Handler
{
    public function __construct(private EntityManagerInterface $entityManagerInterface, private PersonRepository $personRepository)
    {
    }

    public function __invoke(Input $input): Output
    {
        $slugify = new Slugify();

        $label = $input->label;
        $description = $input->description;

        $persons = $this->personRepository->findAll();

        if (0 === count($persons)) {
            throw new \Exception('Could not get the beneficiary list');
        }

        $group = new Group($label, $persons, $description);

        $group->setSlug(sprintf(
            '%s-%s',
            time(),
            $slugify->slugify($group->getSlug())
        ));

        $this->entityManagerInterface->persist($group);
        $this->entityManagerInterface->flush();

        return new Output($group);
    }
}
