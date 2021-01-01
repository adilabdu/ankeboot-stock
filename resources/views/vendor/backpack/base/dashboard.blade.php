@extends(backpack_view('blank'))

<?php

    use App\Models\Book;
    use App\Models\Stock;use Backpack\CRUD\app\Library\Widget;

    $booksCount = Book::all()->count();
    $consignment = Book::where('consignment', true)->count();
    $cheques = Book::where('consignment', false)->count();

    $stocks = Stock::all();

    $latestBook = Book::orderBy('created_at', 'DESC')->first();
    $expensiveBook = Stock::orderBy('cost_price', 'DESC')->first();

    $sum = 0;
    $total = 0;
    $averagePrice = 0;
    foreach($stocks as $stock) {
        $sum += $stock->cost_price * $stock->received_amount;
        $total += $stock->received_amount;
    }

    if($total > 0) {
        $averagePrice = $sum / $total;
    }

    if($expensiveBook) {
        $expensiveBook = $expensiveBook->book;
    } else {
        $expensiveBook = 'N/A';
    }

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
		    ->value($expensiveBook == 'N/A' ? 'N/A' : $expensiveBook->name . ' ('. $expensiveBook->meanPrice() .' ETB)')
		    ->progressClass('progress-bar')
		    ->description('Most Expensive Book')
		    // ->progress(30)
		    ->hint('Mean Cost Price: <span class="text-white">' . ($total == 0 ? 'N/A' : $averagePrice . ' ETB') . '</span>'),

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

?>

@section('content')

    <div class="row">
        <div class="col-sm-5">
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
                                <label class="custom-file-label" for="customFile">Choose .xlsx, .csv file</label>
                            </div>
                            <p style="margin: .25rem auto; color: #73818f; font-size: 0.9em;">
                                Excel file's heading <b>must</b> be <code>Title, Author, Published, ISBN, Consignment</code>;
                                in that order.
                            </p>
                        </div>
                        <div class="form-group form-actions">
                            <button disabled class="upload-btn btn btn btn-primary" type="submit">
                                    <i class="la la-cloud-upload"></i>
                                    Upload
                            </button>
                            <button type="button" class="cancel-btn btn btn-default">
                                <i class="la la-ban"></i>
                                Cancel
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

            let input = $('.custom-file-input')
            let label = $('.custom-file-label')

            input.on('change',function(){
                let fileName = document.getElementById("file").files[0].name;
                label.text(fileName)
                $('.upload-btn').attr('disabled', false)
            })

            $('.cancel-btn').on('click', function() {
                console.log('hello')
                input.val('')
                label.text('Choose .xlsx, .csv file')
                $('.upload-btn').attr('disabled', true)
            })

        </script>
    @endsection

@endsection
