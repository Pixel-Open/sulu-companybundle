<?php

namespace Pixel\CompanyBundle\DependencyInjection;

use Sulu\Bundle\PersistenceBundle\DependencyInjection\PersistenceExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

class CompanyExtension extends Extension implements PrependExtensionInterface
{
    use PersistenceExtensionTrait;

    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension("sulu_admin")) {
            $container->prependExtensionConfig(
                "sulu_admin",
                [
                    "forms" => [
                        "directories" => [
                            __DIR__ . "/../Resources/config/forms",
                        ],
                    ],
                    "resources" => [
                        "company_settings" => [
                            "routes" => [
                                "detail" => "company.get_company-settings",
                            ],
                        ],
                    ],
                ]
            );
        }
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loaderYaml = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load("services.xml");
        $loaderYaml->load("services.yaml");
    }
}
