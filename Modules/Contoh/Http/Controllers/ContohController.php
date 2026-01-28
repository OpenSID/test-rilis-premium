<?php

use App\Models\User;

defined('BASEPATH') OR exit('No direct script access allowed');

class ContohController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'title' => 'Module Contoh',
            'message' => 'This is from Modules/Contoh/Http/Controllers/ContohController',
            'method' => 'index',
            'route_examples' => [
                'current_route' => 'baru.index',
                'generated_urls' => [
                    'index' => ci_route('baru.index'),
                    'show' => ci_route('baru.show', ['id' => 123]),
                    'detail' => ci_route('baru.detail', ['id' => 456]),
                ],
            ],
        ];

        return response()->json($data);
    }

    public function show($id = null)
    {
        $data = [
            'title' => 'Module Contoh - Show',
            'message' => 'Showing item with ID: ' . $id,
            'method' => 'show',
            'id' => $id,
            'route_info' => [
                'current_route' => 'baru.show',
            ],
        ];

        return response()->json($data)->setEncodingOptions(JSON_PRETTY_PRINT);
    }

    public function detail($id = null)
    {
        $data = [
            'title' => 'Module Contoh - Detail',
            'message' => 'Detail for item with ID: ' . $id,
            'method' => 'detail',
            'id' => $id,
            'route_info' => [
                'current_route' => 'baru.detail',
                'generated_url' => ci_route('baru.detail', ['id' => $id]),
                'back_to_index' => ci_route('baru.index'),
                'show_url' => ci_route('baru.show', ['id' => $id]),
            ],
        ];

        return response()->json($data)->setEncodingOptions(JSON_PRETTY_PRINT);
    }

    public function store()
    {
        $postData = file_get_contents('php://input');

        $data = [
            'title' => 'Module Contoh - Store',
            'message' => 'Data stored successfully',
            'route' => '/contoh/store',
            'method' => 'store',
            'received_data' => $postData,
        ];

        return response()->json($data);
    }

    public function akas($id, $name = null)
    {
        $akas = User::find($id);
        
        dd([
            'id' => $akas->toArray(),
            'name' => $name ?? 'Nama tidak diberikan',
            'message' => 'Halo dari DemoCi3 akas method',
        ]);
    }

    public function viewDemo()
    {
        $data = [
            'title' => 'Demo View dengan Assets',
            'message' => 'Halaman sederhana dengan assets dari folder root',
        ];

        return view('contoh.demo', $data);
    }
}
