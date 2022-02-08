<?php

namespace Jezzdk\NovaKontainer;

use Laravel\Nova\Fields\Field;

class NovaKontainer extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-kontainer';

    public $kontainerUrl = null;

    public function kontainerUrl($url)
    {
        return $this->withMeta(['kontainerUrl' => $url]);
    }
}
