@extends('layouts.backend')

@section('content')
<div class="box">
	<!-- Custom Tabs -->
	<div class="nav-tabs-custom">
        Hello, {{ session('username') }}
	</div>
	<!-- nav-tabs-custom -->
</div>
@endsection
