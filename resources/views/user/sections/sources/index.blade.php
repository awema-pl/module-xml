@extends('indigo-layout::main')

@section('meta_title', _p('xml::pages.user.source.meta_title', 'All XML sources') . ' - ' . config('app.name'))
@section('meta_description', _p('xml::pages.user.source.meta_description', 'All user XML sources in the application.'))

@push('head')

@endpush

@section('title')
    {{ _p('xml::pages.user.source.headline', 'All XML sources') }}
@endsection

@section('create_button')

@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('xml::pages.user.source.all_xml_sources', 'All XML sources') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('xml.user.source.scope')}}', ['page', 'limit'], $route.query)"
                                     :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                                     name="sources_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="provider" label="{{ _p('xml::pages.user.source.provider', 'Source provider') }}">
                                    <template slot-scope="col">
                                        <span class="badge badge_grass">@{{ col.data.provider }}</span>
                                    </template>
                                </tb-column>
                                <tb-column name="sourceable" label="{{ _p('xml::pages.user.source.name', 'Name') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.sourceable.name }}
                                    </template>
                                </tb-column>
                                <tb-column name="created_at" label="{{ _p('xml::pages.user.source.created_at', 'Created at') }}"></tb-column>
                            </table-builder>

                            <paginate-builder v-if="table.data"
                                              :meta="table.meta"
                            ></paginate-builder>
                        </template>
                        @include('indigo-layout::components.base.loading')
                        @include('indigo-layout::components.base.empty')
                        @include('indigo-layout::components.base.error')
                    </content-wrapper>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

@endsection
