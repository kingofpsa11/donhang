<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\InventoryRepository;

class InventoryService
{
    protected $inventoryRepository;


    public function __construct(InventoryRepository $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    public function index()
    {

    }

    public function checkInventory()
    {

    }
}