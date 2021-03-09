<?php

view()->creator('backend::partials.sidebar-nav', \Modules\Admin\Composers\SidebarViewCreator::class);
 view()->composer('backend::layouts.*', \Modules\Admin\Composers\LocaleComposer::class);
view()->composer('backend::layouts.*', \Modules\Admin\Composers\CurrentUserViewComposer::class);
view()->composer('backend::layouts.*', \Modules\Admin\Composers\UserPermissionsComposer::class);
