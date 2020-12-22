<?php

namespace AwemaPL\Xml\User\Sections\Ceneosources\Models;

use AwemaPL\Xml\User\Sections\Sources\Models\Contracts\Sourceable;
use AwemaPL\Xml\User\Sections\Sources\Models\Source;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Xml\User\Sections\Ceneosources\Models\Contracts\Ceneosource as CeneosourceContract;
use PrintNode\PrintJob;

class Ceneosource extends Model implements CeneosourceContract, Sourceable
{

    const PROVIDER_NAME = 'PrintNode';

    use EncryptableDbAttribute;

    /** @var array The attributes that should be encrypted/decrypted */
    protected $encryptable = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'url', 'user_id'];
    
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
        return config('xml.database.tables.xml_ceneosources');
    }

    /**
     * Get the source.
     */
    public function source()
    {
        return $this->morphOne(Source::class, 'sourceable');
    }

    /**
     * Get provider name
     *
     * @return string
     */
    public function getProviderName()
    {
        return self::PROVIDER_NAME;
    }
}
