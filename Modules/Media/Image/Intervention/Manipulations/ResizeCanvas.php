<?php

namespace Modules\Media\Image\Intervention\Manipulations;

use Modules\Media\Image\ImageHandlerInterface;

class ResizeCanvas implements ImageHandlerInterface
{
    private $defaults = [
        'width' => 200,
        'height' => 200,
    ];

    /**
     * Handle the image manipulation request
     * @param  \Intervention\Image\Image $image
     * @param  array $options
     * @return \Intervention\Image\Image
     */
    public function handle($image, $options)
    {
        $options = array_merge($this->defaults, $options);

        $anchor = isset($options['anchor']) ? $options['anchor'] : 'center';
        $relative = isset($options['relative']) ? $options['relative'] : false;
        $bgcolor = isset($options['bgcolor']) ? $options['bgcolor'] : null;

        return $image->resizeCanvas($options['width'], $options['height'], $anchor, $relative, $bgcolor);
    }
}
