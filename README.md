DoctrineSluggableBundle
=======================

The DoctrineSluggableBundle provides a neat implementation of sluggable behaviour for your entities.

By having your entity implement the SluggableInterface interface your entities will automatically have slugs generated.

You can also extend out how your slugs are presented by creating your own "Slugger" class, such as MySlugger, so you can generate some fancy variations for much neater and domain specific slugs. This is wired in using Symfony2's service container.

This documentation is still under construction. However, an example is provided for any interested parties to begin experimenting with the package.

### Contributors
* Sam Jarrett (samjarrett@me.com)
* Denis Chartier (denis.chart+git@gmail.com)
* Cameron Manderson (cameronmanderson@gmail.com)
* Joskfg (joskfg@gmail.com)

Installation
------------

Add this inside ``deps`` file in root Symfony2 project :

    [DoctrineSluggableBundle]
        git=https://github.com/samjarrett/DoctrineSluggableBundle.git
        
Update your bundles versioned by git :

    php bin/vendors install
    
Then add this in ``app/AppKernel.php`` :

    new SamJ\DoctrineSluggableBundle\SamJDoctrineSluggableBundle(),
  
Finally, add this in ``app/autoload.php`` :

    'SamJ' => __DIR__.'/../vendor/DoctrineSluggableBundle/src',
  
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

