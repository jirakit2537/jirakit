<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use DB;
use Hash;
use Auth;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'จัดการข้อมูลผู้ใช้';
        $data = User::orderBy('id', 'DESC')->paginate(5);
        return view('users.index', compact('data','title'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function editprofile()
    {
        $title = 'จัดการข้อมูลผู้ใช้';
        $user = User::find(Auth::user()->id);
        return view('users.profile', ["user" => $user]);

    }

    public function saveeditprofile(Request $input)
    {

        $this->validate($input, [
            'name' => 'required|max:255',
            'password' => 'required|min:6|confirmed'
        ]);
        $user = User::find(Auth::user()->id);
        $user->name = $input["name"];
        if ($input->has('password'))
            $user->password = bcrypt($input['password']);
        $user->save();
        return redirect('users/profile')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

		if(Auth::user()->hasRole('Province')){
			$roles = Role::whereIn('id', [7, 8, 9])->pluck('display_name', 'id'); //เจ้าคณะจังหวัด

		}elseif(Auth::user()->hasRole('District')){
			$roles = Role::whereIn('id', [10, 11, 12])->pluck('display_name', 'id'); //เจ้าคณะอำเภอ
		}elseif(Auth::user()->hasRole('canton')){
			$roles = Role::whereIn('id', [13, 14])->pluck('display_name', 'id');   //เจ้าคณะตำบล
		}elseif(Auth::user()->hasRole('Admin')){
			 $roles = Role::pluck('display_name', 'id');
		}
        $title = 'จัดการข้อมูลผู้ใช้';
        return view('users.create', compact('roles','title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }

        return redirect()->route('users.index')
            ->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = User::find($id);
        $title = $user->name;
        return view('users.show', compact('user','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = User::find($id);
        $title = $user->name;
        $roles = Role::pluck('display_name', 'id');
        $userRole = $user->roles->pluck('id', 'id')->toArray();


        return view('users.edit', compact('user', 'roles', 'userRole','title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('role_user')->where('user_id', $id)->delete();

        foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }

        return redirect()->route('users.index')
            ->with('success', 'อัพเดทข้อมูลเรียบร้อย');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'ลบข้อมูลเรียบร้อย');
    }
}
