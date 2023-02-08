<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Gate;

class UsersController extends Controller
{


    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $response = Gate::inspect('check-user', "users-index");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        if(!empty($request->query('type'))){
            $users = User::sortable(['created_at' => 'desc'])->filter($request->query('keyword'))->typefilter($request->query('type'))->paginate(config('get.ADMIN_PAGE_LIMIT'));
        }else{
            return redirect()->route('admin.users.index', ['type' => 'customers']);
        }
        return view('Admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = Gate::inspect('check-user', "users-create");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        return view('Admin.users.createOrUpdate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        // dd($request);
        // $emailArray = explode("@", "foo@bar.com");

        // if (checkdnsrr(array_pop($emailArray), "MX")) {
        //     print "valid email domain";
        // } else {
        //     print "invalid email domain";
        // }
        $response = Gate::inspect('check-user', "users-create");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        try {
            $requestData = $request->all();
            // dd($requestData);
            $requestData['status'] = (isset($requestData['status'])) ? 1 : 0;
            $requestData['email_verified_at'] = \Carbon\Carbon::now();
            $requestData['password'] = Hash::make($requestData['random']);
            $type = $requestData['type'];
            if($type == 'customers'){
                $typeMessage = 'customer';
                $requestData['is_approved'] = 0;
            }
            else{
                $typeMessage = 'advertiser';
                $requestData['is_approved'] = 1;

            }
            User::create($requestData);
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('admin.users.index', ['type' => $type])->with('success', Str::ucfirst($typeMessage).' has been saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $response = Gate::inspect('check-user', "users-index");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        $user = User::findOrFail($id);
        return view('Admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = Gate::inspect('check-user', "users-create");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        $user = User::findOrFail($id);

        return view('Admin.users.createOrUpdate', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UserRequest $request, $id)
    {
        $response = Gate::inspect('check-user', "users-create");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        try {
            $user = User::findOrFail($id);
            $requestData = $request->all();
            $requestData['status'] = (isset($requestData['status'])) ? 1 : 0;
            // $requestData['is_approved'] = (isset($requestData['is_approved'])) ? 1 : 2;
            if ($user->email_verified_at == "" && isset($requestData['verifired'])) {
                $requestData['email_verified_at'] = time();
            }
            $user->fill($requestData);
            $user->save();
            $type = $requestData['type'];

            if($type == 'customers'){
                $typeMessage = 'customer';
            }
            else{
                $typeMessage = 'advertiser';

            }

        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('admin.users.index', ['type' => $type])->with('success', Str::ucfirst($typeMessage).' has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $response = Gate::inspect('check-user', "users-create");
        if (!$response->allowed()) {
            return redirect()->route('admin.dashboard', app('request')->query())->with('error', $response->message());
        }
        DB::beginTransaction();
        try {
            $userData = User::findOrFail($user->id);
            if ($user->is_subscribed == 1) {
                $type = 'advertisers';
            } else {
                $type = 'customers';
            }
            $user->delete();
            DB::commit();
            $responce = ['status' => true, 'message' => 'This '.Str::ucfirst($type).' has been deleted successfully.', 'data' => $user];
        } catch (\Exception $e) {
            DB::rollBack();
            $responce = ['status' => false, 'message' => $e->getMessage()];
        }
        return $responce;
    }


    public function userApprove(Request $request, $id)
    {
        $response = Gate::inspect('check-user', "users-create");
        if (!$response->allowed()) {
            $responce = ['status' => false, 'message' =>  $response->message(), 'data' => []];
        }
        DB::beginTransaction();
        try {
            $userData = User::where(['id' => $id, 'is_approved' => 2])->first();
            if(!empty($userData)){
                $message = 'approved';
                $userData->is_approved = 1;
                $userData->save();
            }
            $userData->sendApprovedEnail($userData);
            $type = 'advertisers';
            DB::commit();
            $responce = ['status' => true, 'message' => 'This '.$type.' has been '.$message.' successfully.', 'data' => []];
        } catch (\Exception $e) {
            DB::rollBack();
            $responce = ['status' => false, 'message' => $e->getMessage()];
        }
        return $responce;
    }
}
