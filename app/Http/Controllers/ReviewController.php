<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['book', 'user'])->get();

        return response()->json($reviews);
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($request->all());

        return response()->json([
            'message' => 'Review berhasil dibuat.',
            'review' => $review
        ], 201);
    }

    public function show($id)
    {
        $review = Review::with(['book:id,title', 'user:id,name'])->findOrFail($id);

        return response()->json([
            'id' => $review->id,
            'book' => [
                'id' => $review->book->id,
                'title' => $review->book->title,
            ],
            'user' => [
                'id' => $review->user->id,
                'name' => $review->user->name,
            ],
            'rating' => $review->rating,
            'comment' => $review->comment,
        ]);
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'rating' => 'integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($request->all());

        return response()->json([
            'message' => 'Review berhasil diperbarui.',
            'review' => $review
        ], 200);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json([
            'message' => 'Review berhasil dihapus.',
            'review' => $review
        ], 200);
    }
}