<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class BookCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    public function setup()
    {
        CRUD::setModel(Book::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/book');
        CRUD::setEntityNameStrings('book', 'books');
    }

    protected function setupListOperation()
    {

        CRUD::addColumn([
            'name' => 'name',
            'label' => 'Title'
        ]);
        CRUD::column('author');
        CRUD::addColumn([
            'name' => 'published_year',
            'label' => 'Published Year',
            'visibleInTable' => false
        ]);
//        CRUD::column('cost_price')->label('Cost Price');
//        CRUD::column('selling_price')->label('Selling Price');
        CRUD::addColumn([
            'label' => 'Book Balance',
            'type'  => 'model_function',
            'function_name' => 'balance'
        ]);
        CRUD::addColumn([
            'label' => 'Cost Price (Mean)',
            'type' => 'model_function',
            'function_name' => 'meanPrice'
        ]);
        CRUD::column('consignment')
            ->label('Type')
            ->type('boolean')
            ->wrapper([
                'element' => 'span',
                'class'   => static function ($crud, $column, $entry) {
                    return 'badge badge-'.($entry->{$column['name']} ? 'success' : 'default');
                },
            ])->options([
                0 => 'Cash',
                1 => 'Consignment'
            ]);
        CRUD::addColumn([
            'name' => 'ISBN',
            'visibleInTable' => false,
        ]);
        CRUD::addColumn([
            'label'     => 'Stock Card',
            'type'      => 'relationship_count',
            'name'      => 'stocks',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('stock?book_id='.$entry->getKey().'&stock_report='.true);
                },
            ],
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

        CRUD::enableExportButtons();

        // dropdown filter
        $this->crud->addFilter([
            'name'  => 'consignment',
            'type'  => 'dropdown',
            'label' => 'Consignment / Cash'
        ], [
            1 => 'Consignment Only',
            0 => 'Cash Only',
        ], function($value) { // if the filter is active
             $this->crud->addClause('where', 'consignment', $value);
        });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'created_at',
            'label' => 'Date Range'
        ], false, function ($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'created_at', '>=', $dates->from);
            $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
        });
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(BookRequest::class);

         CRUD::addField([
            'name' => 'name',
            'label' => 'Title'
        ]);
        CRUD::field('author')->size(4);
        CRUD::field('published_year')->size(4)->type('number');
//        CRUD::field('cost_price')->size(4);
//        CRUD::field('selling_price')->size(4);
        CRUD::field('ISBN')->size(4)->type('number');
        CRUD::field('consignment')->size(12);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
