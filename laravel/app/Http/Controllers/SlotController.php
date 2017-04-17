<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\SlotRequest;
use App\Models\Slot;
use App\Models\UserRole;

use Illuminate\Support\Facades\Auth;

use App\Events\SlotChanged;
use Carbon\Carbon;

class SlotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Helper function to determine if an event has passed
    private function eventHasPassed(Slot $slot)
    {
        $start_date = new Carbon($slot->start_date);

        if($start_date->lt(Carbon::now()))
        {
            return true;
        }

        return false;
    }

    // Helper function to determine allowed roles
    private function userAllowed(Slot $slot, $type)
    {
        $user = Auth::user();
        $roles = $slot->schedule->getRoles();
        $allowed = false;

        // Check each allowed role to see if the user has permission
        foreach($roles as $role)
        {
            $action = implode('-', [$type, $role->role->name, 'slot']);
            
            if($user->can($action))
            {
                $allowed = true;
            }
        }

        return $allowed;
    }

    
    // View form to take an existing slot
    public function takeForm(Request $request, Slot $slot)
    {
        return view('pages/slot/take', compact('slot'));
    }

    // Add yourself to an existing slot
    public function take(SlotRequest $request, Slot $slot)
    {
        // Error handling
        if($slot->schedule->password)
        {
            // TODO: Succeptable to timing attacks? lol
            if($request->get('password') != $slot->schedule->password)
            {
                $request->session()->flash('error', 'The password you entered is incorrect. Please contact your department lead to make sure you have the right password.');
                return redirect()->back();
            }
        }
        else
        {
            if(!$this->userAllowed($slot, 'take'))
            {
                $request->session()->flash('error', 'This shift is only available to certain user groups, your account must be approved by an administrator before signing up.');
                return redirect()->back();
            }
        }

        if($this->eventHasPassed($slot))
        {
            $request->session()->flash('error', 'This event has already passed, you are no longer able to sign up for shifts.');
            return redirect()->back();
        }

        // Has somebody else already taken this slot?
        if(is_null($slot->user))
        {
            $slot->user_id = Auth::user()->id;
            $slot->save();

            event(new SlotChanged($slot, ['status' => 'taken', 'name' => Auth::user()->name]));
            $request->session()->flash('success', 'You signed up for a volunteer shift.');

            // If a password was used
            if($slot->schedule->password)
            {
                // Assign the user to any roles this shift requires
                $roles = [];

                foreach($slot->schedule->getRoles() as $role)
                {
                    $roles[] = $role->name;
                }

                UserRole::assign(Auth::user(), $roles);
            }
        }
        else
        {
            $request->session()->flash('error', 'This slot has already been taken by someone else.');
        }
        
        return redirect('/event/' . $slot->event->id);
    }

    // View confirmation page before releasing a slot
    public function releaseForm(Request $request, Slot $slot)
    {
        return view('pages/slot/release', compact('slot'));
    }

    // Remove yourself from a slot
    public function release(Request $request, Slot $slot)
    {
        if(!$this->userAllowed($slot, 'release'))
        {
            $request->session()->flash('error', 'This shift is only available to certain user groups, your account must be approved by an administrator before signing up.');
        }
        else
        {
            if($this->eventHasPassed($slot))
            {
                $request->session()->flash('error', 'This event has already passed, you are no longer able to sign up for shifts.');
            }
            else
            {
                if(!is_null($slot->user) && $slot->user->id === Auth::user()->id)
                {
                    $slot->user_id = null;
                    $slot->save();

                    event(new SlotChanged($slot, ['status' => 'released']));
                    $request->session()->flash('success', 'You are no longer volunteering for your shift.');
                }
                else
                {
                    $request->session()->flash('error', 'You are not currently scheduled to volunteer for this shift.');
                }
            }
        }

        return redirect('/event/' . $slot->event->id);
    }
}
