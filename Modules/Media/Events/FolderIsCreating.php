<?php

namespace Modules\Media\Events;

use Modules\Mon\Contracts\EntityIsChanging;
use Modules\Mon\Events\AbstractEntityHook;

class FolderIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
