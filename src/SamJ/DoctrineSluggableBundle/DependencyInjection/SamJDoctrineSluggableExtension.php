<?php

namespace SamJ\DoctrineSluggableBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class SamJDoctrineSluggableExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);

		$loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.xml');

		foreach (array('word_separator', 'field_separator') as $attribute) {
			if (isset($config[$attribute])) {
				$container->setParameter('sluggable.slugger.' . $attribute, $config[$attribute]);
			}
		}
	}

	public function getAlias()
	{
		return 'sam_j_doctrine_sluggable';
	}
}
