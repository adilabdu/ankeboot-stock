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
    if($booksCount > 0) {
        $averageBalance = $averageBalance / $booksCount;
    } else {
        $averageBalance = 'N/A';
    }

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
			->hint('<span class="text-white">' . $consignment . ' consignments, </span> ' . $cheques . ' cash'),

		// alternatively, to use widgets as content, we can use the same add() method,
		// but we need to use onlyHere() or remove() at the end
		Widget::add()
		    ->type('progress')
		    ->class('card border-0 text-white bg-success')
		    ->progressClass('progress-bar')
		    ->value($latestBook ? $latestBook->name : 'N/A')
		    ->description('Last Registered Book')
		    // ->progress(80)
		    ->hint('Added On: <span class="text-white">' . ($latestBook ? $latestBook->created_at : 'N/A') . '</span>')
		    ->onlyHere(),

		// alternatively, you can just push the widget to a "hidden" group
		Widget::make()
			->group('hidden')
		    ->type('progress')
		    ->class('card border-0 text-white bg-warning')
		    ->value($averageBalance == 'N/A' ? 'N/A' : floor($averageBalance))
		    ->progressClass('progress-bar')
		    ->description('Average Balance of Books in Inventory')
		    // ->progress(30)
		    ->hint('Highest Balance: <span class="text-white">' . $maxBalance . '</span>'),

		// both Widget::make() and Widget::add() accept an array as a parameter
		// if you prefer defining your widgets as arrays
	    Widget::make([
			'type' => 'progress',
			'class'=> 'card border-0 text-white bg-dark',
			'progressClass' => 'progress-bar',
			'value' => $productCount,
			'description' => 'Another Statistic.',
			'hint' => $productCount>75?'Try to stay under 75 products.':'Good. Good.',
		]),
	]);

@endphp

@section('content')

    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">Import Books from Excel</div>
                <div class="card-body">

                    @if(session('status'))

                        <div class="alert alert-success" role="alert">

                            {{ session('status') }}

                        </div>

                    @endif

                    <form action="import" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file">
                                <label class="custom-file-label" for="customFile">Choose .xlsx file</label>
                            </div>
                        </div>
                        <div class="form-group form-actions">
                            <button class="btn btn btn-primary" type="submit">

                                <span class="ladda-label">
                                    <i class="la la-cloud-upload"></i>
                                    Upload
                                </span>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('after_scripts')
        <script src="{{asset('js/jquery.min.js')}}"></script>

        <script>

            $('.custom-file-input').on('change',function(){
                let fileName = document.getElementById("file").files[0].name;
                alert(fileName)
                let label = $('.custom-file-label')
                label.text(fileName)
                label.css({'color': '#7C6AEF'})
            })

        </script>
    @endsection

@endsection
