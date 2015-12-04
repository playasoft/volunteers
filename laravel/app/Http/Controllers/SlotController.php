<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\SlotRequest;
use App\Models\Slot;

class SlotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // View form to take an existing slot
    public function takeForm(Request $request, Slot $slot)
    {
        $this->authorize('take-slot');
        return view('pages/slot/take', compact('slot'));
    }

    // Add yourself to an existing slot
    public function take(SlotRequest $request, Slot $slot)
    {
        $request->session()->flash('success', 'Slot has been taken.');
        return redirect('/event/' . $slot->event->id);
    }

    // View confirmation page before releasing a slot
    public function releaseForm(Request $request, Slot $slot)
    {
        $this->authorize('release-slot');
        return view('pages/slot/release', compact('slot'));
    }

    // Remove yourself from a slot
    public function release(Request $request, Slot $slot)
    {
        $this->authorize('release-slot');
        $request->session()->flash('success', 'Slot has been released.');
        return redirect('/event/' . $slot->event->id);
    }
}
