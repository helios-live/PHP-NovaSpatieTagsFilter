<?php

namespace HeliosLive\PhpNovaSpatieTagsFilter;

use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class PhpNovaSpatieTagsFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'php-nova-spatie-tags-filter';

    public function __construct($tag_type = null, $withAnyTags = true)
    {
        $this->withMeta([
            "tag_type" => $tag_type,
            "withAnyTags" => $withAnyTags,
        ]);
    }
    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        if ($value) {
            $useAny = false;
            if (($pos = array_search("::useAny", $value, true)) !== false) {
                $useAny = true;
                unset($value[$pos]);
            }

            if ($this->meta['withAnyTags'] !== 'never' && $useAny) {
                $query->withAnyTags($value, $this->meta['tag_type']);
            } else {
                $query->withAllTags($value, $this->meta['tag_type']);
            }
        }
        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return [];
    }


    public function label($name)
    {
        $this->name = $name;

        return $this;
    }
    public function placeholder($placeholder)
    {
        $this->withMeta([
            'placeholder' => $placeholder
        ]);

        return $this;
    }
}
