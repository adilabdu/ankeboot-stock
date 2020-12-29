@extends(backpack_view('blank'))

@php
/**    $widgets['before_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => trans('backpack::base.welcome'),
        'content'     => trans('backpack::base.use_sidebar'),
        'button_link' => backpack_url('logout'),
        'button_text' => trans('backpack::base.logout'),
    ]; **/

    use App\Models\Book;

    $booksCount = Book::all()->count();
    $consignment = Book::where('consignment', true)->count();
    $cheques = Book::where('consignment', false)->count();

    $latestBook = Book::orderBy('created_at', 'DESC')->first();

    $averageBalance = 0;
    $maxBalance = 0;
    $books = Book::all();

    foreach($books as $book) {
        $averageBalance += $book->balance();
        $maxBalance = max($maxBalance, $book->balance());
    }
    $averageBalance = $averageBalance / $booksCount;

    $articleCount = 100;
    $lastArticleDaysAgo = 3;
    $productCount = 25;

 	// notice we use Widget::add() to add widgets to a certain group
	Widget::add()->to('before_content')->type('div')->class('row')->content([
		// notice we use Widget::make() to add widgets as content (not in a group)
		Widget::make()
			->type('progress')
			->class('card border-0 text-white bg-primary')
			->progressClass('progress-bar')
			->value($booksCount)
			->description('Registered books.')
			->progress($consignment / $booksCount * 100)
			->hint('<span class="text-white">' . $consignment . ' consignments, </span> ' . $cheques . ' cheques'),

		// alternatively, to use widgets as content, we can use the same add() method,
		// but we need to use onlyHere() or remove() at the end
		Widget::add()
		    ->type('progress')
		    ->class('card border-0 text-white bg-success')
		    ->progressClass('progress-bar')
		    ->value($latestBook->name)
		    ->description('Last Registered Book')
		    // ->progress(80)
		    ->hint('Added On: <span class="text-white">' . $latestBook->created_at . '</span>')
		    ->onlyHere(),

		// alternatively, you can just push the widget to a "hidden" group
		Widget::make()
			->group('hidden')
		    ->type('progress')
		    ->class('card border-0 text-white bg-warning')
		    ->value(floor($averageBalance))
		    ->progressClass('progress-bar')
		    ->description('Average Balance of Books in Inventory')
		    // ->progress(30)
		    ->hint('Maximum Balance: <span class="text-white">' . $maxBalance . '</span>'),

		// both Widget::make() and Widget::add() accept an array as a parameter
		// if you prefer defining your widgets as arrays
	    Widget::make([
			'type' => 'progress',
			'class'=> 'card border-0 text-white bg-dark',
			'progressClass' => 'progress-bar',
			'value' => $productCount,
			'description' => 'Products.',
			'progress' => (int)$productCount/75*100,
			'hint' => $productCount>75?'Try to stay under 75 products.':'Good. Good.',
		]),
	]);

@endphp

@section('content')
@endsection
