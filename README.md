SamJDoctrineSluggableBundle
===========================

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
* Julien Brochet (mewt@madalynn.eu)

Documentation
-------------

The documentation is stored in the `Resources/doc/index.md` file in this bundle:

    Resources/doc/index.md

License
-------

This bundle is under the GNU LESSER GENERAL PUBLIC LICENSE. See the complete license in the bundle:

    Resources/meta/LICENSE