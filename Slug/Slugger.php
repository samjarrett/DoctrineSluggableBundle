<?php
namespace SamJ\DoctrineSluggableBundle\Slug;

/**
 * Object Service that generates a slug based on incoming fields
 * e.g. "My Test" will return "my-test" ("my-test-1" on duplicate)
 *
 * @author camm (cameronmanderson@gmail.com)
 */
class Slugger implements SluggerInterface
{
    /**
    * Return a slug, ensuring it does not appear in exclude (prior collisions)
    * @param $fields
    * @param array $exclude list of slugs to exclude
    * @return void
    * Reference: Doctrine 1_4 slugify
    */
    public function getSlug($fields, $exclude = array())
    {
        if (is_array($fields)) {
            $fields = implode('-', $fields);
        }

        $slug = preg_replace('~[^\\pL\d]+~u', '-', $fields);
        $slug = trim($slug, '-');

        if (function_exists('iconv')) {
            $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        }

        $slug = strtolower($slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);

        // Fall-back
        if (!trim($slug)) {
            $slug = 'n-a';
        }

        $loop = 1;
        $test = $slug;
        while(in_array($test, $exclude)) {
            $test = $slug . ('-' . $loop++);
        }

        return $test;
    }
}
