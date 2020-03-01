<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\Notification;
use Auth;

class NotificationController extends Controller
{
    public function get(){
        //Se retornan las notificaciones no leidas del usuario logeado

        return Auth::user()->unreadNotifications;
    }
    
    public function leidas(){
        // Se verifica si hay notificaciones sin leer
        if(!Auth::user()->unreadNotifications) return Auth::user()->unreadNotifications;

        $notificacionesNoLeidas = Auth::user()->unreadNotifications;

        foreach ($notificacionesNoLeidas as $notificacion) {
            $notificacion->markAsRead();
        }

        return Auth::user()->notifications;
    }
}
