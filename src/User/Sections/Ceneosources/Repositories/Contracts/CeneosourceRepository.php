<?php

namespace AwemaPL\Xml\User\Sections\Ceneosources\Repositories\Contracts;

use Illuminate\Http\Request;

interface CeneosourceRepository
{
    /**
     * Create ceneosource
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope ceneosource
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update ceneosource
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete ceneosource
     *
     * @param int $id
     */
    public function delete($id);

}
