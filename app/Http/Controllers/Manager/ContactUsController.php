<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactUsController extends Controller
{
    private $_model;

    public function __construct(ContactUs $contactUs)
    {
        parent::__construct();
        $this->_model = $contactUs;
        $this->middleware('permission:Contact Us', ['only' => ['index', 'create', 'edit']]);
    }

    public function index()
    {
        $this->middleware('permission:Contact Us', ['only' => ['index', 'show', 'destroy']]);
        if (request()->ajax()) {
            $contacts = $this->_model->query();
            $search = request()->get('search', false);
            $contacts = $contacts->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('mobile', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('title', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%');
            });
            return DataTables::make($contacts)
                ->escapeColumns([])
                ->addColumn('created_at', function ($contact) {
                    return Carbon::parse($contact->created_at)->toDateTimeString();
                })
                ->addColumn('name', function ($contact) {
//                    if (isset($contact->user_id)) dd( $contact->user_id);
                    return '<a href="javascript:;">' . $contact->name . '</a>';
                })
                ->addColumn('target', function ($contact) {
                    return $contact->target_name;
                })
                ->addColumn('how_did_you_hear_about_ingaz', function ($contact) {
                    return $contact->how_did_you_hear_about_ingaz_name;
                })
                ->addColumn('status', function ($contact) {
                    return $contact->seen ? t('Seen') : t('Unseen');
                })
                ->addColumn('actions', function ($contact) {
                    return $contact->action_buttons;
                })
                ->make();
        }
        $title = t('Show Contact Us List');
        return view('manager.contact_us.index', compact('title'));
    }

    public function show($id)
    {
        $title = t('Show Contact Us Content');
        $contact = ContactUs::query()->findOrFail($id);
        if (!$contact->seen) {
            $contact->update([
                'seen' => true,
//                'seen_at' => now(),
            ]);
        }
        return view('manager.contact_us.show', compact('contact', 'title'));
    }

    public function destroy($id)
    {
        $contact = ContactUs::query()->findOrFail($id);
        $contact->delete();
        return redirect()->back()->with('message', t('Successfully Deleted'))->with('m-class', 'success');
    }

}
