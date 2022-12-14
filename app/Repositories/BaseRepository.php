<?php

namespace App\Repositories;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


abstract class BaseRepository
{
    protected $table;
    protected $column = 'id';

    public function where(array $filters)
    {
        return DB::table($this->table)->where($filters)->get()->toArray();
    }

    public function store(array $data)
    {
        $data['created_at'] = Carbon::now()->timestamp;
        $data['updated_at'] = Carbon::now()->timestamp;

        return DB::table($this->table)->insert($data);
    }

    public function show(mixed $id, ?string $column = null)
    {
        return DB::table($this->table)->where($column ?: $this->column, $id)->first();
    }

    public function destroy(int $id)
    {
        return DB::table($this->table)->delete($id) ?: false;
    }

    public function update(int $id, array $data)
    {
        try {
            $data['updated_at'] = Carbon::now()->timestamp;

            return DB::table($this->table)
                ->where($this->column, $id)
                ->update($data);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
