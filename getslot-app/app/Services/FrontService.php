<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\SellerRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;

class FrontService
{
    protected $ticketRepository;
    protected $categoryRepository;
    protected $sellerRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository, CategoryRepositoryInterface $categoryRepository, SellerRepositoryInterface $sellerRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->categoryRepository = $categoryRepository;
        $this->sellerRepository = $sellerRepository;
    }

    public function getFrontPageData()
    {
        $categories = $this->categoryRepository->getAllCategories();
        $sellers = $this->sellerRepository->getAllSellers();
        $popularTickets = $this->ticketRepository->getPopularTickets(4);
        $newTickets = $this->ticketRepository->getAllNewTickets();

        return compact('categories', 'popularTickets', 'newTickets', 'sellers');
    }
}
