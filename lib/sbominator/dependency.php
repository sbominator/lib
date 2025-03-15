<?php

namespace SBOMinator {
  class Dependency
  {

    public function __construct($version, $dependencies = [])
    {
      $this->version = $version;
      $this->dependencies = $dependencies;
    }

    public function get_version(): string
    {
      return $this->version;
    }

    private $version;

    public function get_dependencies():array
    {
      return $this->dependencies;
    }
    private $dependencies;
  }

}
?>