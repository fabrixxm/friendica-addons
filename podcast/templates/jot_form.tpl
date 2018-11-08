</li></ul><br>
<div class="panel panel-default podcast">
	<div class="panel-heading"><i class="fa fa-podcast" aria-hidden="true"></i>	Podcast</div>
	<div class="panel-body form-horizontal">
		{{foreach $formats as $id=>$format}}
			<p class="text-muted">{{$format.label}} ({{$format.mime}})</p>
			<input type="hidden" id="podcast-{{$id}}-mime" value="{{$format.mime}}">
			<div class="form-group">
				<label for="podcast-{{$id}}-url" class="col-sm-2 control-label">URL</label>
				<div class="col-sm-10">
					<input type="email" class="form-control" id="podcast-{{$id}}-url" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="podcast-{{$id}}-length" class="col-sm-2 control-label">Length</label>
				<div class="col-sm-10">
					<input type="email" class="form-control" id="podcast-{{$id}}-length" placeholder="in bytes">
				</div>
			</div>
		{{/foreach}}

	</div>
</div>