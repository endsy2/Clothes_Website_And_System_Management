<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // You can pass variables to your component here if needed
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        // This loads the Blade view located at resources/views/components/layout.blade.php
        return view('components.layout');
    }
}
