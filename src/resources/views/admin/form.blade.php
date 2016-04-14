@extends('admin.form')

@section('title')
    Gerenciar formulários
@endsection

@section('form')
    {!! BootForm::horizontal(['model' => $mmail, 'store' => 'admin.mmails.store', 'update' => 'admin.mmails.update'
        , 'id' => 'form-model', 'class' => 'form-horizontal form-rocket jq-form-validate jq-form-save'
        , 'files' => true ]) !!}

    @if ($mmail['id'])
        {!! BootForm::text('id', 'Código', null, ['disabled' => true]) !!}
    @endif

    @if ($mmail['slug'])
        {!! BootForm::text('slug', 'Identificação', null, ['disabled' => true]) !!}
    @endif

    {!! BootForm::select('status', 'Status', ['active' => 'Ativo', 'inactive' => 'Inativo'], null
        , ['class' => 'jq-select2', 'data-rule-required' => true]) !!}

    {!! BootForm::text('name', 'Nome', null, ['data-rule-required' => true, 'maxlength' => '150']) !!}

    {!! BootForm::text('toName', 'Para (nome)', null, ['data-rule-required' => true, 'maxlength' => '150']) !!}

    {!! BootForm::email('to', 'Para (email)', null, ['data-rule-required' => true, 'data-rule-email' => 'true', 'maxlength' => '150']) !!}

    {!! BootForm::text('subject', 'Assunto', null, ['data-rule-required' => true, 'maxlength' => '150']) !!}

    <div class="form-group">
        <label for="cc" class="control-label col-sm-3 col-md-3">Cópia (Cc)</label>
        <div class="col-sm-9 col-md-9">
            {!! Form::text('cc', null, ['id' => 'cc', 'class' => 'form-control']) !!}
            <span id="helpBlock" class="help-block">Para mais de 1 e-mail, separe-os por virgula (,)</span>
        </div>
    </div>

    <div class="form-group">
        <label for="bcc" class="control-label col-sm-3 col-md-3">Cópia oculta (Bcc)</label>
        <div class="col-sm-9 col-md-9">
            {!! Form::text('bcc', null, ['id' => 'bcc', 'class' => 'form-control']) !!}
            <span id="helpBlock" class="help-block">Para mais de 1 e-mail, separe-os por virgula (,)</span>
        </div>
    </div>

    {!! BootForm::close() !!}
@endsection