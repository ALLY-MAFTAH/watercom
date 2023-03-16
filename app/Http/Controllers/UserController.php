<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use App\Helpers\ActivityLogHelper;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $roles = Role::where('status', 1)->get();

        $users = User::all();

        return view('users.index', compact('users', 'roles'));
    }
    public function showUser(Request $request, User $user)
    {

        $roles = Role::where('status', 1)->get();

        $logs = ActivityLog::where('user_id', $user->id)->get();
        // dd($user->id);
        return view('users.show', compact('user', 'roles', 'logs'));
    }
    public function postUser(Request $request)
    {
        // dd($request->all());
        try {
            $attributes = $request->validate([
                'name' => ['required', 'unique:users,name,NULL,id,deleted_at,NULL'],
                'mobile' => ['required', 'unique:users,mobile,NULL,id,deleted_at,NULL'],
                'role_id' => 'required',
            ]);

            $attributes['password'] = Hash::make('12312345');
            $attributes['status'] = true;

            $user = User::create($attributes);

            $role = Role::find($request->role_id);
            $role->users()->save($user);
            ActivityLogHelper::addToLog('Added new  user: ' . $user->name);
        } catch (QueryException $th) {
            // dd($th->getMessage());
            notify()->error($th->getMessage());
            // notify()->error('Failed to add user user. "' . $request->name . '" already exists.');
            return back();
        }

        notify()->success('You have successful added new  user');
        return redirect()->back();
    }

    public function putUser(Request $request, User $user)
    {

        session()->flash('user_id', $user->id);
        $attributes = $request->validateWithBag('update', [
            'name' => 'required',
            // 'email' => ['sometimes', Rule::unique('users')->ignore($user->id)->whereNull('deleted_at')],
            'mobile' => ['required', Rule::unique('users')->ignore($user->id)->whereNull('deleted_at')],
            'role_id' => ['sometimes', 'exists:roles,id'],
        ]);

        $user->update($attributes);
        ActivityLogHelper::addToLog('Updated user: ' . $user->name);

        notify()->success('You have successful edited user');
        return back();
    }

    public function toggleStatus(Request $request, User $user)
    {

        $attributes = $this->validate($request, [
            'status' => ['required', 'boolean'],
        ]);

        $user->update($attributes);
        ActivityLogHelper::addToLog('Switched user ' . $user->name . ' status ');

        notify()->success('You have successfully updated user status');
        return back();
    }
    public function deleteUser(Request $request, User $user)
    {

        $itsName = $user->name;
        $user->delete();
        ActivityLogHelper::addToLog('Deleted user: ' . $itsName);

        notify()->success('You have successful deleted ' . $itsName . '.');
        return back();
    }
    public function changePassword(Request $request)
    {
        $userId = $request->user_id;
        $oldPassword = $request->current_password;
        $newPassword = $request->new_password;
        $confirmPassword = $request->password_confirmation;

        if ($confirmPassword != $newPassword) {
            notify()->error("The current password and confirm password must be the same");
            return back()->withErrors(['password_confirmation' => "The current password and confirm password must be the same"])->withInput();
        }
        try {
            $user  = User::find($userId);

            $attributes['password'] = Hash::make($newPassword);

            if (Hash::check($oldPassword, $user->password)) {
                $user->update($attributes);
                ActivityLogHelper::addToLog('Changed password');
                notify()->success('Password changed successfully.');
            } else {
                notify()->error("The current password you entered is incorrect.");
                return back()->withErrors(['current_password' => 'The current password you entered is incorrect.'])->withInput();
            }
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            notify()->error($th->getMessage());
        }
        return back();
    }
}
