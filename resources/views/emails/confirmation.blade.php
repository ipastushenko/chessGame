<p>Hello {{ $user->name }}.</p>
<p>Thank you for registering on ChessGame</p>
<p>
    <span>Click </span>
    <a href="{{ url('user/confirmation', $token) }}">here</a>
    <span> to confirm your email</span>
</p>
<p>--<br/>Best regards, ChessGame team</p>
