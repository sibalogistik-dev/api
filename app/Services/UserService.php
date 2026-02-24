<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['password']) && $data['password']) {
                $data['password'] = bcrypt($data['password']);
            } else {
                $data['password'] = bcrypt('password');
            }
            $data['email_verified_at'] = now();
            $user = User::create($data);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(User $user, array $data)
    {
        DB::beginTransaction();
        try {
            $user->update($data);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
