<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    
    public function index(Request $request)
    {
        $user = $request->user();
        // Fetch all appointments for the authenticated user
        $appointments = Appointment::where('user_id', $user->id)->get();
        return response()->json(['appointments' => $appointments], 200);
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $validator = \Validator::make($request->all(), [
            'user_token' => 'required|exists:users,remember_token',
            'healthcare_professional_id' => 'required|exists:healthcare_professionals,id',
            'appointment_start_time' => 'required|date_format:Y-m-d H:i:s',
            'appointment_end_time' => 'required|date_format:Y-m-d H:i:s|after:appointment_start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>$validator->messages(),
            ], 200);
        }

        // Check availability
        $start = $request->input('appointment_start_time');
        $end = $request->input('appointment_end_time');
        $available = $this->checkAvailability($request->input('healthcare_professional_id'), $start, $end);

        if (!$available) {
            return response()->json(['message' => 'The selected professional is not available for the given time.'], 400);
        }

        $user_info = User::where('remember_token',$request->user_token)->first();
        // Create the appointment
        $appointment = new Appointment([
            'user_id' => $user_info->id,
            'healthcare_professional_id' => $request->healthcare_professional_id,
            'appointment_start_time' => $request->appointment_start_time,
            'appointment_end_time' => $request->appointment_end_time,
            'status'=>'booked'
        ]);

        if($appointment->save()){
            return response()->json(['message' => 'Appointment booked successfully.', 'appointment' => $appointment], 201);
        }else{
            return response()->json(['message' => 'Something went wrong'], 400);
        }
      
    }

    private function checkAvailability($professionalId, $start, $end)
    {
        // Check if there are any appointments overlapping with the given time slot
        $existingAppointments = Appointment::where('healthcare_professional_id', $professionalId)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('appointment_start_time', [$start, $end])
                      ->orWhereBetween('appointment_end_time', [$start, $end]);
            })
            ->exists();
        return !$existingAppointments;
    }

    public function cancel(Request $request, $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        // Check if cancellation is allowed based on the cancellation policy
        $cancellationPolicy = Carbon::parse($appointment->appointment_start_time)->subHours(24);
        if (Carbon::now()->lte($cancellationPolicy)) {
            return response()->json(['message' => 'Cancellation is not allowed within 24 hours of the appointment time.'], 403);
        }

        // Proceed with the cancellation
        $appointment->status = 'cancelled';
        $appointment->save();

        return response()->json(['message' => 'Appointment cancelled successfully.']);
    }

    public function markAsCompleted(Request $request, $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        // Update the appointment status to completed
        $appointment->status = 'completed';
        $appointment->save();

        return response()->json(['message' => 'Appointment marked as completed successfully.']);
    }

}
