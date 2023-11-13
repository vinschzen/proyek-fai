<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ScheduleController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index(Request $request)
    {
        $schedulesSnapshot = $this->database->getReference('tschedules')->getSnapshot();
        $schedules = [];
        
        $schedulesData = $schedulesSnapshot->getValue();

        if (is_array($schedulesData)) {
            foreach ($schedulesData as $scheduleKey => $scheduleData) {
                
                $playsReference = $this->database->getReference('tplays/' . $scheduleData['playid']);
                $playSnapshot = $playsReference->getSnapshot();
                $playData = $playSnapshot->getValue();
                
                $scheduleData['title'] = $playData['title'];
                $scheduleData['duration'] = $this->calculateTimeRange($scheduleData['time'], $playData['duration']);

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
        if ($filter === 'oldest') {
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
    

        return view('admin/schedule/master', compact('schedules'));
    }

    public function viewadd() {
        $playsSnapshot = $this->database->getReference('tplays')->getSnapshot();
        $plays = [];
        
        $playsData = $playsSnapshot->getValue();
        if (is_array($playsData)) {
            foreach ($playsData as $playKey => $playData) {
                $plays[] = array_merge(['id' => $playKey], $playData);
            }
        } else {
            $plays = [];
        }

        return view('admin/schedule/add', compact('plays'));
    }

    public function viewedit($id) 
    {
        $scheduleSnapshot = $this->database->getReference("tschedules/$id")->getSnapshot();
        
        if (!$scheduleSnapshot->exists()) {
            abort(404); 
        }
    
        $schedule = array_merge(['id' => $id], $scheduleSnapshot->getValue());

        $playsSnapshot = $this->database->getReference('tplays')->getSnapshot();
        $plays = [];
        
        $playsData = $playsSnapshot->getValue();
        if (is_array($playsData)) {
            foreach ($playsData as $playKey => $playData) {
                $plays[] = array_merge(['id' => $playKey], $playData);
            }
        } else {
            $plays = [];
        }

        $scheduleid = $schedule['playid'];
        $title = $this->database->getReference("tplays/$scheduleid")->getSnapshot()->getValue();
        $lasttitle = $title['title'];
    
        return view('admin/schedule/edit', compact('schedule', 'plays', 'lasttitle'));
    }

    public function store(Request $request)
    {

        $rules = [
            'playid' => 'required|string|max:255',
            'date' => 'required|string',
            'time' => 'required|date_format:H:i',
        ];
        
        $messages = [
            'playid.required' => 'The play selection is required.',
            'date.required' => 'The date field is required.',
            'date.date_format' => 'The date format is incorrect.',
            'time.string' => 'The title must be a string.',
        ];
        
        $request->validate($rules, $messages);

        $data = $request->only(['playid', 'date', 'time', 'theater']);

        $data['created_at'] = ['.sv' => 'timestamp'];
        $data['updated_at'] = ['.sv' => 'timestamp'];

        $playsRef = $this->database->getReference('tschedules')->push();
        $playsRef->set($data);

        return redirect()->route('toMasterSchedule')->with('success', 'Play added successfully');
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
                $scheduleData['duration'] = $this->calculateTimeRange($scheduleData['time'], $playData['duration']);

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
        if ($filter === 'oldest') {
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
     
    function checkoutTickets()
    {
        return view('admin/dashboard/cashier-tickets/checkout');

    }

    function calculateTimeRange($initialTime, $incrementMinutes) {
        $initialTimestamp = strtotime('1970-01-01 ' . $initialTime);
    
        $endTime = $initialTimestamp + ($incrementMinutes * 60);
    
        $formattedInitialTime = date('H:i', $initialTimestamp);
        $formattedEndTime = date('H:i', $endTime);
    
        $rangeString = $formattedInitialTime . ' - ' . $formattedEndTime;
        
        return $rangeString;
    }
}
