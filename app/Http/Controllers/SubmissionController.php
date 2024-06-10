<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmissionRequest;
use App\Jobs\ProcessSubmission;
use Illuminate\Http\JsonResponse;

class SubmissionController extends Controller
{
    public function submit(SubmissionRequest $request): JsonResponse
    {
        ProcessSubmission::dispatch($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Submission received and will be processed shortly.'
        ]);
    }
}
