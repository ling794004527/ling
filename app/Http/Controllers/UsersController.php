<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;

class UsersController extends Controller
{
    //
    public function __construct(){
        //auth属性，除了show、create、store方法，在未登录状态下不能访问
        //就是说在未登录状态下，只能访问该控制器下的show、create、store方法
        $this->middleware('auth',[
            'except' => ['show', 'create', 'store', 'index']
        ]);

        //guest属性，在未登录状态，只能访问create方法
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function index(){
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    //显示用户注册页面
    public function create(){
    	return view("users.create");
    }

    //显示用户详情页面
    public function show(User $user){
    	return view('users.show', compact('user'));
    }

    //用户注册提交
    public function store(Request $request){
    	$this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => bcrypt($request->password)
    	]);

        Auth::login($user);
    	session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
    	return redirect()->route('users.show', [$user]);
    }

    //显示用户详情编辑页面
    public function edit(User $user){
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    //用户更新
    public function update(User $user, Request $request){
        $this->validate($request, [
            'name' => 'required|max:60',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);
    }

    //用户删除
    public function destroy(User $user){
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '删除成功！');
        return back();
    }
}
