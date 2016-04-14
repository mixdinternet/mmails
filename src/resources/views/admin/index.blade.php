@extends('admin.index')

@section('title')
    Listagem de formulários
@endsection

@section('btn-insert')
    @if((!checkRule('admin.mmails.create')) && (!$trash))
        @include('admin.partials.actions.btn.insert', ['route' => route('admin.mmails.create')])
    @endif
    @if((!checkRule('admin.mmails.trash')) && (!$trash))
        @include('admin.partials.actions.btn.trash', ['route' => route('admin.mmails.trash')])
    @endif
    @if($trash)
        @include('admin.partials.actions.btn.list', ['route' => 'admin.mmails.index'])
    @endif
@endsection

@section('btn-delete-all')
    @if((!checkRule('admin.mmails.destroy')) && (!$trash))
        @include('admin.partials.actions.btn.delete-all', ['route' => 'admin.mmails.destroy'])
    @endif
@endsection

@section('search')
    {!! Form::model($search, ['route' => ($trash) ? 'admin.mmails.trash' : 'admin.mmails.index', 'method' => 'get', 'id' => 'form-search'
        , 'class' => '']) !!}
    <div class="row">
        <div class="col-md-4">
            {!! BootForm::select('status', 'Status', ['' => '-', 'active' => 'Ativo', 'inactive' => 'Inativo'], null
                , ['class' => 'jq-select2']) !!}
        </div>
        <div class="col-md-4">
            {!! BootForm::text('name', 'Nome') !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="{{ route(($trash) ? 'admin.mmails.trash' : 'admin.mmails.index') }}"
                   class="btn btn-default btn-flat">
                    <i class="fa fa-list"></i>
                    <i class="fs-normal hidden-xs">Mostrar tudo</i>
                </a>
                <button class="btn btn-success btn-flat">
                    <i class="fa fa-search"></i>
                    <i class="fs-normal hidden-xs">Buscar</i>
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('table')
    @if (count($mmails) > 0)
        <table class="table table-striped table-hover table-action jq-table-rocket">
            <thead>
            <tr>
                @if((!checkRule('admin.mmails.destroy')) && (!$trash))
                    <th>
                        <div class="checkbox checkbox-flat">
                            <input type="checkbox" id="checkbox-all">
                            <label for="checkbox-all">
                            </label>
                        </div>
                    </th>
                @endif
                <th>{!! columnSort('#', ['field' => 'id', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Nome', ['field' => 'name', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Identificação', ['field' => 'slug', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Status', ['field' => 'status', 'sort' => 'asc']) !!}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($mmails as $mmail)
                <tr>
                    @if((!checkRule('admin.mmails.destroy')) && (!$trash))
                        <td>
                            @include('admin.partials.actions.checkbox', ['row' => $mmail])
                        </td>
                    @endif
                    <td>{{ $mmail->id }}</td>
                    <td>{{ $mmail->name }}</td>
                    <td>{{ $mmail->slug }}</td>
                    <td>@include('admin.partials.label.status', ['status' => $mmail->status])</td>
                    <td>
                        @if((!checkRule('admin.mmails.edit')) && (!$trash))
                            @include('admin.partials.actions.btn.edit', ['route' => route('admin.mmails.edit', $mmail->id)])
                        @endif
                        @if((!checkRule('admin.mmails.destroy')) && (!$trash))
                            @include('admin.partials.actions.btn.delete', ['route' => 'admin.mmails.destroy', 'id' => $mmail->id])
                        @endif
                        @if($trash)
                            @include('admin.partials.actions.btn.restore', ['route' => 'admin.mmails.restore', 'id' => $mmail->id])
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        @include('admin.partials.nothing-found')
    @endif
@endsection

@section('pagination')
    {!! $mmails->appends(request()->except(['page']))->render() !!}
@endsection