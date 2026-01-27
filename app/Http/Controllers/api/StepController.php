<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Step;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;

class StepController extends Controller
{
    public function store(Request $request){
        $user = User::where('device_id', $request->device_id)->first();
        if($user == null){
            $user = new User;
            $user->name = $request->name;
            $user->device_id = $request->device_id;
            $dateTime = new DateTime();
            $user->password = $dateTime->getTimestamp();
            $user->email = $dateTime->getTimestamp() . rand(1, 1000) . "@gimnazija.net";
            $user->save();
        }


        $steps = Step::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if (!$steps) {
            $steps = new Step;
            $steps->user_id = $user->id;
        }

        $steps->amount = $request->amount;
        $steps->save();

    }

    public function results() {
        $users = User::withSum('steps', 'amount')
            ->orderBy('steps_sum_amount', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'user_name'   => $user->name,
                    'total_steps' => (int) ($user->steps_sum_amount ?? 0),
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $users
        ], 200);
    }
}
