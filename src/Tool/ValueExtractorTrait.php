<?php
declare(strict_types=1);

namespace Ramsey\Collection\Tool;

use Ramsey\Collection\Exception\ValueExtractionException;

trait ValueExtractorTrait
{
    /**
     * @param mixed  $object
     * @param string $propertyOrMethod
     *
     * @return mixed
     */
    protected function extractValue($object, string $propertyOrMethod)
    {
        if (\property_exists($object, $propertyOrMethod)) {
            return $object->$propertyOrMethod;
        }

        if (\method_exists($object, $propertyOrMethod)) {
            return $object->{$propertyOrMethod}();
        }

        throw new ValueExtractionException(
            sprintf('Method or property "%s" not defined in %s', $propertyOrMethod, \get_class($object))
        );
    }
}
