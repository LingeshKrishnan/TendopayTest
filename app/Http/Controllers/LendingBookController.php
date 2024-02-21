<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Lendingdetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LendingBookController extends Controller
{
    //

    public function viewBooks()
    {
        $booksToView = DB::table('books')
            ->join('users', 'users.id', '=', 'books.user_id')
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->select('books.id', 'books.name', 'books.availability', 'books.author', 'users.name as username', 'users.id as userid', 'categories.name as category')
            ->get();
        return view('lendbooks', ['bookstoview' => $booksToView]);
    }

    public function getLendingCount()
    {
        $userId = Auth::id();
        $lendindDetailsData =  User::select('number_of_books_lended as booklended')->where('id', $userId)->get();
        $lendingCount = 0;
        foreach ($lendindDetailsData as $lendingData) {
            $lendingCount = $lendingData->booklended;
        }
        return $lendingCount;
    }

    public function getBookAvaialbility($bookId)
    {
        $bookData = Books::select('availability')->where('id', $bookId)->get();
        foreach ($bookData as $bookDetails) {
            $bookAvailability = $bookDetails->availability;
        }
        return $bookAvailability;
    }

    public function lendBook($bookId)
    {
        $lendingCount = $this->getLendingCount();
        $bookAvailability = $this->getBookAvaialbility($bookId);
        if ($lendingCount < 3 && $bookAvailability == 'yes') {
            $lendingDetails = new Lendingdetails();
            $userId = Auth::id();

            $lendingDetails->user_id = $userId;
            $lendingDetails->book_id = $bookId;
            $lendingDetails->lended_on = date('Y-m-d');

            $lendingDetails->save();

            $bookData = Books::find($bookId);
            $bookData->availability = 'no';
            $bookData->save();

            $userData = User::find($userId);
            $finalCount = $lendingCount + 1;
            $userData->number_of_books_lended = (string)$finalCount;
            $userData->save();

            return back()->with('success', 'Book Lended Successfully! Happy Reading');
        } else {
            if ($lendingCount >= 3) {
                return back()->with('warning', 'You Have Reached Maximum Limit to lend the books! Kindly return a book to lend the new Book!');
            }
            if ($bookAvailability == 'no') {
                return back()->with('warning', 'The Book is Unavailable now.Kindly Check after some time!');
            }
        }
    }
}
