<?php
/**
 * <Description>
 *
 * @package   FMDB
 * @author    Marcus Stöhr <marcus.stoehr@filmmusik-info.de>
 * @copyright 2011 R&S Filmmusik Informations GbR (http://www.filmmusik-info.de)
 */
namespace SamJ\DoctrineSluggableBundle\Command;

use Symfony\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SlugDoctrineCommand extends DoctrineCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('doctrine:entity:slug')
            ->setDescription('Regenerate slugs for given entity.')
            ->setDefinition(array(
                new InputOption(
                    'entity',
                    '',
                    InputOption::VALUE_REQUIRED,
                    'FQCN for the entity to be regenerated.'
                )
            ))
            ->setHelp(<<<EOF
The <info>doctrine:entity:slug</info> command regenerates the slugs for an entity.

<info>php app/console doctrine:entity:slug --entity=Acme/DemoBundle/Entity/BlogPost</info>

Note that you can use <comment>/</comment> instead of <comment>\\</comment> for the namespace delimiter to avoid any
problem.

EOF
            )
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityName = $input->getOption('entity');

        if (!$entityName) {
            throw new \InvalidArgumentException('You must specify an entity.');
        }

        $entityName = str_replace('/', '\\', $entityName);

        $reflectionClass = new \ReflectionClass($entityName);
        if (!$reflectionClass->implementsInterface('SamJ\DoctrineSluggableBundle\SluggableInterface')) {
            throw new \InvalidArgumentException("Entity must implement 'SamJ\DoctrineSluggableBundle\SluggableInterface'");
        }

        /* @var $slugger \SamJ\DoctrineSluggableBundle\Slugger */
        $slugger = $this->getContainer()->get('sluggable.slugger');

        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $this->getEntityManager('default');

        $entities = $entityManager->getRepository($entityName)->findAll();
        foreach ($entities as $entity) {
            $entity->setSlug($slugger->getSlug($entity->getSlugFields()));
            $entityManager->persist($entity);
        }

        $entityManager->flush();

        $output->writeln('Successfully regenerated all slugs for ' . $entityName);
    }
}