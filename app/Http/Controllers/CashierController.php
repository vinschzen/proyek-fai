<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class CashierController extends Controller
{
    protected $auth;
    protected $database;
    protected $storage;

    public function __construct(Auth $auth, Database $database, Storage $storage)
    {
        $this->auth = app('firebase.auth');
        $this->database = $database;
        $this->storage = $storage;
    }

    public function toCashierTickets(Request $request) {
        $schedulesSnapshot = $this->database->getReference('tschedules')->getSnapshot();
        $schedules = [];
        
        $schedulesData = $schedulesSnapshot->getValue();

        if (is_array($schedulesData)) {
            foreach ($schedulesData as $scheduleKey => $scheduleData) {
                
                $playsReference = $this->database->getReference('tplays/' . $scheduleData['playid']);
                $playSnapshot = $playsReference->getSnapshot();
                $playData = $playSnapshot->getValue();
                
                $scheduleData['title'] = $playData['title'];

                $schedules[] = array_merge(['id' => $scheduleKey], $scheduleData);

            }
        } else {
            $schedules = [];
        }
        
        $search = $request->input('search');
        if ($search) {
            $schedules = array_filter($schedules, function ($schedule) use ($search) {
                return strpos(strtolower($schedule['title']), strtolower($search)) !== false;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'newest') {
            $schedules = array_reverse($schedules);
        }
    
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($schedules, ($currentPage - 1) * $perPage, $perPage);

        $schedules = new LengthAwarePaginator(
            $currentItems,
            count($schedules),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    

        return view('admin/dashboard/cashier-tickets/cashier', compact('schedules'));
    }

    function checkoutTickets($scheduleid, Request $req)
    {
        $users = [];

        foreach ($this->auth->listUsers() as $user) {
            $role = $user->customClaims['role'];
            $status = $user->disabled;
            
            $displayRole = match($role) {
                "0" => "User",
                "1" => "Staff",
                "2" => "Admin",
                default => null
            };

            $displayStatus = match($status) {
                true => "Disabled",
                false => "Enabled",
                default => null
            };

            $users[] = [
                'uid' => $user->uid,
                'displayName' => $user->displayName,
                'email' => $user->email,
                'role' => $displayRole,
                'status' => $displayStatus,
            ];
        }

        $tables = $users;

        $scheduleSnapshot = $this->database->getReference("tschedules/$scheduleid")->getSnapshot();
        
        if (!$scheduleSnapshot->exists()) {
            abort(404); 
        }

        $schedule = array_merge(['id' => $scheduleid], $scheduleSnapshot->getValue());

        $playid = $schedule['playid'];
        $playsSnapshot = $this->database->getReference("tplays/$playid")->getSnapshot();
        
        if (!$playsSnapshot->exists()) {
            abort(404); 
        }

        $play = array_merge(['id' => $playid], $playsSnapshot->getValue());

        $hseatingsRef = $this->database->getReference('hseatings');
        $query = $hseatingsRef->orderByChild('schedule_id')->equalTo($scheduleid);
        $hseatings = $query->getValue();

        $dseatingsRef = $this->database->getReference('dseatings');
        $query = $dseatingsRef->orderByChild('hseatings')->equalTo(array_keys($hseatings)[0]);
        $seatings = $query->getValue();
    
        return view('admin/dashboard/cashier-tickets/checkout', compact('tables','play', 'schedule', 'seatings'));
    }

    function userbuytickets($scheduleid, Request $request)
    {
        $rules = [
            'seats' => 'array|min:1',
            'seats.*' => 'nullable|string|max:255',
            'total' => 'required|int'
        ];

        if (!$request->anonymous) 
        {
            $rules['specific_user'] = 'required|string|max:255';
        }

        $messages = [
            'specific_user.required' => 'The specific user field is required.',
            'total.required' => 'At least one seat must be selected !',
            'seats.array' => 'The seats must be in an array.',
        ];


        $request->validate($rules, $messages);

        $discount = 0;

        if ($request->voucher) {
            $vouchersRef = $this->database->getReference('tvouchers');
            $query = $vouchersRef->orderByChild('name')->equalTo($request->voucher);
            $voucher = $query->getValue();

            foreach ($voucher as $key => $item) {
                $voucherArray = array_merge(['id' => ltrim($key, '-')], $item);
            }

            if (!$voucher) {
                return redirect()->back()->with('error', 'Voucher incorrect');
            }

            if ($voucherArray["type"] !== "Ticket")
            {
                return redirect()->back()->with('error', 'Voucher type invalid');
            }


            $currentDate = date("Y-m-d");
            if (strtotime($voucherArray['validity_from']) <= strtotime($currentDate) && strtotime($currentDate) <= strtotime($voucherArray['validity_until']) ) {

            } 
            else {
                return redirect()->back()->with('error', 'Voucher has expired');
    
            }

            
            $seats = ((int) $request->total) / 5000;
            
            if ($voucherArray['ticket_amount'] < $seats)
            {
                return redirect()->back()->with('error', 'Voucher conditions not met');
            }
            
            
            $discount = $voucherArray['discount'] / 100;
            $data['voucher'] = $voucherArray;
        }
        
        $total = (int) $request->total - ((int) $request->total * $discount);

        $data['total'] = $total;

        if ($request->anonymous) 
        {
            $data['specific_user'] = 'Anonymous';
        }
        else {
            $potongSaldo = $this->potongSaldo($request->specific_user, $total);
            if (!$potongSaldo) return redirect()->back()->with('msg', 'Saldo tidak cukup');
            $data['specific_user'] = $request->specific_user;
        }
      

        $data['schedule_id'] = $scheduleid;
        $data['created_at'] = ['.sv' => 'timestamp'];
        $data['updated_at'] = ['.sv' => 'timestamp'];

        $hticketsRef = $this->database->getReference('htickets')->push();
        $hticketsRef->set($data);

        $hseatingsRef = $this->database->getReference('hseatings');
        $query = $hseatingsRef->orderByChild('schedule_id')->equalTo($scheduleid);
        $hseatings = $query->getValue();

        foreach ($request->seats as $key => $value) {
            if ($value)
            {
                $dtickets['htickets'] = $hticketsRef->getKey();;
                $dtickets['seat'] = $value;
                $dticketsRef = $this->database->getReference('dtickets')->push();
                $dticketsRef->set($dtickets);

                $dseatings['hseatings'] = array_keys($hseatings)[0];
                $dseatings['seat'] = $value;
                $dseatingsRef = $this->database->getReference('dseatings')->push();
                $dseatingsRef->set($dseatings);
            }
        }

        $this->refreshLoggedIn($request);

        return redirect()->route('toTicket', $hticketsRef->getKey());
    }

    function buytickets($scheduleid, Request $request)
    {
        $rules = [
            'seats' => 'array|min:1',
            'seats.*' => 'nullable|string|max:255',
            'total' => 'required|int'
        ];

        if (!$request->anonymous) 
        {
            $rules['specific_user'] = 'required|string|max:255';
        }

        $messages = [
            'specific_user.required' => 'The specific user field is required.',
            'total.required' => 'At least one seat must be selected !',
            'seats.array' => 'The seats must be in an array.',
        ];


        $request->validate($rules, $messages);

        $discount = 0;

        if ($request->voucher) {
            $vouchersRef = $this->database->getReference('tvouchers');
            $query = $vouchersRef->orderByChild('name')->equalTo($request->voucher);
            $voucher = $query->getValue();

            foreach ($voucher as $key => $item) {
                $voucherArray = array_merge(['id' => ltrim($key, '-')], $item);
            }

            if (!$voucher) {
                return redirect()->back()->with('error', 'Voucher incorrect');
            }

            if ($voucherArray["type"] !== "Ticket")
            {
                return redirect()->back()->with('error', 'Voucher type invalid');
            }


            $currentDate = date("Y-m-d");
            if (strtotime($voucherArray['validity_from']) <= strtotime($currentDate) && strtotime($currentDate) <= strtotime($voucherArray['validity_until']) ) {

            } 
            else {
                return redirect()->back()->with('error', 'Voucher has expired');
    
            }

            
            $seats = ((int) $request->total) / 5000;
            
            if ($voucherArray['ticket_amount'] < $seats)
            {
                return redirect()->back()->with('error', 'Voucher conditions not met');
            }
            
            
            $discount = $voucherArray['discount'] / 100;
            $data['voucher'] = $voucherArray;
        }
        
        $total = (int) $request->total - ((int) $request->total * $discount);

        $data['total'] = $total;

        if ($request->anonymous) 
        {
            $data['specific_user'] = 'Anonymous';
        }
        else {
            $potongSaldo = $this->potongSaldo($request->specific_user, $total);
            if (!$potongSaldo) return redirect()->back()->with('msg', 'Saldo tidak cukup');
            $data['specific_user'] = $request->specific_user;
        }
      

        $data['schedule_id'] = $scheduleid;
        $data['created_at'] = ['.sv' => 'timestamp'];
        $data['updated_at'] = ['.sv' => 'timestamp'];

        $hticketsRef = $this->database->getReference('htickets')->push();
        $hticketsRef->set($data);

        $hseatingsRef = $this->database->getReference('hseatings');
        $query = $hseatingsRef->orderByChild('schedule_id')->equalTo($scheduleid);
        $hseatings = $query->getValue();

        foreach ($request->seats as $key => $value) {
            if ($value)
            {
                $dtickets['htickets'] = $hticketsRef->getKey();;
                $dtickets['seat'] = $value;
                $dticketsRef = $this->database->getReference('dtickets')->push();
                $dticketsRef->set($dtickets);

                $dseatings['hseatings'] = array_keys($hseatings)[0];
                $dseatings['seat'] = $value;
                $dseatingsRef = $this->database->getReference('dseatings')->push();
                $dseatingsRef->set($dseatings);
            }
        }

        $this->refreshLoggedIn($request);

        return redirect()->route('toCashierTickets')->with('success', 'Tickets ordered successfully');
    }

    function buyconcessions(Request $request)
    {
        $rules = [];
        
        if (!$request->anonymous) 
        {
            $rules['specific_user'] = 'required|string|max:255';
        }

        $messages = [
            'specific_user.required' => 'The specific user field is required.',
        ];

        $request->validate($rules, $messages);

        $cart = $request->session()->get('cashier');

        $display = [];
        foreach ($cart as $key => $value) {
            $concessionsSnapshot = $this->database->getReference("tconcessions/$key")->getSnapshot();
            $concessionsData = $concessionsSnapshot->getValue();
            $item = array_merge(['id' => $key, 'qty' => $value['qty']], $concessionsData);
            $item['image'] = $this->storage->getBucket(env('FIREBASE_STORAGE_BUCKET'))->object( $concessionsData['image'] )->signedUrl(new \DateTime('tomorrow'));
            $display[] = $item;
        }

        $cart = $display;

        $discount = 0;

        if ($request->voucher) {
            $vouchersRef = $this->database->getReference('tvouchers');
            $query = $vouchersRef->orderByChild('name')->equalTo($request->voucher);
            $voucher = $query->getValue();

            foreach ($voucher as $key => $item) {
                $voucherArray = array_merge(['id' => ltrim($key, '-')], $item);
            }

            if (!$voucher) {
                return redirect()->back()->with('error', 'Voucher incorrect');
            }

            if ($voucherArray["type"] !== "Concession")
            {
                return redirect()->back()->with('error', 'Voucher type invalid');
            }


            $currentDate = date("Y-m-d");
            if (strtotime($voucherArray['validity_from']) <= strtotime($currentDate) && strtotime($currentDate) <= strtotime($voucherArray['validity_until']) ) {

            } 
            else {
                return redirect()->back()->with('error', 'Voucher has expired');
    
            }

            if (!$this->checkVoucherValidity($cart, $voucherArray['if_bought']))
            {
                return redirect()->back()->with('error', 'Voucher conditions not met');
            }

            
            foreach ($voucherArray['then_get'] as $key => $value) {
                $concessionSnapshot = $this->database->getReference("tconcessions/".$key)->getSnapshot();
                $currentStock = $concessionSnapshot->getChild('stock')->getValue();
                $newStock = $currentStock - $value;
                $this->database->getReference("tconcessions/".$key."/stock")->set($newStock);
            }

            $discount = $voucherArray['discount'] / 100;
            $data['voucher'] = $voucherArray;
        }

        $total = $request->total - ($request->total * $discount);

        if ($request->anonymous) 
        {
            $data['specific_user'] = 'Anonymous';
        }
        else {
            $potongSaldo = $this->potongSaldo($request->specific_user, (int) $total);
            if (!$potongSaldo) return redirect()->back()->with('msg', 'Saldo tidak cukup');
            $data['specific_user'] = $request->specific_user;
        }
        
        $data['total'] = $total;
        $data['created_at'] = ['.sv' => 'timestamp'];
        $data['updated_at'] = ['.sv' => 'timestamp'];


        $hordersRef = $this->database->getReference('horder')->push();
        $hordersRef->set($data);

        foreach ($cart as $key => $value) {
            if ($value)
            {
                $dorders['horder'] = $hordersRef->getKey();;
                $dorders['item'] = $value['id'];

                $concessionSnapshot = $this->database->getReference("tconcessions/".$value['id'])->getSnapshot();
                $currentStock = $concessionSnapshot->getChild('stock')->getValue();
                $newStock = $currentStock - $value['qty'];
                $this->database->getReference("tconcessions/".$value['id']."/stock")->set($newStock);

                $dorders['qty'] = $value['qty'];
                $dorders['price'] = $value['price'];

                $dordersRef = $this->database->getReference('dorder')->push();
                $dordersRef->set($dorders);
            }
        }

        $this->refreshLoggedIn($request);

        $request->session()->forget("cashier");

        return redirect()->route('toCashierConcessions')->with('success', 'Concession ordered successfully');
    }

    function calculateTimeRange($initialTime, $incrementMinutes) {
        $initialTimestamp = strtotime('1970-01-01 ' . $initialTime);
    
        $endTime = $initialTimestamp + ($incrementMinutes * 60);
    
        $formattedInitialTime = date('H:i', $initialTimestamp);
        $formattedEndTime = date('H:i', $endTime);
    
        $rangeString = $formattedInitialTime . ' - ' . $formattedEndTime;
        
        return $rangeString;
    }

    public function potongSaldo($id, $amount)
    {
        $user = $this->auth->getUser($id);

        $currentSaldo = $user->customClaims['saldo'] ?? 0;
        $currentRole = $user->customClaims['role'] ?? 0;
        
        if ($currentSaldo - $amount < 0) {
            return false;
        }

        $newSaldo = $currentSaldo - $amount;

        $this->auth->setCustomUserClaims($id, ['role' => $currentRole,'saldo' => $newSaldo]);

        return $newSaldo;
    }


    public function refreshLoggedIn(Request $request)
    {
        $loggedIn = $request->session()->get('user');

        $user = $this->auth->getUser($loggedIn->uid);

        $request->session()->put('user', $user);;
    }

    function checkoutConcessions()
    {
        $users = [];

        foreach ($this->auth->listUsers() as $user) {
            $role = $user->customClaims['role'];
            $status = $user->disabled;
            
            $displayRole = match($role) {
                "0" => "User",
                "1" => "Staff",
                "2" => "Admin",
                default => null
            };

            $displayStatus = match($status) {
                true => "Disabled",
                false => "Enabled",
                default => null
            };

            $users[] = [
                'uid' => $user->uid,
                'displayName' => $user->displayName,
                'email' => $user->email,
                'role' => $displayRole,
                'status' => $displayStatus,
            ];
        }

        $tables = $users;

        $cashier = session('cashier') ?? [];

        $display = [];
        foreach ($cashier as $key => $value) {
            $concessionsSnapshot = $this->database->getReference("tconcessions/$key")->getSnapshot();
            $concessionsData = $concessionsSnapshot->getValue();
            $cart = array_merge(['id' => $key, 'qty' => $value['qty']], $concessionsData);
            $cart['image'] = $this->storage->getBucket(env('FIREBASE_STORAGE_BUCKET'))->object( $concessionsData['image'] )->signedUrl(new \DateTime('tomorrow'));
            $display[] = $cart;
        }

        return view('admin/dashboard/cashier-concessions/checkout', compact('tables', 'display'));

    }

    public function checkVoucherValidity($cart, $vouchers)
    {        
        foreach ($vouchers as $voucherId => $voucherQty) {
            $cartItem = collect($cart)->firstWhere('id', $voucherId);
    
            if (!$cartItem || $cartItem['qty'] < $voucherQty) {
                return false;
            }
        }
    
        return true;
    }


}