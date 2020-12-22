<?php

namespace AwemaPL\Xml\User\Sections\Sources\Http\Controllers;

use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Xml\User\Sections\Sources\Repositories\Contracts\SourceRepository;
use AwemaPL\Xml\User\Sections\Sources\Resources\EloquentSource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SourceController extends Controller
{

    use RedirectsTo, AuthorizesRequests;

    /**
     * Sources repository instance
     *
     * @var SourceRepository
     */
    protected $sources;

    public function __construct(SourceRepository $sources)
    {
        $this->sources = $sources;
    }

    /**
     * Display sources
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('xml::user.sections.sources.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentSource::collection(
            $this->sources->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }
}
