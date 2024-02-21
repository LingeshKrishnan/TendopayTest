<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function addBook()
    {
        $categories = DB::table('categories')->pluck('name', 'id')->all();
        return view('addbooks', ['categories' => $categories]);
    }

    public function getCategories()
    {
        $categories = DB::table('categories')->lists('name');
        return view('addbooks', ['categories' => $categories]);
    }

    public function postBook(Request $request)
    {
        $books = new Books();
        $user = new User();
        $userId = Auth::id();
        $request->validate(
            [
                'bookname' => ['required', 'max:255'],
                'author' => ['required', 'max:255'],
            ]
        );

        $books->name = $request->bookname;
        $books->author = $request->author;
        $books->user_id = $userId;
        $books->category_id = $request->category;

        $books->save();

        $bookCount = Books::where('user_id', '=', $userId);

        $userData = User::find($userId);
        $userData->number_of_books_added = $bookCount->count();
        $userData->save();


        return back()->with('success', 'Book Added Successfully!');
    }

    public function ownedBooks()
    {
        $userId = Auth::id();
        $bookCount = Books::where('user_id', '=', $userId);

        $userData = User::find($userId);
        $userData->number_of_books_added = $bookCount->count();
        $userData->save();
        $booksOwned = DB::table('books')->get()->where('user_id', '=', $userId);
        return view('booksowned', ['booksowned' => $booksOwned]);
    }

    public function deleteBook($bookId)
    {
        DB::table('books')->delete($bookId);
        return back()->with('success', 'Book Removed Successfully!');
    }
}
