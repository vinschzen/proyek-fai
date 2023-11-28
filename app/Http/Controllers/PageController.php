<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PageController extends Controller
{
    protected $auth;
    protected $database;

    public function __construct(Auth $auth, Database $database)
    {
        $this->auth = app('firebase.auth');
        $this->database = $database;

    }

    public function toHome() {
        $tschedules = $this->database->getReference('tschedules')
                ->orderByChild('date')
                ->startAt(date('Y-m-d'))
                ->getValue();

        $playIds = collect($tschedules)->pluck('playid')->unique()->toArray();
        $plays = [];

        foreach ($playIds as $playId) {
            $result = $this->database->getReference('tplays')
                ->orderByKey()
                ->equalTo($playId)
                ->getValue();
            
            

            if (!empty($result)) {
                $result[$playId]['id'] = $playId;
                $plays = array_merge($plays, $result);
            }
        }

        return view('user/home', compact('plays'));
    }

    public function playDetails($id) {
        $play = $this->database->getReference("tplays/$id")->getValue();
        $schedules = $this->database->getReference('tschedules')
                ->orderByChild('playid')
                ->equalTo($id)
                ->getValue();

        foreach ($schedules as $id => $data) {
            $arr[] = array_merge(['id' => $id], $data);
        }

        $schedules = $arr;

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

        return view('user/plays', compact('play', 'schedules'));
    }

    function toCheckout($scheduleid, Request $req)
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
    
        return view('user/checkout', compact('tables','play', 'schedule', 'seatings'));
    }


    public function toLogin() {
        return view('login');
    }

    public function toProfile(Request $request) {
        $hticketsSnapshot = $this->database->getReference('htickets');
        $htickets = [];
        
        $uid = $request->session()->get('user')->uid;
        $hticketsData = $hticketsSnapshot->orderByChild('specific_user')->equalTo($uid)->getValue();

        if (is_array($hticketsData)) {
            foreach ($hticketsData as $hticketKey => $hticketData) {
                if ($hticketData['specific_user'] != 'Anonymous') 
                {
                    $hticketData['specific_user'] = $this->auth->getUser($hticketData['specific_user'])->displayName;
                }

                $schedulesReference = $this->database->getReference('tschedules/' . $hticketData['schedule_id']);
                $schedulesSnapshot = $schedulesReference->getSnapshot();
                $schedulesData = $schedulesSnapshot->getValue();
                
                $hticketData['date'] = $schedulesData['date'];
                $hticketData['theater'] = $schedulesData['theater'];
                $hticketData['time_end'] = $schedulesData['time_end'];
                $hticketData['time_start'] = $schedulesData['time_start'];

                $playsReference = $this->database->getReference('tplays/' . $schedulesData['playid']);
                $playsSnapshot = $playsReference->getSnapshot();
                $playsData = $playsSnapshot->getValue();
                $hticketData['title'] = $playsData['title'];
                $hticketData['poster'] = $playsData['poster'];
                $hticketData['description'] = $playsData['description'];
                $hticketData['age_rating'] = $playsData['age_rating'];

                $htickets[] = array_merge(['id' => $hticketKey], $hticketData);
            }
        } else {
            $htickets = [];
        }
        
        $dateFrom = $request->input('date-from');
        $dateUntil = $request->input('date-until');

        if ($dateFrom || $dateUntil) {
            $htickets = array_filter($htickets, function ($hticket) use ($dateFrom, $dateUntil) {
                $ticketDate = strtotime($hticket['date']);
        
                if ($dateFrom && $dateUntil) {
                    if ($ticketDate < strtotime($dateFrom) || $ticketDate > strtotime($dateUntil)) {
                    }
                } elseif ($dateFrom) {
                    if ($ticketDate < strtotime($dateFrom)) {
                        return false; 
                    }
                } elseif ($dateUntil) {
                    if ($ticketDate > strtotime($dateUntil)) {
                        return false; 
                    }
                }
                return true;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'oldest') {
            $htickets = array_reverse($htickets);
        }
    
        $perPage = 6;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($htickets, ($currentPage - 1) * $perPage, $perPage);

        $htickets = new LengthAwarePaginator(
            $currentItems,
            count($htickets),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        ); 

        return view('user/profile', compact('htickets'));
    }

    public function toConcessions(Request $request) {
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
                return strpos(strtolower($concession['name']), strtolower($search)) !== false;
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

        return view('user/concessions', compact('concessions'));
    }

    public function toSaldo() {
        return view('user/saldo');
    }
    
    public function toDashboard() {
        return view('admin/dashboard/dashboard');
    }

    public function toTicket($id) {
        $hticketsSnapshot = $this->database->getReference("htickets/$id")->getSnapshot();
        $hticketData = $hticketsSnapshot->getValue();
        $hticket = [];

        if ($hticketData['specific_user'] != 'Anonymous') 
        {
            $user = $this->auth->getUser($hticketData['specific_user']);
            $hticketData['specific_user'] = $user->displayName;
            $hticketData['uid'] = $user->uid;
        }

        $schedulesReference = $this->database->getReference('tschedules/' . $hticketData['schedule_id']);
        $schedulesSnapshot = $schedulesReference->getSnapshot();
        $schedulesData = $schedulesSnapshot->getValue();
        
        $hticketData['date'] = $schedulesData['date'];
        $hticketData['theater'] = $schedulesData['theater'];
        $hticketData['time_end'] = $schedulesData['time_end'];
        $hticketData['time_start'] = $schedulesData['time_start'];

        $playsReference = $this->database->getReference('tplays/' . $schedulesData['playid']);
        $playsSnapshot = $playsReference->getSnapshot();
        $playsData = $playsSnapshot->getValue();
        $hticketData['title'] = $playsData['title'];
        $hticketData['poster'] = $playsData['poster'];
        $hticketData['description'] = $playsData['description'];
        $hticketData['age_rating'] = $playsData['age_rating'];

        $hticket = array_merge(['id' => $id], $hticketData);

        $dticketsRef = $this->database->getReference('dtickets');
        $query = $dticketsRef->orderByChild('htickets')->equalTo($id);
        $dtickets = $query->getValue();

        return view('user/ticket', compact('hticket', 'dtickets'));
    }

    public function viewtickets(Request $request) {
        $hticketsSnapshot = $this->database->getReference('htickets')->getSnapshot();
        $htickets = [];
        
        $hticketsData = $hticketsSnapshot->getValue();

        if (is_array($hticketsData)) {
            foreach ($hticketsData as $hticketKey => $hticketData) {
                if ($hticketData['specific_user'] != 'Anonymous') 
                {
                    $hticketData['specific_user'] = $this->auth->getUser($hticketData['specific_user'])->displayName;
                }

                $schedulesReference = $this->database->getReference('tschedules/' . $hticketData['schedule_id']);
                $schedulesSnapshot = $schedulesReference->getSnapshot();
                $schedulesData = $schedulesSnapshot->getValue();
                
                $hticketData['date'] = $schedulesData['date'];
                $hticketData['theater'] = $schedulesData['theater'];
                $hticketData['time_end'] = $schedulesData['time_end'];
                $hticketData['time_start'] = $schedulesData['time_start'];

                $playsReference = $this->database->getReference('tplays/' . $schedulesData['playid']);
                $playsSnapshot = $playsReference->getSnapshot();
                $playsData = $playsSnapshot->getValue();
                $hticketData['title'] = $playsData['title'];
                $hticketData['poster'] = $playsData['poster'];
                $hticketData['description'] = $playsData['description'];
                $hticketData['age_rating'] = $playsData['age_rating'];

                $htickets[] = array_merge(['id' => $hticketKey], $hticketData);
            }
        } else {
            $htickets = [];
        }
        
        $dateFrom = $request->input('date-from');
        $dateUntil = $request->input('date-until');

        if ($dateFrom || $dateUntil) {
            $htickets = array_filter($htickets, function ($hticket) use ($dateFrom, $dateUntil) {
                $ticketDate = strtotime($hticket['date']);
        
                if ($dateFrom && $dateUntil) {
                    if ($ticketDate < strtotime($dateFrom) || $ticketDate > strtotime($dateUntil)) {
                    }
                } elseif ($dateFrom) {
                    if ($ticketDate < strtotime($dateFrom)) {
                        return false; 
                    }
                } elseif ($dateUntil) {
                    if ($ticketDate > strtotime($dateUntil)) {
                        return false; 
                    }
                }
                return true;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'oldest') {
            $htickets = array_reverse($htickets);
        }
    
        $perPage = 6;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($htickets, ($currentPage - 1) * $perPage, $perPage);

        $htickets = new LengthAwarePaginator(
            $currentItems,
            count($htickets),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        ); 

        return view('admin/history/tickets/history', compact('htickets'));
    }

    public function detailtickets($id) {
        $hticketsSnapshot = $this->database->getReference("htickets/$id")->getSnapshot();
        $hticketData = $hticketsSnapshot->getValue();
        $hticket = [];

        if ($hticketData['specific_user'] != 'Anonymous') 
        {
            $user = $this->auth->getUser($hticketData['specific_user']);
            $hticketData['specific_user'] = $user->displayName;
            $hticketData['uid'] = $user->uid;
        }

        $schedulesReference = $this->database->getReference('tschedules/' . $hticketData['schedule_id']);
        $schedulesSnapshot = $schedulesReference->getSnapshot();
        $schedulesData = $schedulesSnapshot->getValue();
        
        $hticketData['date'] = $schedulesData['date'];
        $hticketData['theater'] = $schedulesData['theater'];
        $hticketData['time_end'] = $schedulesData['time_end'];
        $hticketData['time_start'] = $schedulesData['time_start'];

        $playsReference = $this->database->getReference('tplays/' . $schedulesData['playid']);
        $playsSnapshot = $playsReference->getSnapshot();
        $playsData = $playsSnapshot->getValue();
        $hticketData['title'] = $playsData['title'];
        $hticketData['poster'] = $playsData['poster'];
        $hticketData['description'] = $playsData['description'];
        $hticketData['age_rating'] = $playsData['age_rating'];

        $hticket = array_merge(['id' => $id], $hticketData);

        $dticketsRef = $this->database->getReference('dtickets');
        $query = $dticketsRef->orderByChild('htickets')->equalTo($id);
        $dtickets = $query->getValue();

        return view('admin/history/tickets/details', compact('hticket', 'dtickets'));
    }

    public function viewseatings(Request $request) {
        $hseatingsSnapshot = $this->database->getReference('hseatings')->getSnapshot();
        $hseatings = [];
        
        $hseatingsData = $hseatingsSnapshot->getValue();

        if (is_array($hseatingsData)) {
            foreach ($hseatingsData as $hseatingKey => $hseatingData) {
                $schedulesReference = $this->database->getReference('tschedules/' . $hseatingData['schedule_id']);
                $schedulesSnapshot = $schedulesReference->getSnapshot();
                $schedulesData = $schedulesSnapshot->getValue();
                
                $hseatingData['date'] = $schedulesData['date'];
                $hseatingData['theater'] = $schedulesData['theater'];
                $hseatingData['time_end'] = $schedulesData['time_end'];
                $hseatingData['time_start'] = $schedulesData['time_start'];

                $playsReference = $this->database->getReference('tplays/' . $schedulesData['playid']);
                $playsSnapshot = $playsReference->getSnapshot();
                $playsData = $playsSnapshot->getValue();
                $hseatingData['title'] = $playsData['title'];
                $hseatingData['poster'] = $playsData['poster'];
                $hseatingData['description'] = $playsData['description'];
                $hseatingData['age_rating'] = $playsData['age_rating'];

                $hseatings[] = array_merge(['id' => $hseatingKey], $hseatingData);
            }
        } else {
            $hseatings = [];
        }
        
        $dateFrom = $request->input('date-from');
        $dateUntil = $request->input('date-until');

        if ($dateFrom || $dateUntil) {
            $hseatings = array_filter($hseatings, function ($hseating) use ($dateFrom, $dateUntil) {
                $ticketDate = strtotime($hseating['date']);
        
                if ($dateFrom && $dateUntil) {
                    if ($ticketDate < strtotime($dateFrom) || $ticketDate > strtotime($dateUntil)) {
                    }
                } elseif ($dateFrom) {
                    if ($ticketDate < strtotime($dateFrom)) {
                        return false; 
                    }
                } elseif ($dateUntil) {
                    if ($ticketDate > strtotime($dateUntil)) {
                        return false; 
                    }
                }
                return true;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'oldest') {
            $hseatings = array_reverse($hseatings);
        }
    
        $perPage = 6;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($hseatings, ($currentPage - 1) * $perPage, $perPage);

        $hseatings = new LengthAwarePaginator(
            $currentItems,
            count($hseatings),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        ); 

        return view('admin/history/seatings/history', compact('hseatings'));
    }

    public function detailseatings($id) {
        $hseatingsSnapshot = $this->database->getReference("hseatings/$id")->getSnapshot();
        $hseatingData = $hseatingsSnapshot->getValue();
        $hseating = [];

        $schedulesReference = $this->database->getReference('tschedules/' . $hseatingData['schedule_id']);
        $schedulesSnapshot = $schedulesReference->getSnapshot();
        $schedulesData = $schedulesSnapshot->getValue();
        
        $hseatingData['date'] = $schedulesData['date'];
        $hseatingData['theater'] = $schedulesData['theater'];
        $hseatingData['time_end'] = $schedulesData['time_end'];
        $hseatingData['time_start'] = $schedulesData['time_start'];

        $playsReference = $this->database->getReference('tplays/' . $schedulesData['playid']);
        $playsSnapshot = $playsReference->getSnapshot();
        $playsData = $playsSnapshot->getValue();
        $hseatingData['title'] = $playsData['title'];
        $hseatingData['poster'] = $playsData['poster'];
        $hseatingData['description'] = $playsData['description'];
        $hseatingData['age_rating'] = $playsData['age_rating'];

        $hseating = array_merge(['id' => $id], $hseatingData);

        $dseatingsRef = $this->database->getReference('dseatings');
        $query = $dseatingsRef->orderByChild('hseatings')->equalTo($id);
        $dseatings = $query->getValue();

        return view('admin/history/seatings/details', compact('hseating', 'dseatings'));
    }

    public function viewconcessions(Request $request) {
        $hordersSnapshot = $this->database->getReference('horder')->getSnapshot();
        $horders = [];
        
        $hordersData = $hordersSnapshot->getValue();
        if (is_array($hordersData)) {
            foreach ($hordersData as $horderKey => $horderData) {
                if ($horderData['specific_user'] != 'Anonymous') 
                {
                    $horderData['specific_user'] = $this->auth->getUser($horderData['specific_user'])->displayName;
                }
                
                $dorderRef = $this->database->getReference('dorder');
                $query = $dorderRef->orderByChild('horder')->equalTo($horderKey);
                $dorders = $query->getValue();

                foreach ($dorders as $key => $value) {
                    $concessionId = $dorders[$key]['item'];
                    $dorders[$key]['name'] = $this->database->getReference("tconcessions/$concessionId")->getValue()['name'];
                }

                $timestamp = $this->convertFirebaseTimestamp($horderData['created_at']);

                $horders[] = array_merge(['id' => $horderKey, 'dorder' => $dorders, 'timestamp' => $timestamp], $horderData);


            }
        } else {
            $horders = [];
        }
        
        $dateFrom = $request->input('date-from');
        $dateUntil = $request->input('date-until');

        if ($dateFrom || $dateUntil) {
            $horders = array_filter($horders, function ($horder) use ($dateFrom, $dateUntil) {
                $ticketDate = strtotime($horder['timestamp']);
        
                if ($dateFrom && $dateUntil) {
                    if ($ticketDate < strtotime($dateFrom) || $ticketDate > strtotime($dateUntil)) {
                    }
                } elseif ($dateFrom) {
                    if ($ticketDate < strtotime($dateFrom)) {
                        return false; 
                    }
                } elseif ($dateUntil) {
                    if ($ticketDate > strtotime($dateUntil)) {
                        return false; 
                    }
                }
                return true;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'oldest') {
            $horders = array_reverse($horders);
        }
    
        $perPage = 6;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($horders, ($currentPage - 1) * $perPage, $perPage);

        $horders = new LengthAwarePaginator(
            $currentItems,
            count($horders),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        ); 

        return view('admin/history/concessions/history', compact('horders'));
    }

    public function detailconcessions($id) {
        $hordersSnapshot = $this->database->getReference("horder/$id")->getSnapshot();
        $horderData = $hordersSnapshot->getValue();
        $horder = [];

        if ($horderData['specific_user'] != 'Anonymous') 
        {
            $horderData['specific_user'] = $this->auth->getUser($horderData['specific_user'])->displayName;
        }
        
        $horder = array_merge(['id' => $id], $horderData);
        $horder['created_at'] = $this->convertFirebaseTimestamp($horderData['created_at']);

        $dordersRef = $this->database->getReference('dorder');
        $query = $dordersRef->orderByChild('horder')->equalTo($id);
        $dorders = $query->getValue();
        
        $dorderRef = $this->database->getReference('dorder');
        $query = $dorderRef->orderByChild('horder')->equalTo($id);
        $dorders = $query->getValue();

        foreach ($dorders as $key => $value) {
            $concessionId = $dorders[$key]['item'];
            $dorders[$key]['name'] = $this->database->getReference("tconcessions/$concessionId")->getValue()['name'];
            $dorders[$key]['image'] = $this->database->getReference("tconcessions/$concessionId")->getValue()['image'];
        }

        if (isset($horder['voucher']))
        {

            foreach ($horder['voucher']['then_get'] as $key => $value) {
                // $horder['voucher']['then_get']['name'] = $this->database->getReference("tconcessions/$key")->getValue()['name'];
                // $horder['voucher']['then_get']['image'] = $this->database->getReference("tconcessions/$key")->getValue()['image'];
                $array = [
                    'amount'=> $horder['voucher']['then_get'][$key],
                    'name' => $this->database->getReference("tconcessions/$key")->getValue()['name'],
                    'image' => $this->database->getReference("tconcessions/$key")->getValue()['image'],
                ];

                $horder['then_get'][] = $array;
            }
        }

        // dd($dorders);

        return view('admin/history/concessions/details', compact('horder', 'dorders'));
    }

    public function convertFirebaseTimestamp($timestamp)
    {
        $timestampInSeconds = $timestamp / 1000;

        $dateTime = new \DateTime();
        $dateTime->setTimestamp($timestampInSeconds);

        return $dateTime->format('Y-m-d H:i:s');
    }
}
