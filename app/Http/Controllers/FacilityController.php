<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\View\View;

class FacilityController extends Controller
{
    /**
     * Menampilkan daftar fasilitas resmi armada PO CAN Travel.
     */
    public function index(): View
    {
        $facilities = Facility::with(['buses' => function ($q) {
            $q->select('buses.id', 'buses.name', 'buses.code', 'buses.bus_type');
        }])->orderBy('id', 'asc')->get();

        return view('facilities.index', compact('facilities'));
    }
}
