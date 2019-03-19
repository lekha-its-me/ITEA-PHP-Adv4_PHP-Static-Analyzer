<?php

namespace Greeflas\StaticAnalyzer\Analyzer;

/**
 *
 *
 * @author Alexey Baranov <lekha.baranov@gmail.com>
 */
final class ClassAnalyzeExecutor
{
    /**
     * @var string fullname of class about which we need get info
     */
    private $fullClassName;

    /**
     * ClassAnalyzeExecutor constructor.
     * @param string $fullClassName
     */
    public function __construct(string $fullClassName)
    {
        $this->fullClassName = $fullClassName;
    }

    /**
     * @param \ReflectionClass $reflector object of class ReflectionClass
     * @return string get name of class
     */
    private function getClassInfo(\ReflectionClass $reflector): string
    {
        if ($reflector->isAbstract())
        {
            $classType = 'abstract';
        }
        elseif ($reflector->isFinal())
        {
            $classType = 'final';
        }
        else
        {
            $classType = 'default';
        }

        return $classType;
    }

    /**
     * @param \ReflectionClass $reflector
     * @return array get count information about methods of class
     */
    private function getClassPropertiesInfo(\ReflectionClass $reflector): array
    {
        $properties = $reflector->getProperties();
        $propPublicCount = 0;
        $propProtectedCount = 0;
        $propPrivateCount = 0;
        $propPublicStaticCount = 0;
        $propProtectedStaticCount = 0;
        $propPrivateStaticCount = 0;
        $propertiesCountArray = [];

        foreach ($properties as $property)
        {
            if ($property->isPublic())
            {
                $propPublicCount++;
                if ($property->isStatic())
                {
                    $propPublicStaticCount++;
                }
            }
            elseif ($property->isProtected())
            {
                $propProtectedCount++;
                if ($property->isStatic())
                {
                    $propProtectedStaticCount++;
                }
            }
            elseif ($property->isPrivate())
            {
                $propPrivateCount++;
                if ($property->isStatic())
                {
                    $propPrivateStaticCount++;
                }
            }
        }
        $propertiesCountArray['public'] = [
           'count' =>  $propPublicCount,
           'countStatic' =>  $propPublicStaticCount
        ];
        $propertiesCountArray['protected'] = [
            'count' =>  $propProtectedCount,
            'countStatic' =>  $propProtectedStaticCount
        ];
        $propertiesCountArray['private'] = [
            'count' =>  $propPrivateCount,
            'countStatic' =>  $propPrivateStaticCount
        ];

        return $propertiesCountArray;
    }

    /**
     * @param \ReflectionClass $reflector
     * @return array count information about class methods
     */
    private function getClassMethodsInfo(\ReflectionClass $reflector): array
    {
        $properties = $reflector->getMethods();
        $methodsPublicCount = 0;
        $methodsProtectedCount = 0;
        $methodsPrivateCount = 0;
        $methodsPublicStaticCount = 0;
        $methodsProtectedStaticCount = 0;
        $methodsPrivateStaticCount = 0;
        $methodsCountArray = [];

        foreach ($properties as $property)
        {
            if ($property->isPublic())
            {
                $methodsPublicCount++;
                if ($property->isStatic())
                {
                    $methodsPublicStaticCount++;
                }
            }
            elseif ($property->isProtected())
            {
                $methodsProtectedCount++;
                if ($property->isStatic())
                {
                    $methodsProtectedStaticCount++;
                }
            }
            elseif ($property->isPrivate())
            {
                $methodsPrivateCount++;
                if ($property->isStatic())
                {
                    $methodsPrivateStaticCount++;
                }
            }
        }
        $methodsCountArray['public'] = [
            'count' =>  $methodsPublicCount,
            'countStatic' =>  $methodsPublicStaticCount
        ];
        $methodsCountArray['protected'] = [
            'count' =>  $methodsProtectedCount,
            'countStatic' =>  $methodsProtectedStaticCount
        ];
        $methodsCountArray['private'] = [
            'count' =>  $methodsPrivateCount,
            'countStatic' =>  $methodsPrivateStaticCount
        ];

        return $methodsCountArray;
    }

    /**
     *
     * Main maethods, which analyze class information
     *
     * @return array
     */
    public function analyze(): array
    {
        $result = [];
        try {
            $reflector = new \ReflectionClass($this->fullClassName);
        } catch (\ReflectionException $e) {

        }

        $result['className'] = $reflector->getName();
        $result['classType'] = self::getClassInfo($reflector);
        $result['classProperties'] = self::getClassPropertiesInfo($reflector);
        $result['classMethods'] = self::getClassMethodsInfo($reflector);

        return $result;
    }
}
