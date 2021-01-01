<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use App\Imports\BooksImport;
use App\Models\Book;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Maatwebsite\Excel\Facades\Excel;
use Prologue\Alerts\Facades\Alert;

class FileIOController extends CrudController
{
    public function importView() {
        return view('importView');
    }

    public function import() {
        Excel::import(new BooksImport(), request()->file('file'));

        Alert::add('success', 'Successfully imported')->flash();

        return back();
    }
}
