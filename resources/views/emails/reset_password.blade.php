@component('mail::message')
Hi <b>{{ $user->name }}</b>,
<p>We have sent you a otp code.</p>
<p>Simply paste the code below to reset your account.</p>
<br>
<h4>
{{ $user->otp }}
</h4>
<br>
<p>your otp code will expire in 10 minites</p>
<p>Thank you using Eazy buy</p>
@endcomponent