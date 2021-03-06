DoctrineSluggableBundle
=======================

The DoctrineSluggableBundle provides a neat implementation for Doctrine2 sluggable behaviour for your entities.

* Simple behaviour for generating unique slugs for your entities
* Neatly done by implementing an interface
* Ensures slugs don't duplicate
* Support iconv/transliterate e.g. é -> e
* Uses dependency injection allowing you to implement custom slugger

The DoctrineSluggableBundle takes care of ensuring your slugs generated for your entity are unique. Simply have your entity implement the SluggableInterface interface your entities will automatically have slugs generated.

This uses the service container and dependency injection which allows you to easily create your own "Slugger" class. This supports you creating custom slugs to suit your domain problem.

This documentation is still under construction. However, an example is provided for any interested parties to begin experimenting with the package.

### Contributors
* Sam Jarrett (samjarrett@me.com)
* Denis Chartier (denis.chart+git@gmail.com)
* Cameron Manderson (cameronmanderson@gmail.com)

Installation
------------

Simply run assuming you have installed composer.phar or composer binary :

    $ php composer.phar require samj/doctrine-sluggable-bundle 2.0

Then add this in ``app/AppKernel.php`` :

    new SamJ\DoctrineSluggableBundle\SamJDoctrineSluggableBundle(),

  
Example Entities
----------------

### Example 1
In this example, the slug is built based on a single field:
Note: Make sure you implement the accessor methods getId and getTitle

#### Code
	<?php
	
	// --- YOUR NAMESPACE HERE ---
	namespace SamJ\ExampleBundle\Entity;
	
	use SamJ\DoctrineSluggableBundle\SluggableInterface;
	
	use Doctrine\ORM\Mapping as ORM;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table
	 */
	class SingleFieldExample implements SluggableInterface {
		/**
		 * @ORM\Id
		 * @ORM\Column(type="integer")
		 * @ORM\GeneratedValue
		 */
		protected $id;
	
		/**
		 * @ORM\Column(type="string")
		 */
		protected $title;
	
		/**
		 * @ORM\Column(type="string")
		 */
		protected $slug;
		
		// Implement methods for $id, $title, etc
			
		public function getSlug()
		{
			return $this->slug;
		}
	
		public function setSlug($slug)
		{
			if (!empty($this->slug)) return false;
			$this->slug = $slug;
		}
	
		public function getSlugFields() {
			return $this->getTitle();
		}
	}

#### Outcome
When the entity is persisted, the $slug field will be populated to be a 0-9, a-z only, with spaces converted to hyphens ("-"), based upon the title field.

i.e.: an entity with a title of `Test Post` will have a slug of `test-post`.

### Example 2
In this example, the slug is built based on multiple single fields:
Note: Make sure you implement the accessor methods getId, getTitle and getAuthor

#### Code
	<?php
	
	// --- YOUR NAMESPACE HERE ---
	namespace SamJ\ExampleBundle\Entity;
	
	use SamJ\DoctrineSluggableBundle\SluggableInterface;
	
	use Doctrine\ORM\Mapping as ORM;
	
	/**
	 * @ORM\Entity
	 * @ORM\Table
	 */
	class MultipleSingleFieldExample implements SluggableInterface {
		/**
		 * @ORM\Id
		 * @ORM\Column(type="integer")
		 * @ORM\GeneratedValue
		 */
		protected $id;
	
		/**
		 * @ORM\Column(type="string")
		 */
		protected $title;
	
		/**
		 * @ORM\Column(type="string")
		 */
		protected $author;
	
		/**
		 * @ORM\Column(type="string")
		 */
		protected $slug;
		
		// Implement methods for $id, $title, $author, etc
			
		public function getSlug()
		{
			return $this->slug;
		}
	
		public function setSlug($slug)
		{
			if (!empty($this->slug)) return false;
			$this->slug = $slug;
		}
	
		public function getSlugFields() {
			return array($this->getAuthor(), $this->getTitle());
		}
	}

#### Outcome
When the entity is persisted, the $slug field will be populated to be a 0-9, a-z only, with spaces converted to hyphens ("-"), based upon the author and title field.

i.e.: an entity with a author of `Sam Jarrett` and a title of `Test Post` will have a slug of `sam-jarrett-test-post`.

#### Further Notes
This bundle uses a service called the "Slugger". You can implement your own slugger behaviour (such as dealing with specific field ordering etc) by implementing the SluggerInterface->getSlug($fields) method. Configure your service container to specific the class in the parameter "sluggable.slugger.class" in your service.xml.

