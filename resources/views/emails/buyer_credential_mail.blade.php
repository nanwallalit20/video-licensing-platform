@extends('emails.layout.email')
@section('title', 'MovieRites - Buyer Credential Mail')
@section('content')
<p>Hi {{ $user_name }},</p>
<p>We are excited to let you know that your buyer account has been created successfully. You can now log in using the email address <strong>{{ $user_email }}</strong>.</p>
<p>For security reasons, we require you to reset your password before you log in. Please click the button below to set your new password.</p>
<div style="text-align: center; margin: 30px 0;">
    <a href="{{ $resetPasswordLink }}"
       style="display: inline-block;
              background-color: #333;
              color: white;
              padding: 14px 30px;
              text-decoration: none;
              border-radius: 5px;
              font-weight: bold;
              font-size: 16px;">
        Reset Password
    </a>
</div>
<p>If you have any questions or concerns, please don't hesitate to contact our support team.</p>
<p>Thanks,</p>
<p><strong>{{ config('app.name') }}</strong></p>
@endsection
