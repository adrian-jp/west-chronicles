<?php


namespace App\Twig;


use JMS\Serializer\SerializationContext;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class Extension extends AbstractExtension implements GlobalsInterface
{

    /**
     * @inheritDoc
     */
    public function getGlobals()
    {
        return [];
    }

    /**
     * @return array|\Twig\TwigFilter[]|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_Filter('instanceOf', [$this, 'instanceOf']),
            new \Twig_Filter('serializeObject', [$this, 'serializeObject']),
        ];
    }

    /**
     * Serialise un objet avec jms
     *
     * @param object $object
     * @return string
     */
    public function serializeObject(object $object)
    {
        $serializeContext = new SerializationContext();
        $serializeContext->setGroups(["twig","twig_extrat"]);
        $serializeContext->enableMaxDepthChecks();

        return $this->serializer->serialize($object, "json", $serializeContext);
    }

    /**
     * Retour vrai si l'objet est de type class
     *
     * @param object|string $object
     * @param string $class
     * @return bool
     * @throws \ReflectionException
     */
    public function instanceOf ($object, string $class)
    {
        if (is_string($object)|| is_null($object)){
            return false;
        }
        $reflectionClass = new \ReflectionClass($class);

        return $reflectionClass->isInstance($object);
    }
}