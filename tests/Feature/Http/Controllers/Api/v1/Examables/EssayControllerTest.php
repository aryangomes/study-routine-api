<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Examables;

use App\Domain\Examables\Essay\Models\Essay;
use App\Support\Traits\UserCanAccessThisRoute;
use Carbon\Carbon;
use Database\Seeders\Tests\Examables\EssayTestSeeder;
use Domain\Exam\Models\Exam;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EssayControllerTest extends TestCase
{
    use  RefreshDatabase, WithFaker, UserCanAccessThisRoute;

    private User $user;
    private Essay $essay;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(EssayTestSeeder::class);

        $this->essay = Essay::first();

        $this->user = $this->essay->exam->subject->user;


        $this->initializeModelAndModelName('essay', $this->essay);
    }

    /**
     *
     * 
     * @test
     *
     */
    public function create_a_essay_successfully()
    {
        Sanctum::actingAs($this->user);

        $dataToCreateExam = Exam::factory()->make([
            'subject_id' => $this->user->subjects[0]
        ])->toArray();
        $dataToCreateEssay =
            Essay::factory()->make([
                'subject_id'
                => $dataToCreateExam['subject_id'],
                'effective_date'
                => $dataToCreateExam['effective_date'],
            ])->toArray();

        $response = $this->postJson(
            route('essays.store'),
            $dataToCreateEssay
        );

        $response->assertCreated();

        $this->assertEquals($dataToCreateEssay['topic'], Essay::find($response->getData()->id)->topic);
    }

    /**
     *
     * @dataProvider invalidatedDataToCreateEssay
     * 
     * @test
     *
     */
    public function create_a_essay_should_fail_because_invalid_data($dataToCreateEssay)
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson(
            route('essays.store'),
            $dataToCreateEssay
        );

        $response->assertUnprocessable();
    }


    /**
     *
     * 
     * @test
     *
     */
    public function update_a_essay_successfully()
    {
        Sanctum::actingAs($this->user);

        $dataToUpdateEssay = [
            'topic' => 'new topic',
            'observation' => 'new observation',
            'effective_date' =>
            Carbon::now()->addDays(8),

        ];

        $response = $this->patchJson(
            route(
                'essays.update',
                ['essay' => $this->essay]
            ),
            $dataToUpdateEssay
        );

        $response->assertOk();

        $this->assertEquals(
            $dataToUpdateEssay['topic'],
            Essay::find($response->getData()->id)->topic
        );
    }

    /**
     *
     * @dataProvider invalidatedDataToUpdateEssay
     * 
     * @test
     *
     */
    public function update_a_essay_should_fail_because_invalid_data($dataToUpdateEssay)
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson(
            route(
                'essays.update',
                ['essay' => $this->essay]
            ),
            $dataToUpdateEssay
        );

        $response->assertUnprocessable();
    }

    /**
     *
     * 
     * @test
     *
     */
    public function view_all_users_essays_successfully()
    {

        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'essays.index',
                ['essay' => $this->essay]
            )
        );

        $dataFromResponse = $response->getData();


        $response->assertOk();

        $this->assertEquals($this->essay->id, $dataFromResponse[0]->id);
    }

    /**
     *
     * @dataProvider queryParametersToFilterEssays
     * 
     * @test
     *
     */
    public function get_filtered_users_essays_successfully($key, $value, $jsonKey)
    {

        Sanctum::actingAs($this->user);

        if ($key == 'subject_id') {

            $essay = Exam::factory()->essay()->create([
                $key => $value
            ]);
        } else {
            $attributes = [
                $key => $value,

            ];
            $essay = Exam::factory()->essay($attributes)->create([
                'subject_id' => $this->user->subjects[0]->id
            ]);
        }




        $response = $this->getJson(
            route(
                'essays.index',
                [$key => $value]
            )
        );

        $response->assertOk();


        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where($jsonKey, $value)

            );
    }


    /**
     *
     * 
     * @test
     *
     */
    public function view_a_essay_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'essays.show',
                ['essay' => $this->essay]
            )
        );

        $dataFromResponse = $response->getData();

        $response->assertOk();

        $this->assertEquals($this->essay->id, $dataFromResponse->id);
    }

    /**
     *
     * 
     * @test
     *
     */
    public function view_a_essay_should_fail_because_essay_does_not_exist()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson(
            route(
                'essays.show',
                ['essay' => 100]
            )
        );

        $response->assertNotFound();
    }


    /**
     *
     * 
     * @test
     *
     */
    public function delete_a_essay_successfully()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(
            route(
                'essays.destroy',
                ['essay' => $this->essay]
            )
        );

        $response->assertNoContent();

        $this->assertNull(Essay::find($this->essay->id));
    }

    /**
     *
     * 
     * @test
     *
     */
    public function delete_a_essay_should_fail_because_essay_does_not_exist()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(
            route(
                'essays.destroy',
                ['essay' => 100]
            )
        );

        $response->assertNotFound();
    }



    //PROVIDERS

    public function invalidatedDataToCreateEssay(): array
    {

        $defaultData = [
            'subject_id' => 1,
            'topic' => 'topic',
            'observation' => 'observation',
            'effective_date' => Carbon::now()->addDays(7),
        ];

        $collectionDefaultData = collect($defaultData);



        $invalidatedDataToCreateEssay = [
            'Subject id is missing' => [
                $collectionDefaultData->forget('subject_id')->toArray()
            ],
            'Subject id does not exist' => [
                $collectionDefaultData->replace(['subject_id' => 100])->toArray()
            ],

            'Topic must be a string' => [
                $collectionDefaultData->replace(['topic' => 123])->toArray()
            ],
            'Topic must have max 250 characters' => [
                $collectionDefaultData->replace(['topic' => Str::repeat('a', 251)])->toArray()
            ],
            'Observation must be a string' => [
                $collectionDefaultData->replace(['observation' => 123])->toArray()
            ],
            'Observation must have max 1000 characters' => [
                $collectionDefaultData->replace(['observation' => Str::repeat('a', 1001)])->toArray()
            ],
            'Effective date must be same or after today' => [
                $collectionDefaultData->replace(['effective_date' => Carbon::yesterday()])->toArray()
            ],
        ];

        return $invalidatedDataToCreateEssay;
    }


    public function validatedDataToUpdateEssay(): array
    {

        $validatedDataToUpdateEssay = [
            'New topic' =>
            [
                [
                    'topic' => 'new topic',
                ], 'topic'
            ],
            'New observation' => [
                [
                    'observation' => 'new observation',
                ], 'observation'
            ],
            'New effective date' => [
                [
                    'effective_date' =>
                    Carbon::now()->addDays(8),
                ], 'effective_date'
            ],
        ];


        return $validatedDataToUpdateEssay;
    }

    public function invalidatedDataToUpdateEssay(): array
    {

        $defaultData = [
            'topic' => 'new topic',
            'observation' => 'new observation',
            'effective_date' => Carbon::now()->addDays(1),
        ];

        $collectionDefaultData = collect($defaultData);

        $invalidatedDataToUpdateEssay = [

            'Topic must be a string' => [
                $collectionDefaultData->replace(['topic' => 123])->toArray()
            ],
            'Topic must have max 250 characters' => [
                $collectionDefaultData->replace(['topic' => Str::repeat('a', 251)])->toArray()
            ],
            'Observation must be a string' => [
                $collectionDefaultData->replace(['observation' => 123])->toArray()
            ],
            'Observation must have max 1000 characters' => [
                $collectionDefaultData->replace(['observation' => Str::repeat('a', 1001)])->toArray()
            ],
            'Effective date must be same or after today' => [
                $collectionDefaultData->replace(['effective_date' => Carbon::yesterday()])->toArray()
            ],
        ];

        return $invalidatedDataToUpdateEssay;
    }

    public function queryParametersToFilterEssays()
    {
        return [
            'Query Parameter: subject_id' => [
                'subject_id', 1, '0.exam.subject.id',

            ],
            'Query Parameter: topic' => [
                'topic', 'Some topic', '0.topic',

            ],
            'Query Parameter: observation' => [
                'observation', 'Some observation', '0.observation',

            ],
        ];
    }


    public function routesResourceWithAuthentication(): array
    {

        return $this->makeRoutesResourceWithAuthentication('Essay', 'essays');
    }

    public function routesResourceWithPolicies(): array
    {
        $routesResourceWithPolicies
            = $this->makeRoutesResourceWithPolicies('Essay', 'essays');
        $routesResourceWithPolicies = Arr::except($routesResourceWithPolicies, [array_keys($routesResourceWithPolicies)[0]]);

        return $routesResourceWithPolicies;
    }

    public function routesResourceWithEmailVerified(): array
    {
        $routesResourceWithEmailVerified
            = $this->makeRoutesResourceWithEmailVerified('Essay', 'essays');
        $routesResourceWithEmailVerified = Arr::except($routesResourceWithEmailVerified, [array_keys($routesResourceWithEmailVerified)[0]]);

        return $routesResourceWithEmailVerified;
    }
}
