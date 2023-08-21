<form method="post" action="{{ route('verifyToken') }}">
    @csrf
    <input type="text" name="password_verify_token" placeholder="Enter OTP">
    <input type="password" name="password" placeholder="Enter new password">
    <button type="submit">Reset Password</button>
</form>
