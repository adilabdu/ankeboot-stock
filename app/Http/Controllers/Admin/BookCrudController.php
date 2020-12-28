<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BookCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
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
        CRUD::setModel(\App\Models\Book::class);
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
        CRUD::column('published_year');
        CRUD::column('cost_price');
        CRUD::column('selling_price');
        CRUD::addColumn([
            'label' => 'Book Balance',
            'type'  => 'model_function',
            'function_name' => 'balance'
        ]);
        CRUD::addColumn([
            'label'     => 'Stock Card', // Table column heading
            'type'      => 'relationship_count',
            'name'      => 'stocks', // the method that defines the relationship in your Model
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('stock?book_id='.$entry->getKey());
                },
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(BookRequest::class);

         CRUD::addField([
            'name' => 'name',
            'label' => 'Title'
        ]);
        CRUD::field('author')->size(8);
        CRUD::field('published_year')->size(4)->type('number');
        CRUD::field('cost_price')->size(4)->type('number');
        CRUD::field('selling_price')->size(4)->type('number');
        CRUD::field('ISBN')->size(4)->type('number');

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
