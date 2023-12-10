<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Kreait\Firebase\Contract\Storage;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    protected $database;
    protected $storage;

    public function __construct(Database $database, Storage $storage)
    {
        $this->database = $database;
        $this->storage = $storage;
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
                $playData['poster'] = $this->storage->getBucket(env('FIREBASE_STORAGE_BUCKET'))->object($playData['poster'])->signedUrl(new \DateTime('tomorrow'));;
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


        $data = $request->only(['playid', 'date', 'theater']);

        $play = $this->database->getReference("tplays/$request->playid")->getSnapshot()->getValue();

        $data['time_start'] = $request->time;
        $carbonTime = Carbon::createFromFormat('H:i', $request->time);
        $newTime = $carbonTime->addMinutes($play['duration']);
        $newTimeString = $newTime->format('H:i');

        $data['time_end'] = $newTimeString;

        if (!$this->checkOverlap($data['time_start'], $data['time_end'], $data['theater'], $data['date']))
        {
            return redirect()->back()->with('error', 'Overlapping schedules');
        }

        $data['created_at'] = ['.sv' => 'timestamp'];
        $data['updated_at'] = ['.sv' => 'timestamp'];

        $schedulesRef = $this->database->getReference('tschedules')->push();
        $schedulesRef->set($data);
        
        $hseatings = [];
        $hseatings['schedule_id'] = $schedulesRef->getKey();       
        $hseatings['created_at'] = ['.sv' => 'timestamp'];
        $hseatings['updated_at'] = ['.sv' => 'timestamp'];

        $hseatingsRef = $this->database->getReference('hseatings')->push();
        $hseatingsRef->set($hseatings);

        return redirect()->route('toMasterSchedule')->with('success', 'Play added successfully');
    }

    public function edit($id, Request $request)
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


        $data = $request->only(['playid', 'date', 'theater']);

        $play = $this->database->getReference("tplays/$request->playid")->getSnapshot()->getValue();

        $data['time_start'] = $request->time;
        $carbonTime = Carbon::createFromFormat('H:i', $request->time);
        $newTime = $carbonTime->addMinutes($play['duration']);
        $newTimeString = $newTime->format('H:i');

        $data['time_end'] = $newTimeString;

        if (!$this->checkOverlap($data['time_start'], $data['time_end'], $data['theater'], $data['date']))
        {
            return redirect()->back()->with('error', 'Overlapping schedules');
        }

        $data['updated_at'] = ['.sv' => 'timestamp'];

        $schedulesRef = $this->database->getReference("tschedules/$id");
        $schedulesRef->update($data);

        return redirect()->route('toMasterSchedule')->with('success', 'Schedule edited successfully');
    }

    function calculateTimeRange($initialTime, $incrementMinutes) {
        $initialTimestamp = strtotime('1970-01-01 ' . $initialTime);
    
        $endTime = $initialTimestamp + ($incrementMinutes * 60);
    
        $formattedInitialTime = date('H:i', $initialTimestamp);
        $formattedEndTime = date('H:i', $endTime);
    
        $rangeString = $formattedInitialTime . ' - ' . $formattedEndTime;
        
        return $rangeString;
    }

    public function destroy($id)
    {
        $scheduleRef = $this->database->getReference('tschedules')->getChild($id);

        if ($scheduleRef->getSnapshot()->exists()) {
            // $scheduleRef->remove();
            $scheduleRef->update([
                'is_deleted' => true,
                'deleted_at' => time(), 
            ]);

            return redirect()->route('toMasterSchedule')->with('success', 'Schedule deleted successfully');
        }

        return redirect()->route('toMasterSchedule')->with('error', 'Schedule not found');
    }



    function checkOverlap($newStartTime, $newEndTime, $theater, $date) {

        $existingStarts = [];
        $existingEnds  = [];

        $schedules = $this->database->getReference("tschedules")->getSnapshot()->getValue();
        if (!$schedules) return true;

        foreach ($schedules as $key => $s) {
            if ($s['theater'] == $theater && $s['date'] == $date) {
                $existingStarts[] = $s['time_start'];
                $existingEnds[] = $s['time_end'];
            }
        }

        function isOverlap($newStartTime, $newEndTime, $existingStartTime, $existingEndTime) {
            return (
                ($newStartTime >= $existingStartTime && $newStartTime < $existingEndTime) ||
                ($newEndTime > $existingStartTime && $newEndTime <= $existingEndTime) ||
                ($newStartTime <= $existingStartTime && $newEndTime >= $existingEndTime)
            );
        }

        $overlapFound = false;
        foreach ($existingStarts as $index => $start) {
            if (isOverlap($newStartTime, $newEndTime, $start, $existingEnds[$index])) {
                $overlapFound = true;
                break;
            }
        }
        
        if ($overlapFound) {
            return false;
        } else {
            return true;
        }
    }
    
      
}
