<?php

namespace App\Repositories;

interface BundleRepositoryInterface
{
    public function getAllAvailable();
    public function find($id);
    public function create(array $data, array $items);
    public function update($id, array $data, array $items);
    public function delete($id);
}
