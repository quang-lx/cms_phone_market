<?php

namespace Modules\Mon\Contracts;

interface EntityIsChanging
{
    /**
     * Get the attributes used to create or modify an entity
     * @return array
     */
    public function getAttributes();
    /**
     * Set the attributes used to create or modify an entity
     * @param array $attributes
     */
    public function setAttributes(array $attributes);
    /**
     * Get the original attributes untouched by other listeners
     * @return array
     */
    public function getOriginal();
}
