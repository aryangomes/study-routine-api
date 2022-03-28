<?php

namespace Tests\Feature\Http\Controllers\Api\v1;

use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\Examables\Test\Models\Test;
use Domain\Examables\Test\Topic\Models\Topic;
use Domain\User\Models\User;
use App\Support\Traits\CreateAModelFromFactory;
use App\Support\Traits\UserCanAccessThisRoute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExamTestsControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker, UserCanAccessThisRoute, CreateAModelFromFactory;

    private User $user;
    private Subject $subject;
    private Exam $examTest;
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

        $this->user = $this->createModelFromFactory(new User);

        $this->subject = $this->createModelFromFactory(new Subject, [
            'user_id' => $this->user
        ]);

        $this->examTest = $this->createModelFromFactory(
            new Exam,
            [
                'subject_id' => $this->subject->id
            ]
        );

        $this->initializeModelAndModelName('test', $this->examTest);


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

    /**
     * @test
     */
    public function show_exam_test_successfully()
    {

        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'tests.show',
                ['test' => $this->examTest->examable]
            )
        );
        $dataFromResponse = $response->getData();

        $response->assertOk();

        $response->assertJsonPath('exam.effective_date', $dataFromResponse->exam->effective_date);
    }

    /**
     * @test
     */
    public function get_all_exam_tests_successfully()
    {
        Sanctum::actingAs($this->user);

        User::factory()->count(2)->create()->each(
            function ($user) {
                $subject = Subject::factory()->create([
                    'user_id' => $user->id
                ]);
                $examTestsUser = Exam::factory()->count(
                    $this->faker()->randomDigitNotZero()
                )->create([
                    'subject_id' => $subject->id
                ]);

                $examTestsUser->each(function ($exam) {
                    (Topic::factory()
                        ->count($this->faker()->randomDigitNotZero())
                        ->create([
                            'test_id' => $exam->examable_id
                        ]));
                });
            }
        );
        $examTests = Exam::factory()->count(
            $this->faker()->randomDigitNotZero()
        )->create([
            'subject_id' => $this->subject->id
        ]);

        $examTests->each(function ($exam) {
            (Topic::factory()
                ->count($this->faker()->randomDigitNotZero())
                ->create([
                    'test_id' => $exam->examable_id
                ]));
        });

        $response = $this->getJson(
            route(
                'tests.index'
            )
        );


        $response->assertOk();
    }

    /**
     * 
     * @test
     */
    public function exam_test_updated_successfully()
    {
        Sanctum::actingAs($this->user);


        $dataToUpdateTest =
            [
                'effective_date' => Exam::factory()->make()->effective_date,
            ];

        $response = $this->patchJson(
            route('tests.update', [
                'test' =>
                $this->examTest->examable
            ]),
            $dataToUpdateTest
        );

        $response->assertOk();

        $this->assertEquals($dataToUpdateTest['effective_date'], Test::find($response->getData()->id)->exam->effective_date);
    }

    /**
     * 
     * @test
     * 
     * @dataProvider invalidatedDataToUpdateExamTest
     */
    public function update_exam_test_should_fail_because_data_is_not_valid($invalidatedDataToUpdateExamTest)
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route('tests.update', [
                'test' => $this->examTest->examable
            ]),
            $invalidatedDataToUpdateExamTest
        );

        $response->assertUnprocessable();
    }

    /**
     * @test
     */
    public function delete_exam_test_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(
            route(
                'tests.destroy',
                ['test' => $this->examTest->examable]
            )
        );

        $response->assertNoContent();

        $this->assertDeleted('exams', $this->examTest->toArray());
        $this->assertDeleted('tests', $this->examTest->examable->toArray());
    }



    //DATA PROVIDERS
    public function invalidatedDataToCreateExamTest(): array
    {

        $this->defaultData['effective_date'] =
            now()->addDays(7)->toDateTimeString();


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
                collect($this->defaultData)
                    ->forget('effective_date')->toArray()
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

    public function invalidatedDataToUpdateExamTest(): array
    {

        return [
            'Effective date before today' => [
                collect($this->defaultData)->replace([
                    'effective_date' =>
                    now()->subDays(7)->toDateTimeString()
                ])->toArray()
            ],


        ];
    }

    public function routesResourceWithPolicies(): array
    {
        $this->setModelName('test');

        return [

            "User cannot view {$this->modelName}" => ["{$this->modelName}s.show", 'get'],
            "User cannot update {$this->modelName}" => ["{$this->modelName}s.update", 'patch'],
            "User cannot delete {$this->modelName}" => ["{$this->modelName}s.destroy", 'delete'],
        ];
    }

    public function routesResourceWithAuthentication(): array
    {
        $this->setModelName('test');
        return $this->makeRoutesResourceWithAuthentication();
    }
}
