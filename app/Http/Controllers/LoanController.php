<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with(['book', 'user'])->get();

        return response()->json($loans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'loan_date' => 'required|date|date_format:Y-m-d',
            'return_date' => 'required|date|date_format:Y-m-d|after:loan_date',
            'status' => 'required|string',
        ]);

        $loan = Loan::create($request->all());

        return response()->json([
            'message' => 'Peminjaman berhasil dibuat.',
            'loan' => $loan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loan = Loan::with(['book:id,title,writer,publisher,year', 'user:id,username,name,email,phone']);

        return response()->json([
            "message" => "Tagihan ditemukan",
            'id' => $loan->id,
            'user' => [
                'id' => $loan->user->id,
                'username' => $loan->user->username,
                'name' => $loan->user->name,
                'email' => $loan->user->email,
                'phone' => $loan->user->phone,
            ],
            'book' => [
                'id' => $loan->book->id,
                'title' => $loan->book->title,
                'writer' => $loan->book->writer,
                'publisher' => $loan->book->publisher,
                'year' => $loan->book->year,
            ],
            'loan_date' => $loan->loan_date,
            'return_date' => $loan->return_date,
            'status' => $loan->status,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'book_id' => 'exists:books,id',
            'user_id' => 'exists:users,id',
            'loan_date' => 'date',
            'return_date' => 'date|after:loan_date',
            'status' => 'string',
        ]);

        $loan->update($request->all());

        return response()->json([
            'message' => 'Peminjaman berhasil diperbarui.',
            'loan' => $loan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return response()->json([
            'message' => 'Tagihan berhasil dihapus.',
            'tagihan' => $loan
        ], 200);
    }

    public function updateDates(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after:loan_date',
            'status' => 'required|string',
        ]);

        $loan->update($request->only('loan_date', 'return_date', 'status'));

        return response()->json([
            'message' => 'Tanggal dan status peminjaman berhasil diperbarui.',
            'loan' => [
                'id' => $loan->id,
                'loan_date' => $loan->loan_date,
                'return_date' => $loan->return_date,
                'status' => $loan->status,
            ]
        ], 200);
    }
}
