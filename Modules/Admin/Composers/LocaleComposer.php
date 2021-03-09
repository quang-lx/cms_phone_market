<?php

namespace Modules\Admin\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;

class LocaleComposer
{
    public function compose(View $view)
    {
        $view->with('currentLocale', App::getLocale());
    }
}
