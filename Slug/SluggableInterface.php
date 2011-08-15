<?php

namespace SamJ\DoctrineSluggableBundle\Slug;

interface SluggableInterface
{
	public function setSlug($slug);
	public function getSlugFields();
}
