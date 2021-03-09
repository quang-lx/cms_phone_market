<?php

namespace Modules\Media\Events;

use Modules\Mon\Contracts\EntityIsChanging;
use Modules\Mon\Events\AbstractEntityHook;

final class FileIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
