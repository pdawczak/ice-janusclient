<?php

namespace Ice\DoctrineMockOfJanusClientBundle\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ReplaceServicePass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameterBag()->has('mock_janus_client_entity_manager')) {
            $emServiceName = $container->getParameter('doctrine.orm.default_entity_manager');
        } else {
            $emServiceName = 'doctrine.orm.default_entity_manager';
        }

        $container->addDefinitions(
            [
                'ice.janus_client.doctrine_mock_of_guzzle_client' => new Definition(
                    'Ice\DoctrineMockOfJanusClientBundle\MockClient\MockGuzzleClient',
                    [new Reference($emServiceName)]
                )
            ]
        );

        $definition = $container->getDefinition('janus.client');
        $definition->setClass('Ice\DoctrineMockOfJanusClientBundle\MockClient\MockGuzzleClient');
        $constructorArguments[0] = new Reference($emServiceName);
        $definition->setArguments($constructorArguments);
    }
}
