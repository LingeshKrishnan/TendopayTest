<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function viewReviews(Request $request)
    {
        $reviewsToView = DB::table('reviews')
            ->join('users', 'users.id', '=', 'reviews.user_id')
            ->join('books', 'books.id', '=', 'reviews.book_id')
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->select('reviews.id', 'reviews.comments', 'reviews.ratings', 'books.id as bookid', 'books.name', 'books.author', 'users.name as username', 'users.id as userid', 'categories.name as category')
            ->where('reviews.book_id', '=', $request['bookId'])
            ->get();
        $bookData = Books::select('name')->where('id', '=', $request['bookId'])->get();
        $bookName = '';
        foreach ($bookData as $bookDetails) {
            $bookName = $bookDetails->name;
        }
        return view('addreview', ['bookstoview' => $reviewsToView, 'bookname' => $bookName]);
    }

    public function addReview(Request $request)
    {
        $books = new Books();
        $reviews = new Reviews();
        $user = new User();
        $userId = Auth::id();
        $request->validate(
            [
                'bookname' => ['required', 'max:255'],
                'comment' => ['required', 'max:255'],
            ]
        );

        $reviews->comments = $request->comment;
        $reviews->ratings = $request->ratings;
        $reviews->user_id = $userId;
        $reviews->book_id = $request->bookid;

        $reviews->save();

        return back()->with('success', 'Review Added Successfully!');
    }

    public function showReview(Request $request)
    {
        $bookId = $request['bookId'];
        $reviewsToView = DB::table('reviews')
            ->join('users', 'users.id', '=', 'reviews.user_id')
            ->join('books', 'books.id', '=', 'reviews.book_id')
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->select('reviews.id', 'reviews.comments', 'reviews.ratings', 'books.id as bookid', 'books.name', 'books.author', 'users.name as username', 'users.id as userid', 'categories.name as category')
            ->where('reviews.book_id', '=', $bookId)
            ->get();
        return view('viewreview', ['reviews' => $reviewsToView]);
    }
}
