<?php

namespace AwemaPL\Xml\User\Sections\Sources\Models;

use Illuminate\Database\Eloquent\Model;
use AwemaPL\Xml\User\Sections\Sources\Models\Contracts\Source as SourceContract;

class Source extends Model implements SourceContract
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id'];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('xml.database.tables.xml_sources');
    }

    /**
     * Get the owning sourceable model.
     */
    public function sourceable()
    {
        return $this->morphTo();
    }
 
}
