<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Personal;
use App\Models\Role;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    // index 
    public function index(Request $request)
    {
    $user = Auth::user();
      /*   $role= 'quality-engineer';
     //   $role= 'user';
        //      $role= 'admin'; 
      $role_id = Role::where('slug', $role)->first()->id;
     // $user->roles()->detach();
        $user->roles()->syncWithoutDetaching([$role_id]);*/
      return view('profile.index', compact('user'));
        
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
 
    // import

    public function import(Request $request)
    {
       $personals = Personal::where('status','!=' ,'Звільнення')->get();
       // delete all users accept id=1
         User::where('id', '!=', 1)->delete();
         $role= Role::where('slug', 'user')->first();
       foreach($personals as $personal){
        $user = User::where('email', $personal->tn.'@promprylad.ua')->first();
        if(!$user){
            $user = new User();
            $user->name = $personal->fio;
            $user->email = $personal->tn.'@promprylad.ua';
            $user->tn = $personal->tn;
            $user->password = bcrypt($personal->tn);
            $user->save();
            $role= 'user';
            $role_id = Role::where('slug', $role)->first()->id;
            $user->roles()->attach($role_id);
        }
       }
      return $users = User::orderBy('id', 'desc')->get();
    }
}
