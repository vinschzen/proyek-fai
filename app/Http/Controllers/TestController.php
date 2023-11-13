<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class TestController extends Controller
{
    private $database;

    public function __construct()
    {
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function create(Request $request) 
    {
        $this->database
            ->getReference('test/blogs/' . $request['title'])
            ->set([
                'title' => $request['title'] ,
                'content' => $request['content']
            ]);

        return response()->json('blog has been created');
    }

    public function index() 
    {
        return response()->json($this->database->getReference('test/blogs')->getValue());
    }

    public function edit(Request $request) 
    {
        $this->database->getReference('test/blogs/' . $request['title'])
            ->update([
                'content/' => $request['content']
            ]);

        return response()->json('blog has been edited');
    }

    public function delete(Request $request)
    {
        $this->database
            ->getReference('test/blogs/' . $request['title'])
            ->remove();

        return response()->json('blog has been deleted');
    }
}
