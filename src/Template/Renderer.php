<?php declare ( strict_types = 1 );

namespace App\Template;

interface Renderer 
{
    public function render( $template, $data = []) : string;
}