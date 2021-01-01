<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StockRequest;
use App\Models\Book;
use App\Models\Stock;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class StockCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;
    use FetchOperation;

    public function setup()
    {
        CRUD::setModel(Stock::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/stock');
        CRUD::setEntityNameStrings('stock', 'stocks');
    }

    public function fetchBook()
    {
        return $this->fetch([
            'model' => Book::class,
            'searchable_attributes' => ['name', 'author', 'isbn'],
        ]);
    }

    protected function setupListOperation()
    {

        CRUD::column('invoice');
        CRUD::addColumn([
            'name' => 'book',
            'type' => 'relationship'
        ]);
        CRUD::column('received_amount');
        CRUD::column('issued_amount');
        CRUD::column('cost_price')->label('Cost Price');
        CRUD::addColumn([
            'label' => 'Stock Balance',
            'type' => 'model_function',
            'function_name' => 'balance'
        ]);
        CRUD::addColumn([
            'label' => 'Recorded On',
            'name' => 'created_at',
            'visibleInTable' => false
        ]);
        CRUD::addColumn([
            'label' => 'Last Updated',
            'name' => 'updated_at',
        ]);

        // Filter By Invoice
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'invoice',
            'label' => 'Invoice'
        ], false, function ($value) { // if the filter is active
                $this->crud->addClause('where', 'invoice', '=', "$value");
            });

        // Filter By Book (This is Stock Card)
        $this->crud->addFilter([
            'name' => 'book_id',
            'type' => 'select2',
            'label' => 'Book',
        ], function () {
            return Book::all()->keyBy('id')->pluck('name', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'book_id', $value);
        });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'created_at',
            'label' => 'Date Range'
        ], false, function ($value) {
                 $dates = json_decode($value);
                 $this->crud->addClause('where', 'created_at', '>=', $dates->from);
                 $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
            });

        CRUD::enableExportButtons();
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StockRequest::class);

        CRUD::field('invoice')->size(4)->hint('Receipt No.');
        CRUD::addField([
            'name' => 'book_id',
            'type' => "relationship",
            'ajax' => true,
            'wrapper' => ['class' => 'form-group col-md-8'],
            'inline_create' => ['stock' => 'book'],
            'placeholder' => 'Select a book',
            'hint' => 'Select a book. Search by <span style="color: black">Title, Author</span> or <span style="color: black">ISBN</span>'
        ]);

        CRUD::addField([
            'name' => 'cost_price',
            'label' => 'Cost Price',
            'wrapper' => ['class' => 'form-group col-md-4'],
        ]);
        CRUD::addField([
            'name' => 'received_amount',
            'label' => 'Received Amount',
            'default' => '0',
            'wrapper' => ['class' => 'form-group col-md-4'],
        ]);
        CRUD::addField([
            'name' => 'issued_amount',
            'label' => 'Issued Amount',
            'default' => '0',
            'wrapper' => ['class' => 'form-group col-md-4'],
        ]);

        CRUD::field('pkg')->size(1)->label('PKG?');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
