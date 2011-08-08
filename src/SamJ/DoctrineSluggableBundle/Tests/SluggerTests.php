<?php
namespace SamJ\DoctrineSluggableBundle\Tests\lib;

use SamJ\DoctrineSluggableBundle\Slugger;

class SlugglableTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckSluggable()
    {
    	$slugger = new Slugger();

    	//Spanish/Catalan character checks
        $this->assertEquals('aeiou', $slugger->getSlug('áéíóú'), 'Broad Accents');
        $this->assertEquals('aeiou', $slugger->getSlug('àèìòù'), 'backticks');
        $this->assertEquals('aeiou', $slugger->getSlug('äëïöü'), 'Dieresis');
        $this->assertEquals('l-l',   $slugger->getSlug('l·l'), 'Geminate');
        $this->assertEquals('n',     $slugger->getSlug('ñ'), 'Tilde');
        $this->assertEquals('cocinas-y-banos',     $slugger->getSlug('Cocinas y Baños'), 'Multiple Word');
        
        //string concatenation
        $this->assertEquals('main-title', $slugger->getSlug(array('Main', 'Title')), 'normal concatenation');
        $this->assertEquals('aeiou-title', $slugger->getSlug(array('áéíóú', 'Title')), 'accents concatenation');
    }
}
