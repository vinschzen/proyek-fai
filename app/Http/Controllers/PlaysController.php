<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PlaysController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index(Request $request)
    {
        $playsSnapshot = $this->database->getReference('tplays')->getSnapshot();
        $playsData = $playsSnapshot->getValue();
        if (is_array($playsData)) {
            foreach ($playsData as $playKey => $playData) {
                $plays[] = array_merge(['id' => $playKey], $playData);
            }
        } else {
            $plays = [];
        }
        
        $search = $request->input('search');
        if ($search) {
            $plays = array_filter($plays, function ($play) use ($search) {
                return strpos(strtolower($play['title']), strtolower($search)) !== false;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'oldest') {
            $plays = array_reverse($plays);
        }
    
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($plays, ($currentPage - 1) * $perPage, $perPage);

        $plays = new LengthAwarePaginator(
            $currentItems,
            count($plays),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    

        return view('admin/plays/master', compact('plays'));
    }

    public function viewadd() {
        return view('admin/plays/add');
    }

    public function viewedit($id) 
    {
        $playSnapshot = $this->database->getReference("tplays/$id")->getSnapshot();
        
        if (!$playSnapshot->exists()) {
            abort(404); 
        }
    
        $play = array_merge(['id' => $id], $playSnapshot->getValue());
    
        return view('admin/plays/edit', compact('play'));
    }

    public function store(Request $request)
    {

        $rules = [
            'poster' => 'required|image',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string',
            'age_rating' => 'required|string',
            'director' => 'required|string',
            'casts' => 'required|array',
            'casts.*' => 'string',
        ];
        
        $messages = [
            'poster.required' => 'The poster is required.',
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than :max characters.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a string.',
        ];
        
        $request->validate($rules, $messages);

        $poster = $request->file('poster');
        $posterPath = $poster->store('posters', 'public'); 

        $data = $request->only(['title', 'description', 'duration', 'age_rating', 'director', 'casts']);

        $data['poster'] = $posterPath;
        $data['created_at'] = ['.sv' => 'timestamp'];
        $data['updated_at'] = ['.sv' => 'timestamp'];

        $playsRef = $this->database->getReference('tplays')->push();
        $playsRef->set($data);

        return redirect()->route('toMasterPlay')->with('success', 'Play added successfully');
    }

    public function edit($id, Request $request)
    {

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string',
            'age_rating' => 'required|string',
            'director' => 'required|string',
            'casts' => 'required|array',
            'casts.*' => 'string',
        ];
        
        $messages = [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than :max characters.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a string.',
        ];
        
        $request->validate($rules, $messages);
            
        $data = $request->only(['title', 'description', 'duration', 'age_rating', 'director', 'casts']);

        if ($request->file('poster'))
        {
            $poster = $request->file('poster');
            $posterPath = $poster->store('posters', 'public'); 
            $data['poster'] = $posterPath;
        }

        $data['updated_at'] = ['.sv' => 'timestamp'];

        $playsRef = $this->database->getReference("tplays/$id");
        $playsRef->update($data);

        return redirect()->route('toMasterPlay')->with('success', 'Play edited successfully');
    }

    public function destroy($id)
    {
        $playRef = $this->database->getReference('tplays')->getChild($id);

        if ($playRef->getSnapshot()->exists()) {
            $playRef->remove();
            return redirect()->route('toMasterPlay')->with('success', 'Play deleted successfully');
        }

        return redirect()->route('toMasterPlay')->with('error', 'Play not found');
    }
}
