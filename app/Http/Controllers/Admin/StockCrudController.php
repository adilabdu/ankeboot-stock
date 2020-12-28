<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StockRequest;
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
        CRUD::column('pkg')->type('boolean');
        CRUD::column('consignment')->type('boolean');
        CRUD::addColumn([
            'label' => 'Stock Balance',
            'type'  => 'model_function',
            'function_name' => 'balance'
        ]);
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
        CRUD::field('consignment')->size(2);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
