<?php

namespace Modules\Media\Events;

use Modules\Mon\Contracts\EntityIsChanging;
use Modules\Mon\Events\AbstractEntityHook;

class FolderIsUpdating extends AbstractEntityHook implements EntityIsChanging
{
}
