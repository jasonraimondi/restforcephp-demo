<?php
namespace EventFarm\RestforceDemo\Twig;

use EventFarm\RestforceDemo\MarkdownParser;
use EventFarm\RestforceDemo\Twig\TemplateNamespace;
use EventFarm\RestforceDemo\Twig\TwigMarkdownExtension;
use Twig_Environment;
use Twig_Loader_Filesystem;

class TwigTemplateGenerator
{
    /** @var Twig_Environment */
    private $twigEnvironment;
    /** @var Twig_Loader_Filesystem */
    private $twigLoader;

    /**
     * @param TemplateNamespace[] $twigTemplateNamespaces
     */
    public function __construct(array $twigTemplateNamespaces)
    {
        $this->twigLoader = new Twig_Loader_Filesystem();

        foreach ($twigTemplateNamespaces as $templateNamespace) {
            $this->addTwigTemplatePath($templateNamespace);
        }

        $this->twigEnvironment = new Twig_Environment($this->twigLoader);
    }

    public static function createFromTemplateNamespace(TemplateNamespace $templateNamespace)
    {
        return new self([$templateNamespace]);
    }

    public function getTwigEnvironment()
    {
        return $this->twigEnvironment;
    }

    private function addTwigTemplatePath(TemplateNamespace $templateNamespace)
    {
        $this->twigLoader->addPath(
            $templateNamespace->getTemplatesPath(),
            $templateNamespace->getNamespace()
        );
    }
}
