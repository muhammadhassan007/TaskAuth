
@if (session('message'))
    <div>{{ session('message') }}</div>
@endif
<form method="post" action="{{ route('ResetPasswordRequest') }}">
    @csrf
    <input type="email" name="email" placeholder="Enter your email">
    <button type="submit">Send OTP</button>
</form>
