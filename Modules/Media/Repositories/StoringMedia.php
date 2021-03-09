<?php

namespace Modules\Media\Repositories;

interface StoringMedia
{
    /**
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity();

    /**
     * Return the ALL data sent
     * @return array
     */
    public function getSubmissionData();
}
