<?php
use Carbon\Carbon;
?>

{{$test}}

{{$now = Carbon::now('Asia/Ho_Chi_Minh');}}

</br>
@foreach ($test as $t)
<h1>
{{$now->diffInMinutes($t->created_at)}}
@if($now->diffInMinutes($t->created_at)>1)
<p>hello</p>
</h1>
@elseif($now->diffInMinutes($t->created_at)==0)
<p>cc</p>
@endif
@endforeach