<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StockRequest;
use App\Models\Book;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StockCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StockCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Stock::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/stock');
        CRUD::setEntityNameStrings('stock', 'stocks');
    }

    public function fetchBook()
    {
        return $this->fetch(\App\Models\Book::class);
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
        CRUD::column('pkg')
            ->type('boolean')
            ->label('PKG')
            ->wrapper([
                'element' => 'span',
                'class'   => static function ($crud, $column, $entry) {
                    return 'badge badge-'.($entry->{$column['name']} ? 'success' : 'default');
                },
            ]);
        CRUD::addColumn([
            'label' => 'Stock Balance',
            'type'  => 'model_function',
            'function_name' => 'balance'
        ]);

        // Filter By Invoice
        $this->crud->addFilter([
        'type'  => 'text',
        'name'  => 'invoice',
        'label' => 'Invoice'
        ],
        false,
        function($value) { // if the filter is active
             $this->crud->addClause('where', 'invoice', '=', "$value");
        });

        // Filter By Book (This is Stock Card)
        $this->crud->addFilter([ // select2 filter
            'name' => 'book_id',
            'type' => 'select2',
            'label'=> 'Book',
        ], function () {
            return Book::all()->keyBy('id')->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'book_id', $value);
        });
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StockRequest::class);

        CRUD::field('invoice')->size(4);
        CRUD::addField([
            'name' => 'book_id',
            'type' => "relationship",
            'ajax' => true,
            'wrapper' => ['class' => 'form-group col-md-8'],
            'placeholder' => 'Select a book',
            'inline_create' => ['stock' => 'book']
        ]);

        CRUD::addField([
            'name' => 'received_amount',
            'label' => 'Received Amount',
            'default' => '0',
            'wrapper' => ['class' => 'form-group col-md-6'],
        ]);
        CRUD::addField([
            'name' => 'issued_amount',
            'label' => 'Issued Amount',
            'default' => '0',
            'wrapper' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::field('pkg')->size(1);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
