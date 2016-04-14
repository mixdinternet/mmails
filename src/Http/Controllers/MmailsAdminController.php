<?php

namespace Mixdinternet\Mmails\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Flash\Facades\Flash;
use App\Http\Controllers\AdminController;
use Mixdinternet\Mmails\Mmail as MmailModel;
use Mixdinternet\Mmails\Http\Requests\CreateEditMmailsRequest;

class MmailsAdminController extends AdminController
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        session()->put('backUrl', request()->fullUrl());

        $trash = ($request->segment(3) == 'trash') ? true : false;

        $query = MmailModel::sort();
        ($trash) ? $query->onlyTrashed() : '';

        $search = [];
        $search['status'] = $request->input('status', '');
        $search['name'] = $request->input('name', '');

        ($search['status']) ? $query->where('status', $search['status']) : '';
        ($search['name']) ? $query->where('name', 'LIKE', '%' . $search['name'] . '%') : '';

        $mmails = $query->paginate(50);

        $view['trash'] = $trash;
        $view['search'] = $search;
        $view['mmails'] = $mmails;

        return view('mixdinternet/mmails::admin.index', $view);
    }

    public function create(MmailModel $mmail)
    {
        $view['mmail'] = $mmail;

        return view('mixdinternet/mmails::admin.form', $view);
    }

    public function store(CreateEditMmailsRequest $request)
    {
        if (MmailModel::create($request->all())) {
            Flash::success('Item inserido com sucesso.');
        } else {
            Flash::error('Falha no cadastro.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.mmails.index');
    }

    public function edit(MmailModel $mmail)
    {
        $view['mmail'] = $mmail;

        return view('mixdinternet/mmails::admin.form', $view);
    }

    public function update(MmailModel $mmail, CreateEditMmailsRequest $request)
    {
        if ($mmail->update($request->all())) {
            Flash::success('Item atualizado com sucesso.');
        } else {
            Flash::error('Falha na atualização.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.mmails.index');
    }

    public function destroy(Request $request)
    {
        if (MmailModel::destroy($request->input('id'))) {
            Flash::success('Item removido com sucesso.');
        } else {
            Flash::error('Falha na remoção.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.mmails.index');
    }

    public function restore($id)
    {
        $mmail = MmailModel::onlyTrashed()->find($id);

        if (!$mmail) {
            abort(404);
        }

        if ($mmail->restore()) {
            Flash::success('Item restaurado com sucesso.');
        } else {
            Flash::error('Falha na restauração.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.mmails.trash');
    }
}
