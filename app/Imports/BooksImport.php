<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class BooksImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new Book([
            'name' => $row['Title'],
            'author' => $row['Author'] ? $row['Author'] : null,
            'isbn' => $row['ISBN'] ? $row['ISBN'] : null,
            'published_year' => $row['Published'] ? $row['Published'] : null,
            'consignment' => $row['Consignment'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
