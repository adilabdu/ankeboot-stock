<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Throwable;

//HeadingRowFormatter::default('none');

class BooksImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation
{

    use Importable, SkipsErrors;

    public function model(array $row)
    {
        return new Book([
            'name' => $row['title'],
            'author' => $row['author'] ? $row['author'] : null,
            'isbn' => $row['isbn'] ? $row['isbn'] : null,
            'published_year' => $row['published'] ? $row['published'] : null,
            'consignment' => $row['consignment'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }


    public function rules(): array
    {
        return [
            '*.name' => 'unique|required|min:3|max:255',
            '*.author' => 'required',
            '*.isbn' => 'unique:books|integer|nullable',
            '*.published' => 'integer',
            '*.consignment' => 'required',
        ];
    }
}
