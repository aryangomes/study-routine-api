@component('mail::message')
# Hi, {{$user->name}}

We are here to remember you about your Exam of Subject **{{$subject->name}}**, that will be in
*{{$exam->effective_date->format('l, M d, Y')}}*.

**Have a good studies and a good day!**<br>
**{{ config('app.name') }}**
@endcomponent