<?php

namespace AwemaPL\Xml\User\Sections\Ceneosources\Repositories;

use AwemaPL\Xml\User\Sections\Ceneosources\Models\Ceneosource;
use AwemaPL\Xml\User\Sections\Ceneosources\Repositories\Contracts\CeneosourceRepository;
use AwemaPL\Xml\User\Sections\Ceneosources\Scopes\EloquentCeneosourceScopes;
use AwemaPL\Xml\User\Sections\Sources\Repositories\Contracts\SourceRepository;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Collection;

class EloquentCeneosourceRepository extends BaseRepository implements CeneosourceRepository
{

    /** @var SourceRepository $sources */
    protected $sources;

    public function __construct(SourceRepository $sources)
    {
        parent::__construct();
        $this->sources = $sources;
    }

    protected $searchable = [

    ];

    public function entity()
    {
        return Ceneosource::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentCeneosourceScopes($request))->scope($this->entity);
        return $this;
    }

    /**
     * Create new role
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $data['user_id'] = $data['user_id'] ?? Auth::id();
        $ceneosource = Ceneosource::create($data);
        $ceneosource->source()->create([
            'user_id' =>$data['user_id'],
        ]);
        return $ceneosource;
    }

    /**
     * Update source
     *
     * @param array $data
     * @param int $id
     * @param string $attribute
     *
     * @return int
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return parent::update($data, $id, $attribute);
    }

    /**
     * Delete source
     *
     * @param int $id
     */
    public function delete($id){
        $ceneosource = Ceneosource::find($id);
        $ceneosource->source()->delete();
        $this->destroy($id);
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return Model|Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']){
        return parent::find($id, $columns);
    }
}
