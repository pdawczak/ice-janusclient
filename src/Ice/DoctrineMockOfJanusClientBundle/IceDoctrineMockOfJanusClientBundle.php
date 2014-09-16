<?php

namespace Ice\DoctrineMockOfJanusClientBundle;

use Ice\DoctrineMockOfJanusClientBundle\CompilerPass\ReplaceServicePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IceDoctrineMockOfJanusClientBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ReplaceServicePass());
        parent::build($container);
    }
}
