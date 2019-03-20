<?php

/*
 * This file is part of the "default-project" package.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Greeflas\StaticAnalyzer\Analyzer;

use Greeflas\StaticAnalyzer\Helpers\ClassAnalyzeResult;
use Greeflas\StaticAnalyzer\Helpers\ClassMethods;
use Greeflas\StaticAnalyzer\Helpers\ClassProperties;

/**
 * Class for analizyng information about giving class
 *
 * @author Alexey Baranov <lekha.baranov@gmail.com>
 */
final class ClassAnalyzeExecutor
{
    const ABSTRACT_CLASS = 'abstract';
    const FINAL_CLASS = 'final';
    const DEFAULT_CLASS = 'default';

    /**
     * @var string fullname of class about which we need get info
     */
    private $reflection;


    public function __construct(string $fullClassName)
    {
        $this->reflection = $reflector = new \ReflectionClass($fullClassName);
    }

    /**
     * @param \ReflectionClass $reflector object of class ReflectionClass
     *
     * @return string get name of class
     */
    private function getClassInfo(): string
    {
        if ($this->reflection->isAbstract()) {
            $classType = self::ABSTRACT_CLASS;
        } elseif ($this->reflection->isFinal()) {
            $classType = self::FINAL_CLASS;
        } else {
            $classType = self::DEFAULT_CLASS;
        }

        return $classType;
    }

    /**
     * Get count information about properties of class
     *
     * @return ClassProperties
     */
    private function getClassPropertiesInfo(): ClassProperties
    {
        $properties = $this->reflection->getProperties();
        $propPublicCount = 0;
        $propProtectedCount = 0;
        $propPrivateCount = 0;
        $propPublicStaticCount = 0;
        $propProtectedStaticCount = 0;
        $propPrivateStaticCount = 0;
        $propertiesCountObject = new ClassProperties();

        foreach ($properties as $property) {
            if ($property->isPublic()) {
                $propPublicCount++;

                if ($property->isStatic()) {
                    $propPublicStaticCount++;
                }
            } elseif ($property->isProtected()) {
                $propProtectedCount++;

                if ($property->isStatic()) {
                    $propProtectedStaticCount++;
                }
            } elseif ($property->isPrivate()) {
                $propPrivateCount++;

                if ($property->isStatic()) {
                    $propPrivateStaticCount++;
                }
            }
        }

        $propertiesCountObject->propPrivate = $propPrivateCount;
        $propertiesCountObject->propPrivateStatic = $propPrivateStaticCount;
        $propertiesCountObject->propProtected = $propProtectedCount;
        $propertiesCountObject->propProtectedStatic = $propProtectedStaticCount;
        $propertiesCountObject->propPublic = $propPublicCount;
        $propertiesCountObject->propPublicStatic = $propPublicStaticCount;

        return $propertiesCountObject;
    }

    /**
     * Get count information about class methods
     *
     * @return ClassMethods
     */
    private function getClassMethodsInfo(): ClassMethods
    {
        $properties = $this->reflection->getMethods();
        $methodsPublicCount = 0;
        $methodsProtectedCount = 0;
        $methodsPrivateCount = 0;
        $methodsPublicStaticCount = 0;
        $methodsProtectedStaticCount = 0;
        $methodsPrivateStaticCount = 0;
        $methodsCountObject = new ClassMethods();

        foreach ($properties as $property) {
            if ($property->isPublic()) {
                $methodsPublicCount++;

                if ($property->isStatic()) {
                    $methodsPublicStaticCount++;
                }
            } elseif ($property->isProtected()) {
                $methodsProtectedCount++;

                if ($property->isStatic()) {
                    $methodsProtectedStaticCount++;
                }
            } elseif ($property->isPrivate()) {
                $methodsPrivateCount++;

                if ($property->isStatic()) {
                    $methodsPrivateStaticCount++;
                }
            }
        }

        $methodsCountObject->methodPrivate = $methodsPrivateCount;
        $methodsCountObject->methodPrivateStatic = $methodsPrivateStaticCount;
        $methodsCountObject->methodProtected = $methodsProtectedCount;
        $methodsCountObject->methodProtectedStatic = $methodsProtectedStaticCount;
        $methodsCountObject->methodPublic = $methodsPublicCount;
        $methodsCountObject->methodPublicStatic = $methodsPublicStaticCount;

        return $methodsCountObject;
    }

    /**
     *
     * Main methods, which analyze class information
     *
     * @return ClassAnalyzeResult
     */
    public function analyze(): ClassAnalyzeResult
    {
        $result = new ClassAnalyzeResult();

        $result->className = $this->reflection->getName();
        $result->classType = $this->getClassInfo();
        $result->classProperties = $this->getClassPropertiesInfo();
        $result->classMethods = $this->getClassMethodsInfo();

        return $result;
    }
}
