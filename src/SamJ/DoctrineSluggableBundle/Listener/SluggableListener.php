<?php

namespace SamJ\DoctrineSluggableBundle\Listener;

use SamJ\DoctrineSluggableBundle\SluggableInterface;
use SamJ\DoctrineSluggableBundle\SluggerInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 *
 * @TODO: Eliminate dependency on 'getId' from the entity from the interface
 */
class SluggableListener 
{
    protected $slugger;

    /**
     * @param Slugger $slugger
     */
    public function __construct(SluggerInterface $slugger) {
        $this->slugger = $slugger;
    }

	public function prePersist(LifecycleEventArgs $ea)
	{
		if ($ea->getEntity() instanceof SluggableInterface)
		{
			$entity = $ea->getEntity();
			$repository = $this->getRepository($entity, $ea->getEntityManager());

			$this->generateUniqueSlug($entity, $repository);
		}
	}

	protected function getRepository(SluggableInterface $entity, EntityManager $em)
	{
		return $em->getRepository(get_class($entity));
	}

    /**
     * @param \SamJ\DoctrineSluggableBundle\SluggableInterface $entity
     * @param \Doctrine\ORM\EntityRepository $repository
     * @return void
     *
     * @TODO: Remove the dependency on the field 'slug' on the entity (not in interface)
     * @TODO: Discuss whether the slug should auto-update if it is an 'update' (behaviour?)
     */
	protected function generateUniqueSlug(SluggableInterface $entity, EntityRepository $repository)
	{
		// Find a slug
        $eliminated = array(); // Our prior eliminated slugs
        $foundSlug = false;
		do {
            // Obtain our slug
            $slug = $this->slugger->getSlug($entity->getSlugFields(), $eliminated);

            // See if it is in our collection
            $result = $repository->findOneBy(array('slug' => $slug));

            // Check to see if we have found a slug that matches
            if(!empty($result) && $result->getId() != $entity->getId()) {
                $eliminated[] = $slug;
            } else {
                // We have found a slug for this element
                $foundSlug = true;
            }
		} while ($foundSlug === false);

        // Set the slug back to the entity
		$entity->setSlug($slug);
	}
}
