<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link           http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright      Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license        http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Hydrator\Filter;

use Hydrator\Context\ExtractionContext;
use Hydrator\Exception\InvalidArgumentException;
use ReflectionException;
use ReflectionMethod;

/**
 * This filter accepts any method that have only optional parameters
 */
final class OptionalParametersFilter implements FilterInterface
{
    /**
     * Map of methods already analyzed by {@see \Zend\Hydrator\Filter\OptionalParametersFilter::filter()},
     * cached for performance reasons
     *
     * @var array|bool[]
     */
    private static $propertiesCache = [];

    /**
     * {@inheritDoc}
     */
    public function accept($property, ExtractionContext $context = null)
    {
        if (isset(self::$propertiesCache[$property])) {
            return self::$propertiesCache[$property];
        }

        try {
            $reflectionMethod = new ReflectionMethod($context->object, $property);
        } catch (ReflectionException $exception) {
            throw new InvalidArgumentException(sprintf('Method "%s" does not exist', $property), 0, $exception);
        }

        return self::$propertiesCache[$property] = ($reflectionMethod->getNumberOfRequiredParameters() === 0);
    }
}
