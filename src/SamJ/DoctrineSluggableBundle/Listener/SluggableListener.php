<?php

namespace SamJ\DoctrineSluggableBundle\Listener;

use SamJ\DoctrineSluggableBundle\SluggableInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class SluggableListener 
{
	protected static $issuedSlugs = array();

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

	protected function generateUniqueSlug(SluggableInterface &$entity, EntityRepository $repository)
	{
		if (!isset(self::$issuedSlugs[get_class($entity)])) self::$issuedSlugs[get_class($entity)] = array();

		$slug = is_array($entity->getSlugFields()) ? implode('-', $entity->getSlugFields()) : $entity->getSlugFields();
		$slug = str_replace(' ', '-', strtolower($slug));
		$slug = preg_replace('/[^a-z0-9\-]+/', '', $slug);
		$slug = preg_replace('/[\-]+/', '-', $slug);
		$loops = 0;
		do
		{
			++$loops;
			$testSlug = $slug;
			if ($loops > 1) $testSlug .= '-' . $loops;

			$result = $repository->findOneBy(array('slug' => $testSlug));
		} while ((!empty($result) && $result->getId() != $entity->getId()) ||
		         in_array($testSlug, self::$issuedSlugs[get_class($entity)]));

		$entity->setSlug($testSlug);
		
		self::$issuedSlugs[get_class($entity)][] = $testSlug;
	}
}
