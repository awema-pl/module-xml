@extends('indigo-layout::installation')

@section('meta_title', _p('xml::pages.admin.installation.meta_title', 'Installation module PrestaShop') . ' - ' . config('app.name'))
@section('meta_description', _p('xml::pages.admin.installation.meta_description', 'Installation module PrestaShop in application'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('xml::pages.admin.installation.headline', 'Installation module PrestaShop') }}</h2>
@endsection

@section('content')
    <form-builder disabled-dialog="" url="{{ route('xml.admin.installation.index') }}" send-text="{{ _p('xml::pages.admin.installation.send_text', 'Install') }}"
    edited>
        <div class="section">
            <div class="section">
                {{ _p('xml::pages.admin.installation.will_be_execute_migrations', 'Will be execute package migrations') }}
            </div>
        </div>
    </form-builder>
@endsection
