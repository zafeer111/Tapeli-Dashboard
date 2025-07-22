<?php

namespace App\Repositories;

interface SportRepositoryInterface
{
    public function getAllActive();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getTournamentsBySport($sportId);
}