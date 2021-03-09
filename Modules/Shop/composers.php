<?php

view()->creator('shop::partials.sidebar-nav', \Modules\Shop\Composers\SidebarViewCreator::class);
 view()->composer('shop::layouts.*', \Modules\Shop\Composers\LocaleComposer::class);
view()->composer('shop::layouts.*', \Modules\Shop\Composers\CurrentUserViewComposer::class);
view()->composer('shop::layouts.*', \Modules\Shop\Composers\UserPermissionsComposer::class);
