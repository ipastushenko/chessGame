<p>Hello {{ $user->name }}.</p>
<p>Thank you for registering on ChessGame</p>
<p>
    <span>Click </span>
    <a href="{{ url('user/confirmation', $token) }}">here</a>
    <span> to confirm your email</span>
</p>
<p>Your verificaion link will be expired after one day</p>
<p>--<br/>Best regards, ChessGame team</p>
