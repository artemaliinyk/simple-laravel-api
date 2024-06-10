<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessSubmission;

class SubmissionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_submits_valid_data_successfully()
    {
        Queue::fake();

        $response = $this->postJson('/api/submit', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.'
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Submission received and will be processed shortly.']);

        Queue::assertPushed(ProcessSubmission::class);
    }

    /** @test */
    public function test_validation_fails_for_missing_required_fields()
    {
        $response = $this->postJson('/api/submit', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'message']);
    }

    /** @test */
    public function test_validation_fails_for_invalid_email_format()
    {
        $response = $this->postJson('/api/submit', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'message' => 'This is a test message.'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function test_pushes_job_to_queue_with_valid_data()
    {
        Queue::fake();

        $this->postJson('/api/submit', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.'
        ]);

        Queue::assertPushed(ProcessSubmission::class, function ($job) {
            return $job->data['name'] === 'John Doe' &&
                $job->data['email'] === 'john.doe@example.com' &&
                $job->data['message'] === 'This is a test message.';
        });
    }
}
