<?php

namespace Zareismail\Fields;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\File as Field;

class VoiceRecorder extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'voice-recorder';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string  $attribute
     * @param  string|null  $disk
     * @param  callable|null  $storageCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $disk = 'public', $storageCallback = null)
    {
    	parent::__construct($name, $attribute, $disk, $storageCallback);

    	$this->preview(function($path, $disk) { 
    		return \Storage::disk($disk)->has($path) ? \Storage::disk($disk)->url($path) : null; 
    	});
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  object  $model
     * @return void
     */
    public function fillForAction(NovaRequest $request, $model)
    { 
        return $this->fillAttribute($request, $this->attribute, $model, $this->attribute);
    }
}
