<?php

namespace SamJ\DoctrineSluggableBundle;

interface SluggableInterface
{
    public function getId();
	public function setSlug($slug);
	public function getSlugFields();
}
