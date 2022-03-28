<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models {
	/**
	 * Class Exam
	 *
	 * @property int $id
	 * @property DateTime $effective_date
	 * @property int $subject_id
	 * @property \Illuminate\Support\Carbon|null $created_at
	 * @property \Illuminate\Support\Carbon|null $updated_at
	 * @property string $examable_type
	 * @property int $examable_id
	 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $examable
	 * @property-read \Domain\Subject\Models\Subject $subject
	 * @method static \Database\Factories\ExamFactory factory(...$parameters)
	 * @method static \Illuminate\Database\Eloquent\Builder|Exam newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|Exam newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|Exam query()
	 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereCreatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereEffectiveDate($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereExamableId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereExamableType($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereSubjectId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereUpdatedAt($value)
	 */
	class Exam extends \Eloquent
	{
	}
}

namespace App\Models\Examables {
	/**
	 * Class Test
	 * 
	 * Test is a Exam
	 *
	 * @property int $id
	 * @property int $exam_id
	 * @property \Illuminate\Support\Carbon|null $created_at
	 * @property \Illuminate\Support\Carbon|null $updated_at
	 * @property-read \App\Models\Exam|null $exam
	 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Topic[] $topics
	 * @property-read int|null $topics_count
	 * @method static \Database\Factories\Examables\TestFactory factory(...$parameters)
	 * @method static \Illuminate\Database\Eloquent\Builder|Test newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|Test newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|Test query()
	 * @method static \Illuminate\Database\Eloquent\Builder|Test whereCreatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Test whereId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Test whereUpdatedAt($value)
	 */
	class Test extends \Eloquent
	{
	}
}

namespace App\Models {
	/**
	 * Class Subject
	 *
	 * @property int $ids
	 * @property string $name
	 * @property int $user_id
	 * @property int $id
	 * @property \Illuminate\Support\Carbon|null $created_at
	 * @property \Illuminate\Support\Carbon|null $updated_at
	 * @property-read \Domain\User\Models\User $user
	 * @method static \Database\Factories\SubjectFactory factory(...$parameters)
	 * @method static \Illuminate\Database\Eloquent\Builder|Subject newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|Subject newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|Subject query()
	 * @method static \Illuminate\Database\Eloquent\Builder|Subject whereCreatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Subject whereId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Subject whereName($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Subject whereUpdatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Subject whereUserId($value)
	 */
	class Subject extends \Eloquent
	{
	}
}

namespace App\Models {
	/**
	 * Class Topic
	 * 
	 * Topic of a Test
	 *
	 * @property int $id
	 * @property string $name
	 * @property int $test_id
	 * @property \Illuminate\Support\Carbon|null $created_at
	 * @property \Illuminate\Support\Carbon|null $updated_at
	 * @property-read \App\Models\Examables\Test $test
	 * @method static \Database\Factories\TopicFactory factory(...$parameters)
	 * @method static \Illuminate\Database\Eloquent\Builder|Topic newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|Topic newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|Topic query()
	 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereCreatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereName($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereTestId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereUpdatedAt($value)
	 */
	class Topic extends \Eloquent
	{
	}
}

namespace App\Models {
	/**
	 * Class User
	 *
	 * @property string $id
	 * @property string $name
	 * @property string $username
	 * @property string $email
	 * @property string $password
	 * @property string $user_avatar_path
	 * @property \Illuminate\Support\Carbon|null $email_verified_at
	 * @property string|null $remember_token
	 * @property \Illuminate\Support\Carbon|null $created_at
	 * @property \Illuminate\Support\Carbon|null $updated_at
	 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
	 * @property-read int|null $notifications_count
	 * @property-read \Illuminate\Database\Eloquent\Collection|\Domain\Subject\Models\Subject[] $subjects
	 * @property-read int|null $subjects_count
	 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
	 * @property-read int|null $tokens_count
	 * @method static \Database\Factories\UserFactory factory(...$parameters)
	 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder|User query()
	 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserAvatarPath($value)
	 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
	 */
	class User extends \Eloquent
	{
	}
}
