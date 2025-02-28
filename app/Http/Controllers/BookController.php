<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Book::with(['user', 'category'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'writer' => 'required',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'required',
            'year' => 'required|integer',
        ]);

        $book = Book::create($request->all());
        return response()->json([
            'message' => "Buku berhasil dibuat",
            "book" => $book
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with('category')->findOrFail($id);
    $loan = $book->loans()->where('status', 'active')->first();

    $response = [
        'id' => $book->id,
        'title' => $book->title,
        'writer' => $book->writer,
        'category' => [
            'id' => $book->category->id,
            'name' => $book->category->name,
        ],
        'publisher' => $book->publisher,
        'year' => $book->year,
    ];

    if ($loan) {
        $response['loan'] = [
            'user' => [
                'id' => $book->user->id,
                'username' => $book->user->username,
                'name' => $book->user->name,
                'email' => $book->user->email,
                'phone' => $book->user->phone,
            ],
            'loan_date' => $loan->loan_date,
            'return_date' => $loan->return_date,
        ];
    }

    return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'writer' => 'required',
            'user_id' => 'exists:users,id',
            'category_id' => 'exists:categories,id',
            'publisher' => 'required',
            'year' => 'required|integer',
        ]);

        $book->update($request->all());

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'book' => $book
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus.',
            'book' => $book
        ], 200);
    }
}
