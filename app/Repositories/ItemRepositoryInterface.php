<?php

namespace App\Repositories;

interface ItemRepositoryInterface
{
    public function getAllAvailable();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function checkAvailability($id, $date, $quantity);
}