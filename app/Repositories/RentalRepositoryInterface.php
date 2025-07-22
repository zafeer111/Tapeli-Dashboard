<?php

namespace App\Repositories;

interface RentalRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function checkAvailability($items, $bundles, $date);
}
