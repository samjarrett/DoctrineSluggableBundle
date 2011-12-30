<?php

namespace DoctrineSluggable\Test;

use SamJ\DoctrineSluggableBundle\Slugger;

class DefaultSluggerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSlug()
    {
        $slugger = new Slugger();
        $this->assertEquals($slugger->getSlug('An Example'), 'an-example', 'Simple behaviour to replace whitespace and lowercase');
        $this->assertEquals($slugger->getSlug('AnExample'), 'anexample', 'Case insensitivity');
        $this->assertEquals($slugger->getSlug('The "Fancy-Example"'), 'the-fancy-example', 'First set of special characters');
		$this->assertEquals($slugger->getSlug('A posessive person\'s nightmare'), 'a-posessive-persons-nightmare', 'Test apostrophe-s (\'s) slug creation');
    }

    public function testGetSlugExcludeList()
    {
        $slugger = new Slugger();
        $this->assertEquals($slugger->getSlug('An Example', array('an-example')), 'an-example-1', 'Create unique if appears in exclude list');
        $this->assertEquals($slugger->getSlug('An Example', array('an-example', 'an-example-1')), 'an-example-2', 'Ensure correct sequencing if exclude list includes first unique attempt');
        $this->assertEquals($slugger->getSlug('An Example', array('an-example', 'an-example-2')), 'an-example-1', 'Find the first unique e.g. -1 if available');
        $this->assertEquals($slugger->getSlug('An Example', array('an-example', 'an-example-1', 'an-example-3')), 'an-example-2', 'Find the first unique if -1 is available and -3 is taken');
    }

    public function testGetSlugIconV()
    {
        $slugger = new Slugger();
        $this->assertEquals($slugger->getSlug('An éxample'), 'an-example', 'Transliterate é to e');
        // @TODO: Create more examples of transliterate
    }
}
