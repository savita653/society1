<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use JamesMills\LaravelTimezone\Facades\Timezone;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Event;
use App\File;
use App\Helpers\Helper;
use App\Keyword;
use Str;
use Carbon\Carbon;
use Thomasjohnkane\Snooze\Serializer;
use Thomasjohnkane\Snooze\Models\ScheduledNotification;
use App\Notifications\PresenterEventReminder;

class EventController extends Controller
{
    public function __construct()
    {
        View::share('admins', User::role(Helper::ADMIN_ROLE)->where('is_active', 1)->get());
        View::share('presenters', User::role(Helper::PRESENTER_ROLE)->where('profile_status', 'approved')->where('is_active', 1)->get());
        View::share('keywords', Keyword::all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request()->ajax()) {

            if (auth()->user()->hasRole('super_admin')) {
                $events = Event::with('user')->where('events.id', '<>', 0);
                if (request()->get('view') == 'trash') {
                    $events->onlyTrashed();
                }
            } else {
               
                if (request()->get('view') == 'trash') {
                    $events = Event::onlyTrashed()->leftJoin('event_user', 'event_user.event_id', '=', 'events.id')
                    ->where(function($query) {
                        $query->where('event_user.user_id', auth()->user()->id);
                        $query->orWhere('events.user_id', auth()->user()->id);
                    });
                    
                } else {
                    $events = Event::leftJoin('event_user', 'event_user.event_id', '=', 'events.id')
                    ->where(function($query) {
                        $query->where('event_user.user_id', auth()->user()->id);
                        $query->orWhere('events.user_id', auth()->user()->id);
                    });
                    // ->where('event_user.user_id', auth()->user()->id)
                    // ->orWhere('events.user_id', auth()->user()->id);
                }

            }

            

            if (request()->get('event_admin') != "") {
                $events->where('user_id', request()->get('event_admin'));
            }

            if (request()->get('event_status') != "") {
                $events->where('status', request()->get('event_status'));
            }


            if (request()->get('view') == 'calendar') {
                // Return data for Calendar
                if (auth()->user()->hasRole('super_admin')) {
                    return Event::where('id', '<>', 0)->select('events.*', 'start_date_time as start')->get()->toJson();
                } else {
                    return Event::leftJoin('event_user', 'event_user.event_id', '=', 'events.id')
                        ->where('event_user.user_id', auth()->user()->id)
                        ->orWhere('events.user_id', auth()->user()->id)
                        ->select('events.*', 'events.start_date_time as start')
                        ->get()->toJson();
                }
            }

            return datatables()->of($events)
                ->editColumn('title', function ($event) {
                    $title = Helper::tooltip($event->title, 50);
                    $eventLink = route('subscriber.events.show', $event->id);
                    return "<a href='$eventLink' target='blank'>$title <i data-feather='external-link'></i> </a>";
                })
                ->editColumn('created_at', function ($event) {
                    return Timezone::convertToLocal($event->created_at, 'd M Y');
                })
                ->editColumn('status', function ($event) {
                    return $event->statusHtml();
                })
                ->editColumn('start_date_time', function ($event) {
                    return Timezone::convertToLocal($event->start_date_time, 'd M Y h:i A');
                })
                ->addColumn('users', function ($event) {
                    if (isset($event->user->id)) {
                        if (auth()->user()->id == $event->user->id) {
                            return "Me";
                        } else {
                            $user = $event->user;
                            return $event->user->fullName() . "<br>" . "<a href='mailto:{$user->email}'>{$user->email}</a>";
                        }
                    } else {
                        return "N/A";
                    }
                })
                ->addColumn('last_name', function($event) {
                    if(!isset($event->user->id)) { return "N/A"; }
                    return $event->user->last_name;
                })
                ->addColumn('email', function($event) {
                    if(!isset($event->user->id)) { return "N/A"; }
                    return $event->user->email;
                })
                ->addColumn('action', function ($event) {
                    $deleteUrl = route('events.destroy', $event->id);

                    if ($event->trashed()) {
                        $url = route('events.restore', $event->id);

                        return "
                            <button 
                                class='btn btn-primary restore-record btn-icon btn-sm'
                                data-title='Restore'
                                data-url='$url'
                            ><i data-feather='refresh-cw'></i></button>

                            <button 
                                data-toggle='tooltip'
                                data-title='Delete Parmanently'
                                class='btn btn-danger delete-record btn-icon btn-sm'
                                data-url='$deleteUrl'
                            ><i data-feather='trash'></i></button>

                            
                        ";
                    } else {
                        $url = route('events.edit', $event->id);
                        $liveUrl = route('live.event.index', $event->id);

                        return "
                            <button 
                                class='btn btn-primary get-content btn-icon btn-sm'
                                data-title='Edit Event'
                                data-url='$url'
                            ><i data-feather='edit-3'></i></button>

                            <button 
                                data-toggle='tooltip'
                                data-title='Move to Trash'
                                class='btn btn-danger delete-record btn-icon btn-sm'
                                data-url='$deleteUrl'
                            ><i data-feather='trash'></i></button>

                            <a 
                                
                                href='$liveUrl'
                                class='btn btn-primary  btn-sm'
                            > <i data-feather='play-circle'></i> Go Live</a>
                        ";
                    }
                })
                ->rawColumns(['action', 'is_active', 'users', 'status', 'title'])
                ->make(true);
        } else {

            $pageConfigs = ['pageHeader' => true];
            $breadcrumbs = [
                ['link' => "/", 'name' => "Dashboard"],
                ['name' => "Events"]
            ];
            return view('/content/events/manage/index', compact('breadcrumbs'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/content/events/manage/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules());

        $event = new Event;
        $event->user_id = auth()->user()->hasRole('super_admin') ? $request->user_id : auth()->user()->id;
        $event->title = $request->title;
        $event->start_date_time = Timezone::convertFromLocal($request->start_date_time);
        $channelName = Str::slug($request->title, '-');
        $channelName = Str::limit($channelName, 10) . '_' . date('d_M_Y_H_i_s');
        $event->channel_name = $channelName;
        $event->status = $request->status;
        $event->save();

        $event->users()->sync($request->users ?? []);

        $keywordIds = [];

        foreach($request->keywords as $keywordId) {
            if(!is_numeric($keywordId)) {
                $keyword = Keyword::create([
                    'keyword_name' => $keywordId,
                ]);
                $keywordId = $keyword->id;
            }

            $keywordIds[] = $keywordId;
        }

        $event->keywords()->sync($keywordIds ?? []);

        if ($request->hasFile('logo')) {
            try {
                $path = $request->file('logo')->store('public/event_images');
                $fileName = $request->file('logo')->getClientOriginalName();
                $extension = $request->file('logo')->extension();
                $file = new File;
                $file->title = $fileName;
                $file->location = $path;
                $file->extension = $extension;
                $file->save();

                $event->logo_id = $file->id;
                $event->save();
            } catch (\Exception $e) {}
        }

        foreach($event->users ?? [] as $user) {
            $user = User::find($user->id);
            
            try {
                $user->notifyAt(
                    new PresenterEventReminder($event, $user), 
                    Carbon::parse(Timezone::convertFromLocal($event->start_date_time))
                );
            } catch(\Exception $e) {
                // Send date may be in advance.
            }
            
        }

        return response()->json([
            'success' => true,
            'code' => 'success',
            'title' => 'Congratulations',
            'message' => 'Event created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $record = Event::findOrFail($id);
        $record->start_date_time = Timezone::convertToLocal($record->start_date_time, 'd M Y h:i A');
        $record->token = $record->generateToken();
        return view('/content/events/manage/show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $record = Event::findOrFail($id);
        $record->start_date_time = Timezone::convertToLocal($record->start_date_time, 'd M Y h:i A');
        return view('/content/events/manage/edit', compact('record'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $request->validate($this->validationRules([
            'title' => 'required|max:255|unique:events,title,' . $id,
        ]));

        $event->user_id = auth()->user()->hasRole('super_admin') ? $request->user_id : auth()->user()->id;
        $event->title = $request->title;
        $event->start_date_time = Timezone::convertFromLocal($request->start_date_time);
        $event->status = $request->status;
        $event->save();

        
        $keywordIds = [];
        
        foreach($request->keywords as $keywordId) {
            if(!is_numeric($keywordId)) {
                $keyword = Keyword::create([
                    'keyword_name' => $keywordId,
                ]);
                $keywordId = $keyword->id;
            }

            $keywordIds[] = $keywordId;
        }

        $event->keywords()->sync($keywordIds ?? []);

        if ($request->hasFile('logo')) {
            $event->addOrUpdateLogo($request->file('logo'));
        }

        foreach($event->users ?? [] as $user) {
            $user = User::find($user->id);
            
            $record = ScheduledNotification::where(
                'notification',
                 Serializer::create()->serializeNotifiable(new PresenterEventReminder($event, $user))
            )->where([
                'target_id' => $user->id,
                'target_type' => 'App\User'
            ])->first();
            
            if($record) {
                $record->delete();
            }
        }

        $event->users()->sync($request->users ?? []);

        foreach($event->users ?? [] as $user) {
            $user = User::find($user->id);

            try {
                $user->notifyAt(
                    new PresenterEventReminder($event, $user), 
                    Carbon::parse(Timezone::convertFromLocal($event->start_date_time))
                );
            } catch(\Exception $e) {
                // Send date may be in advance.
            }
        }

        return response()->json([
            'success' => true,
            'code' => 'success',
            'title' => 'Congratulations',
            'message' => 'Event information updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Event::withTrashed()->findOrFail($id);
        if ($record->trashed()) {
            if ($record->logo_id) {
                $logo = File::find($record->logo_id);
                $logoLocation = $logo->location;
            } else {
                $logoLocation = "";
            }

            $record->forceDelete();

            if (!empty($logoLocation)) {
                // Storage::delete($logoLocation);
                $logo->delete();
            }

            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Event permanently deleted successfully.'
            ]);
        } else {
            $record->delete();
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Event moved to trash successfully.'
            ]);
        }
    }

    public function restore($id)
    {
        $record = Event::withTrashed()->findOrFail($id);
        if ($record->trashed()) {
            $record->restore();
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Event restored successfully.'
            ]);
        } else {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
    }

    public function validationRules($overrideRule = [])
    {
        $rules = [
            'title' => 'required|max:255|unique:events,title',
            'start_date_time' => 'required',
            'status' => 'required|in:' . implode(',', array_keys(config('setting.event_status'))),
            'logo' => 'image|nullable|max:5120'
        ];
        return array_merge($rules, $overrideRule);
    }
}
