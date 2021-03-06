{extends 'reports/menu.tpl'}
{block name=content1}

<br/>
{assign var='action' value="{Router->build p1='ReportsController' p2='smallStationStrength'}"}
{include file='reports/datepicker.tpl' action=$action}
<br/>
<div class="table-responsive">
<table class="table table-bordered">
    <colgroup>
        <col class="col-md" />
        <col class="col" />
        <col class="col" />
        <col class="col" />
        <col class="col" />
        <col class="col" />
        <col class="col" />
    </colgroup>
    
    <thead>
        <tr>
            <th>{'Nickname'|@lang}</th>
            <th>{'OnDuty'|@lang}</th>
            <th>{'Driver'|@lang}</th>
            <th>{'Vehicle'|@lang}</th>
            <th>{'VehicleInUse'|@lang}</th>
            <th>{'EquipmentCondition'|@lang}</th>
            <!--<th>{'EquipmentCondition'|@lang}</th>-->
        </tr>
    </thead>
    
    <tbody>
{foreach $table as $item}
{if $item['reported']}
        <tr>
            <td rowspan="2" valign="middle">
                <i class="fa fa-user"></i>
                <a href="{$item['uid']}">{$item['nickname']}</a>
            </td>
            <td>{$item['onduty']}</td>
            <td>{$item['driver']}</td>
            <td>{$item['vehicle']}</td>
            <td>{$item['vehicle_inuse']}</td>
            <td>{$item['equipment_condition']}</td>
        <tr>
			<td offset=1 colspan=5>{'VehicleCondition'|@lang} {$item['vehicle_condition']}</td>
		</tr>
{else}
		<tr>
            <td>
                <i class="fa fa-user"></i>
                <a href="{$item['uid']}">{$item['nickname']}</a>
            </td>
			<td colspan=6 align="center">{'NotSubmit'|@lang}</td>
        </tr>
{/if}
		
{/foreach}
		<tr>

			<td><i class="fa fa-plus"></i>{'TotalRecord'|@lang}</td>
			<td>{$total['onduty']}</td>
            <td>{$total['driver']}</td>
            <td>{$total['vehicle_inuse']}</td>
			<td colspan=2></td>
		</tr>
    </tbody>
</table>
</div>
<script type="text/javascript">
$(document).ready(function(){
        $('input[role=date]').datepicker({
            format: "yyyy-mm-dd",
            language: "zh-CN",
            weekStart: 1,
            saysOfWeekHighlighted: "0,6",
            autoclose: true,
            todayHighlight: true
        });
    }   
);
</script>
{/block}
