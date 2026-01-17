<?php

namespace App\Services;

use App\Models\EmployeeTraining;
use App\Models\FcmToken;
use App\Models\Karyawan;
use App\Models\User;
use App\Notifications\NewTrainingNotification;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeeTrainingService
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $et = EmployeeTraining::create($data);
            DB::commit();
            $user = Karyawan::find($data['employee_id'])?->user;
            $this->notifyNewTraining(
                $user,
                'info',
                'Anda memiliki training baru yang perlu diikuti.',
                $data['training_name'],
                '/notification/read'
            );
            return $et;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to save employee training data: ' . $e->getMessage());
        }
    }

    public function update(EmployeeTraining $employeeTraining, array $data)
    {
        DB::beginTransaction();
        try {
            $employeeTraining->update($data);
            DB::commit();
            return $employeeTraining;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to update employee training data: ' . $e->getMessage());
        }
    }

    public function report(array $data)
    {
        DB::beginTransaction();
        try {
            $response = EmployeeTraining::query()
                ->filter($data)
                ->get();
            DB::commit();
            return $response;
        } catch (Exception $e) {
            throw new Exception('Failed to generate employee training report: ' . $e->getMessage());
        }
    }

    public function document(array $data)
    {
        DB::beginTransaction();
        try {
            $response = EmployeeTraining::find($data['employee_training_id']);
            if (!$response) {
                throw new Exception('Employee training data not found');
            }
            $response->load('employee', 'trainingType', 'schedules.mentor');
            $response->schedules()->orderBy('start_date', 'asc');
            DB::commit();
            return $response;
        } catch (Exception $e) {
            throw new Exception('Failed to generate employee training document: ' . $e->getMessage());
        }
    }

    public function notifyNewTraining(
        User $user,
        string $status,
        string $title,
        string $message,
        ?string $url = null
    ) {
        $notif = new NewTrainingNotification([
            'title'   => $title,
            'message' => $message,
            'status'  => $status,
            'url'     => $url,
        ]);
        $user->notify($notif);
    }
}
