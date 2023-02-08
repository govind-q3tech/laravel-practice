<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Response;

class ContactController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $contacts = Contact::sortable(['created_at' => 'desc'])->paginate(config('get.FRONT_PAGE_LIMIT'));
        return view('contacts.index', compact('contacts'));
    }   

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {        
        $contact = Contact::where('id',$id)->with('listing')->whereHas('listing', function($q){
            $q->where('user_id',  auth()->user()->id);
        })->first();
        if(empty($contact)){
            return redirect()->route('home')->with('error', 'you are not authorized to access it.');
        }
        return view('contacts.show', compact('contact'));
    }  


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        $requestedData = $request->all();
        $contact = Contact::create($requestedData);
        return redirect()->route('frontend.contacts.index')->with('success', 'Enquiry has been saved successfully.');
    }
}
