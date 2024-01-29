# NovaSpatieTagsFilter
Works with Laravel Nova 4.  
Borrowed heavily from https://github.com/Mahi-Mahi/SpatieTagsNovaFilter.  

This package allows you to filter resources by tags.

Using the awesome [Spatie/laravel-tags](https://github.com/spatie/laravel-tags) and [Vue-MultiSelect](https://github.com/shentao/vue-multiselect) 

## Installation

First you must install [spatie/laravel-tags](https://github.com/spatie/laravel-tags) into your Laravel app. Here are [the installation instructions](https://spatie.be/docs/laravel-tags/v4/installation-and-setup) for that package.

Next, you can install this package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require helios-live/php-nova-spatie-tags-filter
```

## Usage

After the package has been installed with Composer, simply register the filter in your resource:

```php
use HeliosLive\PhpNovaSpatieTagsFilter\PhpNovaSpatieTagsFilter;

class MyResource extends Resource
{
    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            (new PhpNovaSpatieTagsFilter)
                ->label('Tags')
                // ->placeholder('')
                ->withMeta([
                    'withAnyTags' => true, // true, false, "never"
                    'tag_type' => null
                ])
        ];
    }
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
