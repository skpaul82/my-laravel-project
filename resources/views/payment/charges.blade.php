<div class="">
	<div class="col-xs-12 col-md-12 table-responsive">
		<h2>{{ str_plural('Payment', $charges->data) }}</h2>
		<table class="table table-inverse">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Date</th>
					<th class="text-right">Amount</th>
					<th class="text-left"></th>
					<th class="text-center">Description</th>
					{{-- <th class="text-center">Mode</th> --}}
					<th class="text-center">Status</th>
				</tr>
			</thead>
			<tbody>
				{{-- {{ dd($charges->data) }} --}}
				@if(isset($charges->data) AND count($charges->data) > 0)
					{{-- @foreach($charges->data as $key=>$charge) --}}
					@foreach($charges->autoPagingIterator() as $key=>$charge)
					<tr>
						<td class="text-center">{{ $key+1 }}</td>
						<td class="text-center">{{ date('Y/m/d H:i:s', ($charge->created)) }}</td>
						<td class="text-right">{{ number_format($charge->amount/100, 2) }}</td>
						<td class="text-left">{{ strtoupper($charge->currency) }}</td>
						<td class="text-right">{{ $charge->description }} </td>
						{{-- <td>{{ $charge->description }} </td> --}}
						<td class="text-center text-capitalize">{{ $charge->status }}</td>
					</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
