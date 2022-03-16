<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Test;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExamTestsControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker;

    private User $user;
    private Subject $subject;
    private array $defaultData =
    [
        'subject_id' => 1,

        'topics' => [
            ['name' => 'Topic 1'],
            ['name' => 'Topic 2'],
            ['name' => 'Topic 3'],
        ]
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->subject = Subject::factory()->create([
            'user_id' => $this->user->id
        ]);

        $this->withMiddleware('auth:sanctum');
    }

    // TESTS

    /**
     * 
     * @test
     */
    public function exam_test_created_successfully()
    {
        Sanctum::actingAs($this->user);

        $dataToCreateTest = Test::factory()->make(
            [
                'subject_id' => $this->subject,
                'effective_date' => Exam::factory()->make()->effective_date,
                'topics' => Topic::factory()->count($this->faker()->randomDigitNotZero())->make()->toArray()
            ]
        )->toArray();


        $response = $this->postJson(
            route('tests.store'),
            $dataToCreateTest
        );


        $response->assertCreated();

        $this->assertEquals($dataToCreateTest['effective_date'], Test::find($response->getData()->id)->exam->effective_date);
    }

    /**
     * 
     * @test
     * 
     * @dataProvider invalidatedDataToCreateExamTest
     */
    public function register_exam_test_should_fail_because_data_is_not_valid($invalidatedDataToCreateExamTest)
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson(
            route('tests.store'),
            $invalidatedDataToCreateExamTest
        );

        $response->assertUnprocessable();
    }

    //DATA PROVIDERS
    public function invalidatedDataToCreateExamTest(): array
    {

        $this->defaultData['effective_date'] = now()->addDays(7)->toDateTimeString();


        return [
            'Subject id is missing' => [
                collect($this->defaultData)->forget('subject_id')->toArray()
            ],
            'Subject does not exist' => [
                collect($this->defaultData)->replace(
                    [
                        'subject_id' =>
                        $this->faker(Test::class)->numberBetween(1000, 10000)
                    ]
                )->toArray()
            ],
            'Effective date is missing' => [
                collect($this->defaultData)->forget('effective_date')->toArray()
            ],

            'Effective date before today' => [
                collect($this->defaultData)->replace([
                    'effective_date' =>
                    now()->subDays(7)->toDateTimeString()
                ])->toArray()
            ],

            'Topics has not name' => [
                collect($this->defaultData)->replace([
                    'topics' => [
                        ['name' => ''],
                        ['name' => ''],
                        ['name' => ''],
                    ]
                ])->toArray()
            ],

            'Topics has empty topic' => [
                collect($this->defaultData)->replace([
                    'topics' => [
                        [],
                        [],
                        [],
                    ]
                ])->toArray()
            ],

        ];
    }
}
