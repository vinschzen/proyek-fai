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
                return strpos(strtolower($voucher['name']), strtolower($search)) !== false;
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

    public function store(Request $request)
    {
        if ($request->type == "Ticket")
        {
            if (!$request->any_play) 
            {
                $rules[] = ['specific_play' => 'required'];
            }
            
            $rules = [
                'ticket_amount' => 'required|min:0',
                'discount' => 'required|numeric|min:0|max:100',
            ];
            
            $messages = [
                'ticket_amount.required' => 'The ticker amount is required.',
                'ticket_amount.min' => 'The ticker amount cant be below 0.',
                'specific_play.required' => 'The specific play is required',
                'discount.required' => 'discount is required.',
                'discount.numeric' => 'discount must be a numeric value.',
                'discount.min' => 'discount can\'t be below 0.',
                'discount.max' => 'discount can\'t be above 100.',
            ];
            
            $request->validate($rules, $messages); 
    
            $data = $request->only(['name', 'type', 'validity_from', 'validity_until', 'ticket_amount', 'discount']);
    
            if ($request->any_play) 
            {
                $data['specific_play'] = 'Any';
            }
            else {
                $data['specific_play'] = $request->specific_play;
            }
        }
        else {
            $rules = [
                'if_bought_id.*' => 'required',
                'if_bought_amount.*' => 'required',
                'then_get_id.*' => 'required',
                'then_get_amount.*' => 'required',
                'discount' => 'required|numeric|min:0|max:100',
            ];
            
            $messages = [
                'if_bought_id.*.required' => 'At least one "If Bought" product must be selected.',
                'if_bought_amount.*.required' => 'Amount must be specified for all "If Bought" products.',
                'then_get_id.*.required' => 'At least one "Then Get" product must be selected.',
                'then_get_amount.*.required' => 'Amount must be specified for all "Then Get" products.',
                'discount.required' => 'discount is required.',
                'discount.numeric' => 'discount must be a numeric value.',
                'discount.min' => 'discount can\'t be below 0.',
                'discount.max' => 'discount can\'t be above 100.',
            ];
            
            $request->validate($rules, $messages); 
            
            
            $data = $request->only(['name', 'type', 'validity_from', 'validity_until', 'discount']);
            $ifBought = array_combine($request->input('if-bought-id'), $request->input('if-bought-amount'));
            if ($request->input('then-get-id'))
            {
                $thenGet = array_combine($request->input('then-get-id'), $request->input('then-get-amount'));
                $data['then_get'] = $thenGet;
            }
            $data['if_bought'] = $ifBought;
        }

        $data['created_at'] = ['.sv' => 'timestamp'];
        $data['updated_at'] = ['.sv' => 'timestamp'];

        $vouchersRef = $this->database->getReference('tvouchers')->push();
        $vouchersRef->set($data);

        return redirect()->route('toMasterVoucher')->with('success', 'Voucher added successfully');
    }

    public function edit($id, Request $request)
    {

        $rules = [
            'name' => 'required|string|max:255',
            'validity_from' => 'required|date',
            'validity_until' => 'required|date|after:validity_from',
        ];
        
        $messages = [
            'validity_from.required' => 'The validity from date is required.',
            'validity_until.required' => 'The validity until date is required.',
            'validity_from.date' => 'The validity from must be a valid date.',
            'validity_until.date' => 'The validity until must be a valid date.',
            'validity_until.after' => 'The validity until must be a date after the validity from.',
        ];

        $request->validate($rules, $messages);
                    
        $data = $request->only(['name', 'validity_from', 'validity_until']);

        $data['updated_at'] = ['.sv' => 'timestamp'];

        $vouchersRef = $this->database->getReference("tvouchers/$id");
        $vouchersRef->update($data);

        return redirect()->route('toMasterVoucher')->with('success', 'Voucher edited successfully');
    }

    public function viewadd() {
        return view('admin/voucher/add');
    }

    public function viewadddetails(Request $request) {

        $rules = [
            'name' => 'required|string|max:255',
            'validity_from' => 'required|date',
            'validity_until' => 'required|date|after:validity_from',
        ];
        
        $messages = [
            'validity_from.required' => 'The validity from date is required.',
            'validity_until.required' => 'The validity until date is required.',
            'validity_from.date' => 'The validity from must be a valid date.',
            'validity_until.date' => 'The validity until must be a valid date.',
            'validity_until.after' => 'The validity until must be a date after the validity from.',
        ];

        $request->validate($rules, $messages);
        $vouchers = $request->only(['name', 'type', 'validity_from', 'validity_until']);

        if ($request->type == "Ticket") 
            $reference = "tplays";
        else {
            $reference = "tconcessions";
        }

        $tablesSnapshot = $this->database->getReference($reference)->getSnapshot();
        $tables = [];
        
        $tablesData = $tablesSnapshot->getValue();
        if (is_array($tablesData)) {
            foreach ($tablesData as $tableKey => $tableData) {
                $tables[] = array_merge(['id' => $tableKey], $tableData);
                
            }
    
            if ($request->type == "Concession")
            {
                array_walk($tables, function (& $item) {
                    $item['title'] = $item['name'];
                    unset($item['name']);
                 });
            }
            
        } else {
            $tables = [];
        }

        return view('admin/voucher/add-details', compact('vouchers', 'tables'));
    }

    public function viewedit($id) 
    {
        $voucherSnapshot = $this->database->getReference("tvouchers/$id")->getSnapshot();

        if (!$voucherSnapshot->exists()) {
            abort(404); 
        }

        
        $voucher = array_merge(['id' => $id], $voucherSnapshot->getValue());

        if ($voucher['type'] == 'Ticket')
        {
            $specificPlay = $voucherSnapshot->getValue()['specific_play'];
            if ($specificPlay == "Any")
            {
                $voucher['specific_play_title'] = "Any";
            }
            else {
                $playsReference = $this->database->getReference('tplays/' . $specificPlay);
                $playSnapshot = $playsReference->getSnapshot();
                $playData = $playSnapshot->getValue();
                $voucher['specific_play_title'] = $playData['title'];
            }

        }
        else {

            foreach ($voucher['if_bought'] as $key => $value) {
                $concessionSnapshot = $this->database->getReference("tconcessions/$key")->getSnapshot();
                $concessionData = $concessionSnapshot->getValue();

                $voucher['if_bought_data'][] = 
                [
                    'image' => $concessionData['image'],
                    'name' => $concessionData['name'],
                    'category' => $concessionData['category'],
                    'amount' => $value,
                ];
            }


            foreach ($voucher['then_get'] as $key => $value) {
                $concessionSnapshot = $this->database->getReference("tconcessions/$key")->getSnapshot();
                $concessionData = $concessionSnapshot->getValue();

                $voucher['then_get_data'][] = 
                [   
                    'image' => $concessionData['image'],
                    'name' => $concessionData['name'],
                    'category' => $concessionData['category'],
                    'amount' => $value,
                ];
            }
        }
    
        return view('admin/voucher/edit', compact('voucher'));
    }

    public function destroy($id)
    {
        $voucherRef = $this->database->getReference('tvouchers')->getChild($id);

        if ($voucherRef->getSnapshot()->exists()) {
            // $playRef->remove();

            $voucherRef->update([
                'is_deleted' => true,
                'deleted_at' => time(), 
            ]);
            return redirect()->route('toMasterVoucher')->with('success', 'Voucher deleted successfully');
        }

        return redirect()->route('toMasterVoucher')->with('error', 'Voucher not found');
    }
}
