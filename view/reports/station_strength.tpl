{extends 'reports/menu.tpl'}
{block name=content1}
<form class="form-inline">
	<div class="form-group">
		<label for="datefrom">From</label>
		<input id="datefrom" type="text" class="form-control" role="date">
		</div>
		<div class="form-group">
		<label for="dateto">To</label>
		<input id="dateto" type="text" class="form-control" role="date">
		<button type="submit" class="btn btn-primary">Search</button>
		</div>
	</div>
</form>

<div class="table-responsive">
<table class="table table-bordered">
    <colgroup>
        <col class="col-md" />
        <col class="col" />
        <col class="col" />
        <col class="col" />
        <col class="col" />
        <col class="col" />
    </colgroup>
    
    <thead>
        <tr>
            <th>{'Nickname'|@lang}</th>
            <th>{'Officer'|@lang}</th>
            <th>{'Soldier'|@lang}</th>
            <th>{'Employee'|@lang}</th>
            <th>{'FireEngine'|@lang}</th>
            <th>{'Driver'|@lang}</th>
        </tr>
    </thead>
    
    <tbody>
{foreach $table as $item}
        <tr>
            <td>
                <i class="fa fa-user"></i>
                <a href="#">{$item->user->nickname}</a>
            </td>
            <td>{$item->nofficer}</td>
            <td>{$item->nsoldier}</td>
            <td>{$item->nemployee}</td>
            <td>{$item->nfireengine}</td>
            <td>{$item->ndriver}</td>
        </tr>
{/foreach}
    </tbody>
</table>
</div>
<script type="text/javascript">
$('input[role=date]').datepicker({
format: "yy-mm-dd",
todayBtn: true,
language: "zh-CN",
weekStart: 1,
saysOfWeekHighlighted: "0,6",
autoclose: true
}); 
</script>
{/block}
