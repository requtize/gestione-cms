<?php

namespace Gestione\Cms\Content\Metadata\Registrator;

class ContentFields implements ContentFieldsInterface
{
    protected $fields = [];

    public function add(array $field): ContentFieldsInterface
    {
        $field = array_merge([
            'name' => null,
            // Default value
            'default' => null,
            'multilingual' => true,
            // One of available: text, array, datetime
            'datatype' => 'text',
        ], $field);

        $this->fields[$field['name']] = $field;

        return $this;
    }

    public function remove(string $field): ContentFieldsInterface
    {
        unset($this->fields[$field['name']]);

        return $this;
    }

    public function empty(): ContentFieldsInterface
    {
        $this->fields = [];

        return $this;
    }

    public function count(): int
    {
        return count($this->fields);
    }

    public function getNames(): array
    {
        return array_keys($this->fields);
    }

    public function all(): array
    {
        return $this->fields;
    }
}
