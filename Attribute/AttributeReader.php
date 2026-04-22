<?php

namespace FS\SolrBundle\Attribute;

use FS\SolrBundle\Attribute\Document;
use FS\SolrBundle\Attribute\Field;
use FS\SolrBundle\Attribute\Id;
use FS\SolrBundle\Doctrine\Mapper\MappingDriver;
use FS\SolrBundle\Doctrine\Mapper\MappingDriverException;
use FS\SolrBundle\Doctrine\Mapper\SolrMappingException;

/**
 * This class reads native attributes instead of using
 * Doctrine\Common\Annotations\Reader
 * to create FS\SolrBundle\Attribute instances.
 */
class AttributeReader implements MappingDriver
{
    /**
     * @var array
     */
    private $entityProperties;

    const DOCUMENT_CLASS = 'FS\SolrBundle\Attribute\Document';
    const DOCUMENT_NESTED_CLASS = 'FS\SolrBundle\Attribute\Nested';
    const FIELD_CLASS = 'FS\SolrBundle\Attribute\Field';
    const FIELD_IDENTIFIER_CLASS = 'FS\SolrBundle\Attribute\Id';
    const SYNCHRONIZATION_FILTER_CLASS = 'FS\SolrBundle\Attribute\SynchronizationFilter';

    /**
     * reads the entity and returns a set of annotations
     *
     * @param object $entity
     * @param string $type
     *
     * @return Attribute[]
     */
    private function getPropertiesByType($entity, $type)
    {
        $properties = $this->readClassProperties($entity);

        $fields = [];
        foreach ($properties as $property) {
            $attributes = $property->getAttributes($type);

            if (count($attributes) == 0) {
                continue;
            }

            $annotation = new $type($attributes[0]->getArguments());

            $property->setAccessible(true);
            $annotation->value = $property->getValue($entity);
            $annotation->name = $property->getName();

            $fields[] = $annotation;
        }

        return $fields;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     *
     * @return \ReflectionProperty[]
     */
    private function getParentProperties(\ReflectionClass $reflectionClass)
    {
        $parent = $reflectionClass->getParentClass();
        if ($parent != null) {
            return array_merge($reflectionClass->getProperties(), $this->getParentProperties($parent));
        }

        return $reflectionClass->getProperties();
    }

    /**
     * @param object $entity
     *
     * @return array
     */
    public function getFields($entity)
    {
        return $this->getPropertiesByType($entity, self::FIELD_CLASS);
    }

    /**
     * @param object $entity
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public function getMethods($entity)
    {
        $reflectionClass = new \ReflectionClass($entity);

        $methods = [];
        foreach ($reflectionClass->getMethods() as $method) {
            /** @var Field $annotation */
            $attributes = $method->getAttributes(self::FIELD_CLASS);

            if (count($attributes) == 0) {
                continue;
            }

            $type = self::FIELD_CLASS;
            $annotation = new $type($attributes[0]->getArguments());

            $annotation->value = $method->invoke($entity);

            if ($annotation->name == '') {
                throw new SolrMappingException(sprintf('Please configure a field-name for method "%s" with field-annotation in class "%s"', $method->getName(), get_class($entity)));
            }

            $methods[] = $annotation;
        }

