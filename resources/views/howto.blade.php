<div class="col-12 mb-5">
	<div class="card">
		<div class="card-body">
			<h4 class="pb-2">How-To: {{ $model->title }}</h4>
			<p><strong>Category: {{ $model->category }}</strong></p>
			{!! $model->objective !!}
			<hr class="mb-5">

	@foreach($model->howtosteps as $repeatable)
			<h3>Step {!! $repeatable->stepnumber !!}</h3>
			{!! $repeatable->stepdescription !!}
			@if( isset($repeatable->image['url']) )	
				<img src="{{ $repeatable->image['url'] }}" class="img-fluid">
			@endif		
			<hr class="mb-5">
	@endforeach
			

		</div>
	</div>
</div>

