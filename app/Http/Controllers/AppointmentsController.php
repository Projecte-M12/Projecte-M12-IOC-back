<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtén todas las citas
        $appointments = \App\Models\Appointment::all();
        
        // Devuelve las citas como una respuesta JSON
        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida los datos de la solicitud
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'provider_id' => 'required|exists:providers,id',
            'service_id' => 'required|string',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'modification_type' => 'nullable|string',
        ]);
    
        // Crea una nueva cita con los datos de la solicitud
        $appointment = new \App\Models\Appointment;
        $appointment->user_id = $request->user_id;
        $appointment->provider_id = $request->provider_id;
        $appointment->service_id = $request->service_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        $appointment->modification_type = $request->modification_type;
        $appointment->save();
    
        // Devuelve la cita creada como una respuesta JSON
        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Busca la cita por su ID
        $appointment = \App\Models\Appointment::find($id);
        
        // Si la cita no se encuentra, devuelve una respuesta 404
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
    
        // Devuelve la cita como una respuesta JSON
        return response()->json($appointment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Busca la cita por su ID
        $appointment = \App\Models\Appointment::find($id);
        
        // Si la cita no se encuentra, devuelve una respuesta 404
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
    
        // Elimina la cita
        $appointment->delete();
    
        // Devuelve una respuesta JSON indicando que la cita fue eliminada
        return response()->json(['message' => 'Appointment deleted']);
    }
}
