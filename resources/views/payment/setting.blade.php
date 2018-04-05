<div class="">
	<div class="col-xs-12 col-md-12 table-responsive">
		
		<h2>{{ str_plural('Card', $cards->data) }} </h2>
		<table class="table table-inverse">
			<thead>
				<tr>
					<th width="30">#</th>
					<th>CARD #</th>
					<th>TYPE</th>
				</tr>
			</thead>
			<tbody>
				@if(isset($cards->data) AND count($cards->data) > 0)
					@foreach($cards->data as $key=>$card)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>
							<i class="fa fa-cc-{{ str_slug($card->brand) }}"></i> 
							{{-- {{ $card->brand .' ....'. $card->last4 }} --}}
							{{ ' ....'. $card->last4 }}

							@if($card->id == $default_source)
								<i class="fa fa-check text-success" title="Default charging source"></i> 
							@endif							
						</td>
						<td class="text-capitalize">{{ $card->funding }}</td>
					</tr>
					@endforeach
				@endif
			</tbody>
		</table>
		
		<hr/>
		
		<h2>{{ str_plural('Bank Account', $bank_accounts->data) }} </h2>
		<table class="table table-inverse">
			<thead>
				<tr>
					<th width="30">#</th>
					<th>ACCOUNT</th>
					<th></th>
					<th>ACCOUNT NAME</th>
					{{-- <th>Status</th> --}}
				</tr>
			</thead>
			<tbody>
				@if(isset($bank_accounts->data) AND count($bank_accounts->data) > 0)
					@foreach($bank_accounts->data as $key=>$bank_account)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>
							<i class="fa fa-bank"></i> 
							{{ $bank_account->bank_name .' ....'. $bank_account->last4 }}

							@if($bank_account->id == $default_source)
								<i class="fa fa-check text-success" title="Default charging source"></i> 
							@endif	
						</td>
						<td class="text-uppercase">{{ $bank_account->currency }}</td>
						<td class="text-capitalize">{{ $bank_account->account_holder_name }}</td>
						{{-- <td class="text-capitalize">{{ $bank_account->status }}</td> --}}
					</tr>
					@endforeach
				@endif
			</tbody>
		</table>
		
	</div>
</div>
