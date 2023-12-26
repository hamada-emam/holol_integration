<?php

namespace App\Http\Controllers\Core;

use Illuminate\Queue\Failed\FailedJobProviderInterface;
use App\Http\Controllers\Controller;
use App\Models\Shipment;

class FailedJobsController extends Controller
{
    public $failedJobProvider;

    /**
     * 
     *
     * @param [type] $id
     * @return void
     */
    function index()
    {
        $failedJobProvider = app(FailedJobProviderInterface::class);
        $failedJobs = $failedJobProvider->all();
        $failedData = [];
        $data = [];

        /** @var mixed */
        foreach ($failedJobs as $job) {
            $shipment = unserialize(json_decode($job->payload)->data->command)->shipment;
            $failedData['shipmentId'] = $shipment['id'];
            $failedData['shipmentCode'] = $shipment['code'];
            $failedData['failedAt'] = $job->failed_at;
            $failedData['failedJobID'] = $job->id;
            $data[] = $failedData;
        }

        return view('failed-jobs', compact(['data']));
    }

    /**
     * 
     *
     * @param int $id
     * @return void
     */
    function retry($id = null)
    {
        $this->failedJobProvider = app(FailedJobProviderInterface::class);
        if ($id) {
            $job = $this->failedJobProvider->find($id);
            if ($job) $this->delete($job);
        } else {
            $failedJobs = $this->failedJobProvider->all();
            foreach ($failedJobs as $job) {
                $this->delete($job);
            }
        }

        return redirect('failed');
    }

    private function delete($job)
    {
        $shipment = unserialize(json_decode($job->payload)->data->command)->shipment;
        Shipment::where('shipment_id', $shipment['id'])->whereNull('order_code')->delete();
        $this->failedJobProvider->forget($job->id);
    }
}