        return $methods;
    }

    /**
     * @param object $entity
     *
     * @return number
     *
     * @throws MappingDriverException if the boost value is not numeric
     */
    public function getEntityBoost($entity)
    {
        $annotation = $this->getClassAnnotation($entity, self::DOCUMENT_CLASS);

        if (!$annotation instanceof Document) {
            return 0;
        }

        $boostValue = $annotation->getBoost();
        if (!is_numeric($boostValue)) {
            throw new MappingDriverException(sprintf('Invalid boost value "%s" in class "%s" configured', $boostValue, get_class($entity)));
        }

        if ($boostValue === 0) {
            return null;
        }

        return $boostValue;
    }

    /**
     * @param object $entity
     *
     * @return string
     */
    public function getDocumentIndex($entity)
    {
        $annotation = $this->getClassAnnotation($entity, self::DOCUMENT_CLASS);
        if (!$annotation instanceof Document) {
            return null;
        }

        $indexHandler = $annotation->indexHandler;
        if ($indexHandler != '' && method_exists($entity, $indexHandler)) {
            return $entity->$indexHandler();
        }

        return $annotation->getIndex();
    }

    /**
     * @param object $entity
     *
     * @return Id
     *
     * @throws MappingDriverException if given $entity has no identifier
     */
    public function getIdentifier($entity)
    {
        $id = $this->getPropertiesByType($entity, self::FIELD_IDENTIFIER_CLASS);

        if (count($id) == 0) {
            throw new MappingDriverException('no identifer declared in entity ' . get_class($entity));
        }

        return reset($id);
    }

    /**
     * @param object $entity
     *
     * @return string classname of repository
     */
    public function getRepository($entity)
    {
        $annotation = $this->getClassAnnotation($entity, self::DOCUMENT_CLASS);

        if ($annotation instanceof Document) {
            return $annotation->repository;
        }

        return '';
    }

    /**
     * returns all fields and field for identification
     *
     * @param object $entity
     *
     * @return array
     */
    public function getFieldMapping($entity)
    {
        $fields = $this->getPropertiesByType($entity, self::FIELD_CLASS);

        $mapping = [];
        foreach ($fields as $field) {
            $mapping[$field->getNameWithAlias()] = $field->name;
        }

        $id = $this->getIdentifier($entity);
        $mapping['id'] = $id->name;

        return $mapping;
    }

    /**
     * @param object $entity
     *
     * @return boolean
     */
    public function hasDocumentDeclaration($entity)
    {
        if ($rootDocument = $this->getClassAnnotation($entity, self::DOCUMENT_CLASS)) {
            return true;
        }

        if ($this->isNested($entity)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $entity
     *
     * @return string
     */
    public function getSynchronizationCallback($entity)
    {
        $annotation = $this->getClassAnnotation($entity, self::SYNCHRONIZATION_FILTER_CLASS);

        if (!$annotation) {
            return '';
        }

        return $annotation->callback;
    }

    /**
     * @param object $entity
     *
     * @return bool
     */
    public function isOrm($entity)
    {
        $annotation = $this->getClassAnnotation($entity, 'Doctrine\ORM\Mapping\Entity');

        if ($annotation === null) {
            return false;
        }

        return true;
    }

    /**
     * @param object $entity
     *
     * @return bool
     */
    public function isOdm($entity)
    {
        $annotation = $this->getClassAnnotation($entity, 'Doctrine\ODM\MongoDB\Mapping\Annotations\Document');

        if ($annotation === null) {
            return false;
        }

        return true;
    }

    /**
     * @param object $entity
     *
     * @return bool
     */
    public function isNested($entity)
    {
        if ($nestedDocument = $this->getClassAnnotation($entity, self::DOCUMENT_NESTED_CLASS)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $entity
     * @param string $annotationName
     *
     * @return Annotation|null
     */
    private function getClassAnnotation($entity, $annotationName)
    {
        $reflectionClass = new \ReflectionClass($entity);

        $attributes = $reflectionClass->getAttributes($annotationName);

        if (count($attributes) == 0 && $reflectionClass->getParentClass()) {
            $attributes = $reflectionClass->getParentClass()->getAttributes($annotationName);
        }

        if (count($attributes) == 0) {
            return null;
        }

        $constructorExpectsScalar = in_array($annotationName, [
            'Doctrine\ORM\Mapping\Entity',
            'Doctrine\ODM\MongoDB\Mapping\Annotations\Document',
        ]);

        if ($constructorExpectsScalar) {
            $annotation = new $annotationName(... $attributes[0]->getArguments());

        }
        else {
            $annotation = new $annotationName($attributes[0]->getArguments());
        }

        return $annotation;
    }

    /**
     * @param object $entity
     *
     * @return \ReflectionProperty[]
     */
    private function readClassProperties($entity)
    {
        $className = get_class($entity);
        if (isset($this->entityProperties[$className])) {
            return $this->entityProperties[$className];
        }

        $reflectionClass = new \ReflectionClass($entity);
        $inheritedProperties = array_merge($this->getParentProperties($reflectionClass), $reflectionClass->getProperties());

        $properties = [];
        foreach ($inheritedProperties as $property) {
            $properties[$property->getName()] = $property;
        }

        $this->entityProperties[$className] = $properties;

        return $properties;
    }
}
