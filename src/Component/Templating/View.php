<?php

namespace Gestione\Component\Templating;

class View
{
    protected $views = [];
    protected $data  = [];

    public function __construct($views, array $data = [])
    {
        if(is_array($views) === false)
            $views = [ $views ];

        $this->views = $views;
        $this->data  = $data;
    }

    public function getViews(): ?array
    {
        return $this->views;
    }

    public function setViews(array $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function addData(array $data)
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }
}
