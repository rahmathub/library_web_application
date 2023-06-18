<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Publisher;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    // security
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.member.index');
    }
    
    public function api(Request $request) {
        $members = Member::query();
    
        if ($request->has('gender')) {
            $gender = $request->gender;
            if ($gender !== '0') {
                $members->where('gender', $gender);
            }
        }
    
        $datatables = datatables()->of($members)->addIndexColumn();
    
        return $datatables->make(true);
    }
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:1'],
            'phone_number' => ['required', 'string', 'max:12'],
            'address' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'max:15'],
        ]);

        Member::create($request->all());

        return redirect()->route('members.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:1'],
            'phone_number' => ['required', 'string', 'max:12'],
            'address' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'max:255'], // Ubah max:15 menjadi max:255
        ]);
    
        $member->update($request->all());
    
        return redirect()->route('members.index');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        $member->delete();
    }
}
