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

    public function onlyImages()
    {
        return $this->withMeta(['allowType' => 'images']);
    }

    public function onlyVideos()
    {
        return $this->withMeta(['allowType' => 'videos']);
    }

    public function onlyFiles()
    {
        return $this->withMeta(['allowType' => 'files']);
    }
}
