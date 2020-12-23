<?php

namespace AwemaPL\Xml\User\Sections\Ceneosources\Http\Controllers;

use AwemaPL\Xml\User\Sections\Ceneosources\Http\Requests\StoreCeneosource;
use AwemaPL\Xml\User\Sections\Ceneosources\Http\Requests\UpdateCeneosource;
use AwemaPL\Xml\User\Sections\Ceneosources\Models\Ceneosource;
use AwemaPL\Xml\User\Sections\Ceneosources\Repositories\Contracts\CeneosourceRepository;
use AwemaPL\Xml\User\Sections\Ceneosources\Resources\EloquentCeneosource;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AwemaPL\Xml\Client\XmlClient;

class CeneosourceController extends Controller
{

    use RedirectsTo, AuthorizesRequests;

    /**
     * Ceneosources repository instance
     *
     * @var CeneosourceRepository
     */
    protected $ceneosources;

    public function __construct(CeneosourceRepository $ceneosources)
    {
        $this->ceneosources = $ceneosources;
    }

    /**
     * Display ceneosources
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('xml::user.sections.ceneosources.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentCeneosource::collection(
            $this->ceneosources->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Add ceneosource
     *
     * @param StoreCeneosource $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreCeneosource $request)
    {
        $this->ceneosources->create($request->all());
        return notify(_p('xml::notifies.user.ceneosource.success_added_source', 'Success added source XML Ceneo.'));
    }

    /**
     * Update ceneosource
     *
     * @param UpdateCeneosource $request
     * @param $id
     * @return array
     */
    public function update(UpdateCeneosource $request, $id)
    {
        $this->authorize('isOwner', Ceneosource::find($id));
        $this->ceneosources->update($request->all(), $id);
        return notify(_p('xml::notifies.user.ceneosource.success_updated_source', 'Success updated source XML Ceneo.'));
    }
    
    /**
     * Destroy ceneosource
     *
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        $this->authorize('isOwner', Ceneosource::find($id));
        $this->ceneosources->delete($id);
        return notify(_p('xml::notifies.user.ceneosource.success_deleted_source', 'Success deleted source XML Ceneo.'));
    }

    /**
     * Check connection ceneosource
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function checkConnection($id)
    {
        $this->authorize('isOwner', Ceneosource::find($id));
        $ceneosources = $this->ceneosources->find($id);
        $error = (new XmlClient(['url' =>$ceneosources->url, 'download_before' =>false]))->ceneo()->fail();
        if (!empty($error)){
            return $this->ajaxNotifyError($error, 422);
        }
        return notify(_p('xml::notifies.user.ceneosource.success_connected_source', 'Success connected to the source XML Ceneo.'));
    }
}
