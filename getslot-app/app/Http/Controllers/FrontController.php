<?php

namespace App\Http\Controllers;

use App\Services\FrontService;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    protected $frontService;

    public function __construct(FrontService $frontService)
    {
        $this->frontService = $frontService;
    }
    public function index()
    {
        $data = $this->frontService->getFrontPageData();
        return view('front.index', $data);
    }

    public function details(Ticket $ticket)
    {
        return view('front.details', compact('ticket'));
    }

    public function category(Category $category)
    {
        return view('front.category', compact('category'));
    }
}
