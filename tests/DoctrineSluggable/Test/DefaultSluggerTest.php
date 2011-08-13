<?php

namespace DoctrineSluggable\Test;

use SamJ\DoctrineSluggableBundle\Slugger;

class DefaultSluggerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSlug()
    {
        $slugger = new Slugger();
        $this->assertEquals($slugger->getSlug('An Example'), 'an-example');
        $this->assertEquals($slugger->getSlug('AnExample'), 'anexample');
        $this->assertEquals($slugger->getSlug('The "Fancy-Example"'), 'the-fancy-example');
    }

    public function testGetSlugExcludeList()
    {
        $slugger = new Slugger();
        $this->assertEquals($slugger->getSlug('An Example', array('an-example')), 'an-example-1');
        $this->assertEquals($slugger->getSlug('An Example', array('an-example', 'an-example-1')), 'an-example-2');
        $this->assertEquals($slugger->getSlug('An Example', array('an-example', 'an-example-2')), 'an-example-1');
        $this->assertEquals($slugger->getSlug('An Example', array('an-example', 'an-example-1', 'an-example-3')), 'an-example-2');
    }
}
