<div class="row">
	<div class="col-xs-12 col-md-12">
		<table class="table table-inverse">
			<thead>
				<tr>
					<th>#</th>
					<th>Cards</th>
					<th>Type</th>
				</tr>
			</thead>
			<tbody>
				@if(isset($cards->data) AND count($cards->data) > 0)
					@foreach($cards->data as $key=>$card)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>
							<i class="fab fc-cc-{{ str_slug($card->brand) }}"></i>
							{{ $card->brand .'....'. $card->last4 }}
						</td>
						<td class="text-capitalize">{{ $card->funding }}</td>
					</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
