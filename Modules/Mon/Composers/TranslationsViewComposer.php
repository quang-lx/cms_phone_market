<?php

namespace Modules\Mon\Composers;

use Illuminate\Contracts\View\View;
use Modules\Mon\Events\LoadingTranslations;

class TranslationsViewComposer
{
    public function compose(View $view)
    {
        event($staticTranslations = new LoadingTranslations());

        $view->with('staticTranslations', json_encode($staticTranslations->getTranslations()));
    }
}
