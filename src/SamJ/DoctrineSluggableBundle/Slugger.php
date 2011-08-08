<?php
namespace SamJ\DoctrineSluggableBundle;

use SamJ\DoctrineSluggableBundle\SluggerInterface;

/**
 * Object Service that generates a slug based on incoming fields
 * e.g. "My Test" will return "my-test" ("my-test-1" on duplicate)
 *
 * @author camm (cameronmanderson@gmail.com)
 */
class Slugger implements SluggerInterface {

	/**
	 * Return a slug, ensuring it does not appear in exclude (prior collisions)
	 * @param $fields
	 * @param array $exclude list of slugs to exclude
	 * @return void
	 * Reference: Doctrine 1_4 slugify
	 */
	public function getSlug($fields, $exclude = array())
	{
		// Determine if we are dealing with single-field or multiple-field slugs
		if (is_array($fields)) {
			$value = implode('-', $fields);
		} else {
			$value = $fields;
		}

		// Treat the data (eliminate non-letter or digits by '-'
		$slug = preg_replace('~[^\\pL\d]+~u', '-', $value);

		// Clean up the slug
		$slug = trim($slug, '-');

		// Translate
		if (function_exists('iconv')) {
			//$slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
		}

		// Lowercase
		$slug = strtolower($slug);

		// Remove unwanted characters
		$slug = preg_replace('~[^-\w]+~', '', $slug);

		// Fall-back to produce something
		if (!trim($slug)) $slug = 'n-a';

		// Append an index to the slug and see if we can generate a unique value
		$loop = 1;
        $test = $slug;
		while(in_array($test, $exclude)) $test = $slug . ($loop . '-' . ++$loop);
		$slug = $test;

		// We have our unique slug suggestion
		return $slug;
	}
}
