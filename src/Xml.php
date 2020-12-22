<?php

namespace AwemaPL\Xml;

use AwemaPL\Xml\Admin\Sections\Settings\Models\Setting;
use AwemaPL\Xml\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use AwemaPL\Xml\Contracts\Xml as XmlContract;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Xml implements XmlContract
{
    /** @var Router $router */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;}

    /**
     * Routes
     */
    public function routes()
    {
        if ($this->isActiveRoutes()) {
            if ($this->isActiveAdminInstallationRoutes() && (!$this->isMigrated())) {
                $this->adminInstallationRoutes();
            }
            if ($this->isActiveSourceRoutes()) {
                $this->sourceRoutes();
            }
            if ($this->isActiveCeneosourceRoutes()) {
                $this->ceneosourceRoutes();
            }
            if ($this->isActiveAdminSettingRoutes()) {
                $this->adminSettingRoutes();
            }
        }
    }

    /**
     * Admin installation routes
     */
    protected function adminInstallationRoutes()
    {
        $prefix = config('xml.routes.admin.installation.prefix');
        $namePrefix = config('xml.routes.admin.installation.name_prefix');
        $this->router->prefix($prefix)->name($namePrefix)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Xml\Admin\Sections\Installations\Http\Controllers\InstallationController@index')
                ->name('index');
            $this->router->post('/', '\AwemaPL\Xml\Admin\Sections\Installations\Http\Controllers\InstallationController@store')
                ->name('store');
        });

    }

    /**
     * Admin setting routes
     */
    protected function adminSettingRoutes()
    {
        $prefix = config('xml.routes.admin.setting.prefix');
        $namePrefix = config('xml.routes.admin.setting.name_prefix');
        $middleware = config('xml.routes.admin.setting.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Xml\Admin\Sections\Settings\Http\Controllers\SettingController@index')
                ->name('index');
            $this->router
                ->get('/settings', '\AwemaPL\Xml\Admin\Sections\Settings\Http\Controllers\SettingController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Xml\Admin\Sections\Settings\Http\Controllers\SettingController@update')
                ->name('update');
        });
    }
    
    /**
     * Source routes
     */
    protected function sourceRoutes()
    {
        $prefix = config('xml.routes.user.source.prefix');
        $namePrefix = config('xml.routes.user.source.name_prefix');
        $middleware = config('xml.routes.user.source.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Xml\User\Sections\Sources\Http\Controllers\SourceController@index')
                ->name('index');
            $this->router
                ->get('/sources', '\AwemaPL\Xml\User\Sections\Sources\Http\Controllers\SourceController@scope')
                ->name('scope');
        });
    }

    /**
     * Ceneosource routes
     */
    protected function ceneosourceRoutes()
    {
        $prefix = config('xml.routes.user.ceneosource.prefix');
        $namePrefix = config('xml.routes.user.ceneosource.name_prefix');
        $middleware = config('xml.routes.user.ceneosource.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Xml\User\Sections\Ceneosources\Http\Controllers\CeneosourceController@index')
                ->name('index');
            $this->router
                ->get('/ceneosources', '\AwemaPL\Xml\User\Sections\Ceneosources\Http\Controllers\CeneosourceController@scope')
                ->name('scope');
            $this->router
                ->post('/', '\AwemaPL\Xml\User\Sections\Ceneosources\Http\Controllers\CeneosourceController@store')
                ->name('store');
            $this->router
                ->patch('{id?}', '\AwemaPL\Xml\User\Sections\Ceneosources\Http\Controllers\CeneosourceController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Xml\User\Sections\Ceneosources\Http\Controllers\CeneosourceController@destroy')
                ->name('destroy');
        });
    }
    
    /**
     * Can installation
     *
     * @return bool
     */
    public function canInstallation()
    {
        $canForPermission = $this->canInstallForPermission();
        return $this->isActiveRoutes()
            && $this->isActiveAdminInstallationRoutes()
            && $canForPermission
            && (!$this->isMigrated());
    }

    /**
     * Is migrated
     *
     * @return bool
     */
    public function isMigrated()
    {
        $tablesInDb = array_map('reset', DB::select('SHOW TABLES'));

        $tables = array_values(config('xml.database.tables'));
        foreach ($tables as $table){
            if (!in_array($table, $tablesInDb)){
                return false;
            }
        }
        return true;
    }

    /**
     * Is active routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveRoutes()
    {
        return config('xml.routes.active');
    }

    /**
     * Is active admin setting routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveAdminSettingRoutes()
    {
        return config('xml.routes.admin.setting.active');
    }

    /**
     * Is active admin installation routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveAdminInstallationRoutes()
    {
        return config('xml.routes.admin.installation.active');
    }
    
    /**
     * Is active source routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveSourceRoutes()
    {
        return config('xml.routes.user.source.active');
    }

    /**
     * Is active ceneosource routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveCeneosourceRoutes()
    {
        return config('xml.routes.user.ceneosource.active');
    }
    
    /**
     * Include lang JS
     */
    public function includeLangJs()
    {
        $lang = config('indigo-layout.frontend.lang', []);
        $lang = array_merge_recursive($lang, app(Translator::class)->get('xml::js')?:[]);
        app('config')->set('indigo-layout.frontend.lang', $lang);
    }

    /**
     * Can install for permission
     *
     * @return bool
     */
    private function canInstallForPermission()
    {
        $userClass = config('auth.providers.users.model');
        if (!method_exists($userClass, 'hasRole')) {
            return true;
        }

        if ($user = request()->user() ?? null){
            return $user->can(config('xml.installation.auto_redirect.permission'));
        }

        return false;
    }

    /**
     * Menu merge in navigation
     */
    public function menuMerge()
    {
        if ($this->canMergeMenu()){
            $xmlMenu = config('xml-menu.navs', []);
            $navTemp = config('temp_navigation.navs', []);
            $nav = array_merge_recursive($navTemp, $xmlMenu);
            config(['temp_navigation.navs' => $nav]);
        }
    }

    /**
     * Can merge menu
     *
     * @return boolean
     */
    private function canMergeMenu()
    {
        return !!config('xml-menu.merge_to_navigation') && self::isMigrated();
    }

    /**
     * Execute package migrations
     */
    public function migrate()
    {
         Artisan::call('migrate', ['--force' => true, '--path'=>'vendor/awema-pl/module-xml/database/migrations']);
    }

    /**
     * Install package
     */
    public function install()
    {
        $this->migrate();
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }

    /**
     * Add permissions for module permission
     */
    public function mergePermissions()
    {
       if ($this->canMergePermissions()){
           $xmlPermissions = config('xml.permissions');
           $tempPermissions = config('temp_permission.permissions', []);
           $permissions = array_merge_recursive($tempPermissions, $xmlPermissions);
           config(['temp_permission.permissions' => $permissions]);
       }
    }

    /**
     * Can merge permissions
     *
     * @return boolean
     */
    private function canMergePermissions()
    {
        return !!config('xml.merge_permissions');
    }
}
