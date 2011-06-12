<?php

namespace SamJ\DoctrineSluggableBundle;

interface SluggableInterface
{
	public function setSlug($slug);
	public function getSlugFields();
}
