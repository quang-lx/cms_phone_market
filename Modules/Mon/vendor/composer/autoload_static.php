<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9639ca0ecb7103e706951bdfb72b04af
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\Mon\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\Mon\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Modules\\Mon\\Auth\\AccessTokenGuard' => __DIR__ . '/../..' . '/Auth/AccessTokenGuard.php',
        'Modules\\Mon\\Auth\\Authentication' => __DIR__ . '/../..' . '/Auth/Authentication.php',
        'Modules\\Mon\\Auth\\Contracts\\Authentication' => __DIR__ . '/../..' . '/Auth/Contracts/Authentication.php',
        'Modules\\Mon\\Composers\\TranslationsViewComposer' => __DIR__ . '/../..' . '/Composers/TranslationsViewComposer.php',
        'Modules\\Mon\\Console\\EntityScaffoldCommand' => __DIR__ . '/../..' . '/Console/EntityScaffoldCommand.php',
        'Modules\\Mon\\Console\\Theme' => __DIR__ . '/../..' . '/Console/Theme.php',
        'Modules\\Mon\\Contracts\\EntityIsChanging' => __DIR__ . '/../..' . '/Contracts/EntityIsChanging.php',
        'Modules\\Mon\\Entities\\Category' => __DIR__ . '/../..' . '/Entities/Category.php',
        'Modules\\Mon\\Entities\\News' => __DIR__ . '/../..' . '/Entities/News.php',
        'Modules\\Mon\\Entities\\Profile' => __DIR__ . '/../..' . '/Entities/Profile.php',
        'Modules\\Mon\\Entities\\User' => __DIR__ . '/../..' . '/Entities/User.php',
        'Modules\\Mon\\Events\\AbstractEntityHook' => __DIR__ . '/../..' . '/Events/AbstractEntityHook.php',
        'Modules\\Mon\\Events\\LoadingTranslations' => __DIR__ . '/../..' . '/Events/LoadingTranslations.php',
        'Modules\\Mon\\Events\\UserWasCreated' => __DIR__ . '/../..' . '/Events/UserWasCreated.php',
        'Modules\\Mon\\Events\\UserWasDeleting' => __DIR__ . '/../..' . '/Events/UserWasDeleting.php',
        'Modules\\Mon\\Events\\UserWasUpdated' => __DIR__ . '/../..' . '/Events/UserWasUpdated.php',
        'Modules\\Mon\\Http\\Controllers\\AdminController' => __DIR__ . '/../..' . '/Http/Controllers/AdminController.php',
        'Modules\\Mon\\Http\\Controllers\\ApiController' => __DIR__ . '/../..' . '/Http/Controllers/ApiController.php',
        'Modules\\Mon\\Http\\Controllers\\Auth\\ForgotPasswordController' => __DIR__ . '/../..' . '/Http/Controllers/Auth/ForgotPasswordController.php',
        'Modules\\Mon\\Http\\Controllers\\Auth\\LoginController' => __DIR__ . '/../..' . '/Http/Controllers/Auth/LoginController.php',
        'Modules\\Mon\\Http\\Controllers\\Auth\\RegisterController' => __DIR__ . '/../..' . '/Http/Controllers/Auth/RegisterController.php',
        'Modules\\Mon\\Http\\Controllers\\Auth\\ResetPasswordController' => __DIR__ . '/../..' . '/Http/Controllers/Auth/ResetPasswordController.php',
        'Modules\\Mon\\Http\\Controllers\\Auth\\VerificationController' => __DIR__ . '/../..' . '/Http/Controllers/Auth/VerificationController.php',
        'Modules\\Mon\\Http\\Controllers\\BaseController' => __DIR__ . '/../..' . '/Http/Controllers/BaseController.php',
        'Modules\\Mon\\Http\\Controllers\\MonController' => __DIR__ . '/../..' . '/Http/Controllers/MonController.php',
        'Modules\\Mon\\Http\\Controllers\\WebController' => __DIR__ . '/../..' . '/Http/Controllers/WebController.php',
        'Modules\\Mon\\Http\\Middleware\\Admin' => __DIR__ . '/../..' . '/Http/Middleware/Admin.php',
        'Modules\\Mon\\Http\\Middleware\\ApiPermission' => __DIR__ . '/../..' . '/Http/Middleware/ApiPermission.php',
        'Modules\\Mon\\Http\\Middleware\\ApiRole' => __DIR__ . '/../..' . '/Http/Middleware/ApiRole.php',
        'Modules\\Mon\\Http\\Middleware\\ApiToken' => __DIR__ . '/../..' . '/Http/Middleware/ApiToken.php',
        'Modules\\Mon\\Http\\Middleware\\SaveSettings' => __DIR__ . '/../..' . '/Http/Middleware/SaveSettings.php',
        'Modules\\Mon\\Http\\Middleware\\SetLocale' => __DIR__ . '/../..' . '/Http/Middleware/SetLocale.php',
        'Modules\\Mon\\Http\\Middleware\\ThemeViews' => __DIR__ . '/../..' . '/Http/Middleware/ThemeViews.php',
        'Modules\\Mon\\Providers\\MonServiceProvider' => __DIR__ . '/../..' . '/Providers/MonServiceProvider.php',
        'Modules\\Mon\\Providers\\RouteServiceProvider' => __DIR__ . '/../..' . '/Providers/RouteServiceProvider.php',
        'Modules\\Mon\\Providers\\UserTokenProvider' => __DIR__ . '/../..' . '/Providers/UserTokenProvider.php',
        'Modules\\Mon\\Repositories\\BaseRepository' => __DIR__ . '/../..' . '/Repositories/BaseRepository.php',
        'Modules\\Mon\\Repositories\\ConnectedAccountRepository' => __DIR__ . '/../..' . '/Repositories/ConnectedAccountRepository.php',
        'Modules\\Mon\\Repositories\\Eloquent\\BaseRepository' => __DIR__ . '/../..' . '/Repositories/Eloquent/BaseRepository.php',
        'Modules\\Mon\\Repositories\\Eloquent\\ConnectedAccountRepository' => __DIR__ . '/../..' . '/Repositories/Eloquent/ConnectedAccountRepository.php',
        'Modules\\Mon\\Repositories\\Eloquent\\PermissionRepository' => __DIR__ . '/../..' . '/Repositories/Eloquent/PermissionRepository.php',
        'Modules\\Mon\\Repositories\\Eloquent\\ProfileRepository' => __DIR__ . '/../..' . '/Repositories/Eloquent/ProfileRepository.php',
        'Modules\\Mon\\Repositories\\Eloquent\\RoleRepository' => __DIR__ . '/../..' . '/Repositories/Eloquent/RoleRepository.php',
        'Modules\\Mon\\Repositories\\Eloquent\\UserRepository' => __DIR__ . '/../..' . '/Repositories/Eloquent/UserRepository.php',
        'Modules\\Mon\\Repositories\\Eloquent\\UserTokenRepository' => __DIR__ . '/../..' . '/Repositories/Eloquent/UserTokenRepository.php',
        'Modules\\Mon\\Repositories\\PermissionRepository' => __DIR__ . '/../..' . '/Repositories/PermissionRepository.php',
        'Modules\\Mon\\Repositories\\ProfileRepository' => __DIR__ . '/../..' . '/Repositories/ProfileRepository.php',
        'Modules\\Mon\\Repositories\\RoleRepository' => __DIR__ . '/../..' . '/Repositories/RoleRepository.php',
        'Modules\\Mon\\Repositories\\UserRepository' => __DIR__ . '/../..' . '/Repositories/UserRepository.php',
        'Modules\\Mon\\Repositories\\UserTokenRepository' => __DIR__ . '/../..' . '/Repositories/UserTokenRepository.php',
        'Modules\\Mon\\Scaffold\\Module\\Exception\\ModuleExistsException' => __DIR__ . '/../..' . '/Scaffold/Module/Exception/ModuleExistsException.php',
        'Modules\\Mon\\Scaffold\\Module\\Generators\\EntityGenerator' => __DIR__ . '/../..' . '/Scaffold/Module/Generators/EntityGenerator.php',
        'Modules\\Mon\\Scaffold\\Module\\Generators\\FilesGenerator' => __DIR__ . '/../..' . '/Scaffold/Module/Generators/FilesGenerator.php',
        'Modules\\Mon\\Scaffold\\Module\\Generators\\Generator' => __DIR__ . '/../..' . '/Scaffold/Module/Generators/Generator.php',
        'Modules\\Mon\\Scaffold\\Module\\Generators\\ValueObjectGenerator' => __DIR__ . '/../..' . '/Scaffold/Module/Generators/ValueObjectGenerator.php',
        'Modules\\Mon\\Scaffold\\Module\\ModuleScaffold' => __DIR__ . '/../..' . '/Scaffold/Module/ModuleScaffold.php',
        'Modules\\Mon\\Services\\OlavuiCarbonUtils' => __DIR__ . '/../..' . '/Services/OlavuiCarbonUtils.php',
        'Modules\\Mon\\Support\\Facades\\Settings' => __DIR__ . '/../..' . '/Support/Facades/Settings.php',
        'Modules\\Mon\\Support\\Facades\\Theme' => __DIR__ . '/../..' . '/Support/Facades/Theme.php',
        'Modules\\Mon\\Support\\Settings' => __DIR__ . '/../..' . '/Support/Settings.php',
        'Modules\\Mon\\Support\\Theme' => __DIR__ . '/../..' . '/Support/Theme.php',
        'Modules\\Mon\\Transformers\\UserTransformer' => __DIR__ . '/../..' . '/Transformers/UserTransformer.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9639ca0ecb7103e706951bdfb72b04af::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9639ca0ecb7103e706951bdfb72b04af::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9639ca0ecb7103e706951bdfb72b04af::$classMap;

        }, null, ClassLoader::class);
    }
}
