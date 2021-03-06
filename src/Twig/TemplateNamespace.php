<?php
namespace EventFarm\RestforceDemo\Twig;

class TemplateNamespace
{
    /** @var string */
    private $templatesPath;
    /** @var string */
    private $namespace;

    public function __construct(string $templatesPath, string $namespace)
    {
        $this->templatesPath = $templatesPath;
        $this->namespace = $namespace;
    }

    public function getTemplatesPath(): string
    {
        return $this->templatesPath;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }
}
