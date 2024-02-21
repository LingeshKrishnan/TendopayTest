<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\Lendingdetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnBookController extends Controller
{
    public function viewBooks()
    {
        $userId = Auth::id();
        $booksToView = DB::table('books')
            ->join('lendingdetails', 'lendingdetails.book_id', '=', 'books.id')
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->select('books.id', 'books.name', 'books.availability', 'books.author', 'lendingdetails.lended_on', 'lendingdetails.id as lendingid', 'categories.name as category')
            ->where('lendingdetails.returned', '=', 'no')
            ->where('lendingdetails.user_id', '=', $userId)
            ->get();
        return view('lendinghistory', ['bookstoview' => $booksToView]);
    }
   
    public function returnBook($lendingId)
    {
        $lendingBookController = new LendingBookController();

        $lendingCount = $lendingBookController->getLendingCount();
        $lendingDetails = Lendingdetails::find($lendingId);
        $bookId = $lendingDetails->book_id;
        $bookAvailability = $lendingBookController->getBookAvaialbility($bookId);
        if ($bookAvailability == 'no') {
            $lendingDetails->returned = 'yes';
            $lendingDetails->save();
            $bookData = Books::find($bookId);
            $bookData->availability = 'yes';
            $bookData->save();
            $userId = Auth::id();
            $userData = User::find($userId);
            $finalCount = $lendingCount - 1;
            $userData->number_of_books_lended = (string)$finalCount;
            $userData->save();
            return back()->with('success', 'Book Returned Successfully! Thank you Returning the Book!');
        } else {
            return back()->with('warning', 'Something Error in Returning the book. Kindly check the availability of the Book!');
        }
    }
}
