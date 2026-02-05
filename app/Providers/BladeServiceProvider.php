<?php

/*
 * Blade Template Helpers & Macros
 * 
 * File ini menyediakan Blade directives untuk backward compatibility
 * dan helper functions dalam templates
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerCustomBladeDirectives();
        $this->registerBladeMacros();
    }

    /**
     * Register custom Blade directives
     */
    protected function registerCustomBladeDirectives(): void
    {
        /**
         * @can directive - short for permission check
         * 
         * Usage in Blade:
         * @can('u')
         *     <button>Edit</button>
         * @endcan
         */
        Blade::if('can', function ($permission) {
            return can($permission);
        });

        /**
         * @role directive - check user role
         */
        Blade::if('role', function ($role) {
            return auth('admin')->user()?->hasRole($role);
        });

        /**
         * @auth directive - sudah built-in di Laravel, tapi listing untuk reference
         */
        // @auth - check if authenticated
        // @guest - check if not authenticated
    }

    /**
     * Register Blade macros
     */
    protected function registerBladeMacros(): void
    {
        /**
         * Macro untuk route generation dengan icon
         * 
         * Usage: {{ route_link('Edit', route('path'), 'fa-edit') }}
         */
        Blade::macro('routeLink', function ($title, $url, $icon = null) {
            $iconHtml = $icon ? "<i class=\"fa {$icon}\"></i> " : '';
            return "<a href=\"{$url}\">{$iconHtml}{$title}</a>";
        });

        /**
         * Macro untuk action buttons
         * 
         * Usage: {{ action_buttons($model) }}
         */
        Blade::macro('actionButtons', function ($model, $resource) {
            $edit = route("{$resource}.edit", $model->id);
            $delete = route("{$resource}.destroy", $model->id);
            
            return view('partials.action_buttons', [
                'edit_url' => $edit,
                'delete_url' => $delete,
            ]);
        });

        /**
         * Macro untuk flash message display
         * 
         * Usage: {{ flash_message() }}
         */
        Blade::macro('flashMessage', function () {
            return view('partials.flash_message');
        });

        /**
         * Macro untuk pagination
         * 
         * Usage: {{ paginate($collection, 15) }}
         */
        Blade::macro('paginate', function ($items, $perPage = 15) {
            return $items->paginate($perPage);
        });
    }
}
