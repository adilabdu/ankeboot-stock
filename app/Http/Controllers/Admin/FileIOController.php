<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use App\Imports\BooksImport;
use App\Models\Book;
use Backpack\CRUD\app\Http\Controllers\CrudController;
//use http\Client\Request;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Prologue\Alerts\Facades\Alert;

class FileIOController extends CrudController
{

    public function import(Request $request) {

        $file = $request->file('file');

        $import = new BooksImport;
        $import->import($file);

        Alert::add('success', 'Successfully imported')->flash();
        return back();
    }
}
