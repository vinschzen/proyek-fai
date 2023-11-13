<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class VoucherController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index(Request $request)
    {
        $vouchersSnapshot = $this->database->getReference('tvouchers')->getSnapshot();
        $vouchers = [];
    
        $vouchersData = $vouchersSnapshot->getValue();
        if (is_array($vouchersData)) {
            foreach ($vouchersData as $voucherKey => $voucherData) {
                $vouchers[] = array_merge(['id' => $voucherKey], $voucherData);
            }
        } else {
            $vouchers = [];
        }

        $search = $request->input('search');
        if ($search) {
            $vouchers = array_filter($vouchers, function ($voucher) use ($search) {
                return strpos(strtolower($voucher['title']), strtolower($search)) !== false;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'oldest') {
            $vouchers = array_reverse($vouchers);
        }
    
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($vouchers, ($currentPage - 1) * $perPage, $perPage);

        $vouchers = new LengthAwarePaginator(
            $currentItems,
            count($vouchers),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    

        return view('admin/voucher/master', compact('vouchers'));
    }
}
