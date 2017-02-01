<?php

/*
 * @copyright  Copyright (c) 2016, Net Inventors GmbH
 * @category   Shopware
 * @author     Net Inventors GmbH
 *
 */

namespace NetiToolKit;

use NetiToolKit\CompilerPasses\EmotionComponentPass;
use Shopware\Components\Emotion\ComponentInstaller;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NetiToolKit extends Plugin
{
    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        $emotionInstaller = $this->container->get(
            'shopware.emotion_component_installer',
            ContainerInterface::NULL_ON_INVALID_REFERENCE
        );

        if ($emotionInstaller instanceof ComponentInstaller) {
            $this->createEmotionComponent($emotionInstaller);
        }
    }

    /**
     * @param ComponentInstaller $emotionInstaller
     */
    private function createEmotionComponent(ComponentInstaller $emotionInstaller)
    {
        $customCodeElement = $emotionInstaller->createOrUpdate(
            $this->getName(),
            'Custom HTML/JS',
            [
                'name'        => 'ToolKit Custom Code',
                'template'    => 'neti_tool_kit_emotion_custom',
                'cls'         => 'emotion-tool_kit-element',
                'description' => 'Custom HTML/JS Code that will be output as-is in the emotion element.',
            ]
        );

        $customCodeElement->createTextAreaField(
            [
                'name'        => 'html_code',
                'fieldLabel'  => 'Code:',
                'supportText' => 'Enter HTML/JS Code here, it will be not be altered in any way.',
                'allowBlank'  => false,
            ]
        );
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        // avoid DI errors in Shopware < 5.2.10
        $container->addCompilerPass(new EmotionComponentPass());

        parent::build($container);
    }
}
