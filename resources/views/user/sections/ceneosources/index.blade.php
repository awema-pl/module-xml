@extends('indigo-layout::main')

@section('meta_title', _p('xml::pages.user.ceneosource.meta_title', 'XML Ceneo sources') . ' - ' . config('app.name'))
@section('meta_description', _p('xml::pages.user.ceneosource.meta_description', 'Ceneo XML user source in the application.'))

@push('head')

@endpush

@section('title')
    {{ _p('xml::pages.user.ceneosource.headline', 'XML Ceneo sources') }}
@endsection

@section('create_button')
    <button class="frame__header-add" title="{{ _p('xml::pages.user.ceneosource.add_ceneosource', 'Add XML ceneo source') }}"
            @click="AWEMA.emit('modal::add_ceneosource:open')"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('xml::pages.user.ceneosource.ceneosources', 'XML Ceneo sources') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('xml.user.ceneosource.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="ceneosources_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="name" label="{{ _p('xml::pages.user.ceneosource.name', 'Name') }}"></tb-column>
                                <tb-column name="url" label="{{ _p('xml::pages.user.ceneosource.url', 'Website address') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('xml::pages.user.ceneosource.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('xml::pages.user.ceneosource.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('xml::pages.user.ceneosource.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editCeneosource', data: col.data}); AWEMA.emit('modal::edit_ceneosource:open')">
                                                {{_p('xml::pages.user.ceneosource.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteCeneosource', data: col.data}); AWEMA.emit('modal::delete_ceneosource:open')">
                                                {{_p('xml::pages.user.ceneosource.delete', 'Delete')}}
                                            </cm-button>
                                        </context-menu>
                                    </template>
                                </tb-column>
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

    <modal-window name="add_ceneosource" class="modal_formbuilder" title="{{ _p('xml::pages.user.ceneosource.add_ceneosource', 'Add XML Ceneo source') }}">
        <form-builder name="add_ceneosource" url="{{ route('xml.user.ceneosource.store') }}"
                      @sended="AWEMA.emit('content::ceneosources_table:update')"
                      send-text="{{ _p('xml::pages.user.ceneosource.add', 'Add') }}" disabled-dialog>
            <fb-input name="name" label="{{ _p('xml::pages.user.ceneosource.name', 'Name') }}"></fb-input>
            <fb-input name="url" label="{{ _p('xml::pages.user.ceneosource.url', 'Website address') }}"></fb-input>
        </form-builder>
    </modal-window>

    <modal-window name="edit_ceneosource" class="modal_formbuilder" title="{{ _p('xml::pages.user.ceneosource.edit_ceneosource', 'Edit XML Ceneo source') }}">
        <form-builder name="edit_ceneosource" url="{{ route('xml.user.ceneosource.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::ceneosources_table:update')"
                      send-text="{{ _p('xml::pages.user.ceneosource.save', 'Save') }}" store-data="editCeneosource">
            <div v-if="AWEMA._store.state.editCeneosource">
                <fb-input name="name" label="{{ _p('xml::pages.user.ceneosource.name', 'Name') }}"></fb-input>
                <fb-input name="url" label="{{ _p('xml::pages.user.ceneosource.url', 'Website address') }}"></fb-input>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_ceneosource" class="modal_formbuilder" title="{{  _p('xml::pages.user.ceneosource.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('xml.user.ceneosource.destroy') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::ceneosources_table:update')"
                      send-text="{{ _p('xml::pages.user.ceneosource.confirm', 'Confirm') }}" store-data="deleteCeneosource"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
