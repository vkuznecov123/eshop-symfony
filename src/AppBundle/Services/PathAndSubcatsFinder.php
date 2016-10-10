<?php

namespace AppBundle\Services;


class PathAndSubcatsFinder
{
    private $path;
    private $subcats;

    private function getPathSubcats() {

    }

    public function getPath() {
       if(isset($this->path))
           return $this->path;

       $this->getPathSubcats();
    }
}