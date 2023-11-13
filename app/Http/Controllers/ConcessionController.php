<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ConcessionController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index(Request $request)
    {
        $concessionsSnapshot = $this->database->getReference('tconcessions')->getSnapshot();
        $concessionsData = $concessionsSnapshot->getValue();
        if (is_array($concessionsData)) {
            foreach ($concessionsData as $concessionKey => $concessionData) {
                $concessions[] = array_merge(['id' => $concessionKey], $concessionData);
            }
        } else {
            $concessions = [];
        }

        $search = $request->input('search');
        if ($search) {
            $concessions = array_filter($concessions, function ($concession) use ($search) {
                return strpos(strtolower($concession['title']), strtolower($search)) !== false;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'oldest') {
            $concessions = array_reverse($concessions);
        }
    
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($concessions, ($currentPage - 1) * $perPage, $perPage);

        $concessions = new LengthAwarePaginator(
            $currentItems,
            count($concessions),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    

        return view('admin/concession/master', compact('concessions'));
    }

    public function toCashierConcessions(Request $request) {
        $concessionsSnapshot = $this->database->getReference('tconcessions')->getSnapshot();
        $concessionsData = $concessionsSnapshot->getValue();
        if (is_array($concessionsData)) {
            foreach ($concessionsData as $concessionKey => $concessionData) {
                $concessions[] = array_merge(['id' => $concessionKey], $concessionData);
            }
        } else {
            $concessions = [];
        }

        $search = $request->input('search');
        if ($search) {
            $concessions = array_filter($concessions, function ($concession) use ($search) {
                return strpos(strtolower($concession['title']), strtolower($search)) !== false;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'oldest') {
            $concessions = array_reverse($concessions);
        }
    
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($concessions, ($currentPage - 1) * $perPage, $perPage);

        $concessions = new LengthAwarePaginator(
            $currentItems,
            count($concessions),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    

        return view('admin/dashboard/cashier-concessions/cashier', compact('concessions'));
    }
     
    function checkoutConcessions()
    {
        return view('admin/dashboard/cashier-concessions/checkout');

    }
}
