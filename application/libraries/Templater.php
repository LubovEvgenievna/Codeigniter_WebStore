<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Templater {

    protected $loader;
    protected $twig;

    public function __construct($path) {
        $this->loader = new Twig_Loader_Filesystem($path['views_folder_path']);
        $this->twig = new Twig_Environment($this->loader);
        $this->twig->addExtension(new Twig_Extensions_Extension_Text());
    }

    public function render($template, $data) {
        return $this->twig->render($template.'.twig', $data);
    }
}