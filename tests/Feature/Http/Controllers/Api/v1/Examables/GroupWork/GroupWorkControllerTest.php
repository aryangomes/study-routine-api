<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Examables\GroupWork;

use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Domain\User\Models\User;
use App\Support\Traits\CreateAModelFromFactory;
use App\Support\Traits\UserCanAccessThisRoute;
use Database\Seeders\Tests\Examables\GroupWork\GroupWorkTestSeeder;
use Database\Seeders\UserSeeder;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GroupWorkControllerTest extends TestCase
{
    use  RefreshDatabase,
        WithFaker,
        UserCanAccessThisRoute,
        CreateAModelFromFactory;

    private User $user;
    private Subject $subject;
    private Exam $exam;
    private GroupWork $examGroupWork;
    private array $defaultData =
    [
        'subject_id' => 1,
        'topic' => 'Some topic'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(GroupWorkTestSeeder::class);

        $this->examGroupWork = GroupWork::first();

        $this->exam = $this->examGroupWork->exam;

        $this->subject = $this->exam->subject;

        $this->user = $this->subject->user;

        $this->initializeModelAndModelName('groupWork', $this->examGroupWork);

        $this->withMiddleware(['auth:sanctum', 'verified']);
    }

    // TESTS

    /**
     * 
     * @test
     */
    public function exam_group_work_created_successfully()
    {
        Sanctum::actingAs($this->user);

        $dataToCreateGroupWork = GroupWork::factory()->make(
            [
                'subject_id' => $this->subject,
                'effective_date' => $this->exam->effective_date,

            ]
        )->toArray();

        $response = $this->postJson(
            route('groupsWork.store'),
            $dataToCreateGroupWork
        );

        $response->assertCreated();


        $this->assertEquals($dataToCreateGroupWork['effective_date'], GroupWork::find($response->getData()->id)->exam->effective_date);
    }



    /**
     * 
     * @test
     * 
     * @dataProvider invalidatedDataToCreateExamGroupWork
     */
    public function register_exam_group_work_should_fail_because_data_is_not_valid($invalidatedDataToCreateExamGroupWork)
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson(
            route('groupsWork.store'),
            $invalidatedDataToCreateExamGroupWork
        );

        $response->assertUnprocessable();
    }

    /**
     * 
     * @test
     */
    public function exam_group_work_with_members_created_successfully()
    {
        Sanctum::actingAs($this->user);

        $membersQuantityWithoutOwnerMemberOfGroupWork = 3;

        $memberForGroupWork =
            [
                'members' =>
                Member::factory()
                    ->withoutGroupWork()
                    ->count($membersQuantityWithoutOwnerMemberOfGroupWork)
                    ->create()
                    ->toArray()
            ];

        $dataToCreateGroupWork = GroupWork::factory()->make(
            [
                'subject_id' => $this->subject,
                'effective_date' => $this->exam->effective_date,

            ]
        )->toArray();

        $dataToCreateGroupWork = array_merge($dataToCreateGroupWork, $memberForGroupWork);

        $response = $this->postJson(
            route('groupsWork.store'),
            $dataToCreateGroupWork
        );

        $response->assertCreated();

        $this->assertCount(($membersQuantityWithoutOwnerMemberOfGroupWork + 1),
            GroupWork::find($response->getData()->id)->members
        );
    }

    /**
     * @test
     */
    public function show_exam_group_work_successfully()
    {

        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'groupsWork.show',
                ['groupWork' => $this->examGroupWork]
            )
        );
        $dataFromResponse = $response->getData();

        $response->assertOk();

        $response->assertJsonPath('exam.effective_date', $dataFromResponse->exam->effective_date);
    }

    /**
     * @test
     */
    public function get_all_exam_groupsWork_successfully()
    {
        Sanctum::actingAs($this->user);

        User::factory()->count(2)->create()->each(
            function ($user) {
                $subject = Subject::factory()->create([
                    'user_id' => $user->id
                ]);
                $examGroupWorksUser = Exam::factory()->count(
                    $this->faker()->randomDigitNotZero()
                )->groupWork()->create([
                    'subject_id' => $subject->id
                ]);
            }
        );
        $examGroupWorks = Exam::factory()->count(
            $this->faker()->randomDigitNotZero()
        )->groupWork()->create([
            'subject_id' => $this->subject->id
        ]);



        $response = $this->getJson(
            route(
                'groupsWork.index'
            )
        );


        $response->assertOk();
    }

    /**
     * 
     * @test
     */
    public function exam_group_work_updated_successfully()
    {
        Sanctum::actingAs($this->user);


        $dataToUpdateGroupWork =
            [
                'effective_date' => Exam::factory()->make()->effective_date,
            ];

        $response = $this->patchJson(
            route('groupsWork.update', [
                'groupWork' =>
                $this->examGroupWork
            ]),
            $dataToUpdateGroupWork
        );

        $response->assertOk();

        $this->assertEquals($dataToUpdateGroupWork['effective_date'], GroupWork::find($response->getData()->id)->exam->effective_date);
    }

    /**
     * 
     * @test
     * 
     * @dataProvider invalidatedDataToUpdateExamGroupWork
     */
    public function update_exam_group_work_should_fail_because_data_is_not_valid($invalidatedDataToUpdateExamGroupWork)
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route('groupsWork.update', [
                'groupWork' => $this->examGroupWork
            ]),
            $invalidatedDataToUpdateExamGroupWork
        );

        $response->assertUnprocessable();
    }

    /**
     * @test
     */
    public function delete_exam_group_work_successfully()
    {

        Sanctum::actingAs($this->user);
        $response = $this->deleteJson(
            route(
                'groupsWork.destroy',
                ['groupWork' => $this->examGroupWork]
            )
        );

        $response->assertNoContent();

        $this->assertDeleted('exams', $this->examGroupWork->toArray());
        $this->assertDeleted('groups_work', $this->examGroupWork->toArray());
    }



    //DATA PROVIDERS
    public function invalidatedDataToCreateExamGroupWork(): array
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
                        $this->faker(GroupWork::class)->numberBetween(1000, 10000)
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


            'Topic is missing' => [collect($this->defaultData)
                ->forget('topic')->toArray()],

            'Topic is not string' => [
                ['topic' => 123456, 'topic']
            ],
            'Topic should have max length 150 characters' => [
                ['topic' => Str::repeat('a', 151), 'topic']
            ],

            'Note is not string' => [
                ['note' => 123456, 'note']
            ],

            'Note should have max length 250 characters' => [
                ['note' => Str::repeat('a', 251), 'note']
            ],

        ];
    }

    public function invalidatedDataToUpdateExamGroupWork(): array
    {

        return [
            'Effective date before today' => [
                collect($this->defaultData)->replace([
                    'effective_date' =>
                    now()->subDays(7)->toDateTimeString()
                ])->toArray()
            ],
            'New topic is not string' => [
                ['topic' => 123456, 'topic']
            ],

            'New topic should have max length 150 characters' => [
                ['topic' => Str::repeat('a', 151), 'topic']
            ],

            'New note is not string' => [
                ['note' => 123456, 'note']
            ],

            'New note should have max length 250 characters' => [
                ['note' => Str::repeat('a', 251), 'note']
            ],



        ];
    }

    public function routesResourceWithPolicies(): array
    {
        $routesResourceWithPolicies = $this->makeRoutesResourceWithPolicies('GroupWork', 'groupsWork');

        $routesResourceWithPolicies = Arr::except($routesResourceWithPolicies, array_keys($routesResourceWithPolicies)[0]);

        return $routesResourceWithPolicies;
    }

    public function routesResourceWithAuthentication(): array
    {

        return $this->makeRoutesResourceWithAuthentication('GroupWork', 'groupsWork');
    }

    public function routesResourceWithEmailVerified(): array
    {

        return $this->makeRoutesResourceWithEmailVerified('GroupWork', 'groupsWork');
    }
}
