@component('mail::message')
Hi <b>{{ $user->name }}</b>,
<p>We have sended you a confirmation mail.</p>
<p>Simply click the button below to verify your email address.</p>
<p>
@component('mail::button', ['url' => url('verify_user/' .base64_encode($user->id))])
Verify
@endcomponent
</p>
<p>Thank you for signup in Easy buy</p>
@endcomponent