<?php

namespace Gestione\Cms\Content\Metadata\Registrator;

class Registry implements RegistryInterface
{
    protected $contentTypesFields = [];

    public function addRegistrator(RegistratorInterface $registrator)
    {
        $registrator->register($this);
    }

    public function addContentFields(string $contentType, ContentFieldsInterface $fields): RegistryInterface
    {
        $this->contentTypesFields[$contentType] = $fields;

        return $this;
    }

    public function getContentFields(string $contentType): ContentFieldsInterface
    {
        if(isset($this->contentTypesFields[$contentType]) === false)
            $this->contentTypesFields[$contentType] = new ContentFields;

        return $this->contentTypesFields[$contentType];
    }

    public function hasContentFields(string $contentType): bool
    {
        return array_key_exists($contentType, $this->contentTypesFields);
    }

    public function getContentTypes(): array
    {
        return array_keys($this->contentTypesFields);
    }
}
