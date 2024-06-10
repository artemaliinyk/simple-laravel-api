<?php

namespace Tests\Unit;

use App\Http\Controllers\SubmissionController;
use App\Http\Requests\SubmissionRequest;
use App\Jobs\ProcessSubmission;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SubmissionTest extends TestCase
{
    /** @test */
    public function test_validation_fails_for_missing_required_fields()
    {
        $request = new SubmissionRequest();

        $validator = Validator::make([], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->messages());
        $this->assertArrayHasKey('email', $validator->errors()->messages());
        $this->assertArrayHasKey('message', $validator->errors()->messages());
    }

    /** @test */
    public function test_validation_fails_for_invalid_email_format()
    {
        $request = new SubmissionRequest();

        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'message' => 'This is a test message.'
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->messages());
    }

    /** @test */
    public function test_validation_passes_with_valid_data()
    {
        $request = new SubmissionRequest();

        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.'
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function test_job_is_dispatched_with_valid_data()
    {
        Queue::fake();

        $request = new SubmissionRequest([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.'
        ]);

        $controller = new SubmissionController();
        $response = $controller->submit($request);

        $this->assertEquals(200, $response->getStatusCode());

        Queue::assertPushed(ProcessSubmission::class, function ($job) {
            return $job->data['name'] === 'John Doe' &&
                $job->data['email'] === 'john.doe@example.com' &&
                $job->data['message'] === 'This is a test message.';
        });
    }
}
