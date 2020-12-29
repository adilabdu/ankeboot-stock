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
        CRUD::column('cost_price')->label('Cost Price');
        CRUD::column('selling_price')->label('Selling Price');
        CRUD::addColumn([
            'label' => 'Book Balance',
            'type'  => 'model_function',
            'function_name' => 'balance'
        ]);
        CRUD::column('consignment')
            ->type('boolean')
            ->wrapper([
                'element' => 'span',
                'class'   => static function ($crud, $column, $entry) {
                    return 'badge badge-'.($entry->{$column['name']} ? 'success' : 'default');
                },
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
                    return backpack_url('stock?book_id='.$entry->getKey());
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
        CRUD::field('consignment')->size(2);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
