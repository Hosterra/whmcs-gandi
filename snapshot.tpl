<div class="section">
    {if $list}
    <div class="section-header">
        <h2 class="section-title">{$LANG.gandi.snapshot.name}</h2>
        <p class="section-desc">{$LANG.gandi.snapshot.desc}</p>
    </div>
    {if $error}
        {include file="$template/includes/alert.tpl" type="error" msg=$error}
    {elseif $success}
        {include file="$template/includes/alert.tpl" type="success" msg=$success}
    {/if}
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.main-content').addClass('status-icons-enabled');
        });
    </script>
    {include file="$template/includes/tablelist.tpl" tableName="SnapshotsList" noSortColumns="0" startOrderCol="2" startOrderSort="desc" filterColumn="3"}
    <script type="text/javascript">
        jQuery(document).ready(function () {
            var table = jQuery('#tableSnapshotsList').removeClass('hidden').DataTable();
            {if $orderby == 'name'}
            table.order(1, '{$sort}');
            {elseif $orderby == 'date'}
            table.order(2, '{$sort}');
            {elseif $orderby == 'status'}
            table.order(3, '{$sort}');
            {/if}
            table.draw();
            jQuery('.table-container').removeClass('loading');
            jQuery('#tableLoading').addClass('hidden');
            {literal}
            setTimeout(function () {
                checkAll()
            }, 500);
            {/literal}
        });
    </script>
    <form id="snapshotForm" method="post" action="clientarea.php?action=bulksnapshot">
        <input id="bulkaction" name="update" type="hidden"/>
        <div class="table-container loading clearfix">
            <div class="table-top">
                <div class="d-flex" style="flex-direction: row;flex-wrap: nowrap;">
                    <label>{$LANG.clientareahostingaddonsview}</label>
                    <div class="dropdown view-filter-btns {if $RSThemes.addonSettings.show_status_icon == 'displayed'}iconsEnabled{/if}">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            {if $RSThemes.addonSettings.show_status_icon == 'displayed'}
                                {if file_exists("templates/$template/assets/img/status-icons/status-all.tpl")}
                                    <span class="status-icon status-status-all" style="font-size: 0;">
                                                {include file="$template/assets/img/status-icons/status-all.tpl"}
                                            </span>
                                {/if}
                            {else}
                                <span class="status hidden"></span>
                            {/if}
                            <span class="filter-name">{$LANG.gandi.snapshot.alltriggers}</span>
                            <i class="ls ls-caret"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#">
                                    <span data-value="all">
                                        {if $RSThemes.addonSettings.show_status_icon == 'displayed'}
                                            {if file_exists("templates/$template/assets/img/status-icons/status-all.tpl")}
                                                <span class="status-icon status-status-all">
                                                    {include file="$template/assets/img/status-icons/status-all.tpl"}
                                                </span>
                                            {/if}

                                            <span class="filter-name">{$LANG.gandi.snapshot.alltriggers}</span>
                                        {else}
                                            {$LANG.gandi.snapshot.alltriggers}
                                        {/if}
                                    </span>
                                </a>
                            </li>
                            {foreach key=num item=status from=$SnapshotsStatuses}
                                {if $RSThemes.addonSettings.show_status_icon == 'displayed'}
                                    <li>
                                        <a href="#">
                                                <span class="status status-{$status.statusClass} {if $RSThemes.addonSettings.show_status_icon == 'displayed'}dot-hidden{/if}"
                                                      data-value="{$status.statustext}"
                                                      data-status-class="{$status.statusClass}">
                                                    {if $RSThemes.addonSettings.show_status_icon == 'displayed'}
                                                        {if file_exists("templates/$template/assets/img/status-icons/{$status.statusClass}.tpl")}
                                                            <span class="status-icon status-{$status.statusClass}">
                                                                {include file="$template/assets/img/status-icons/{$status.statusClass}.tpl"}
                                                            </span>

{else}

                                                            <span class="status-icon status-{$status.statusClass}">
                                                                {include file="$template/assets/img/status-icons/default.tpl"}
                                                            </span>
                                                        {/if}
                                                    {/if}
                                                    <span class="filter-name">
                                                        {$status.statustext}
                                                    </span>
                                                </span>
                                        </a>
                                    </li>
                                {else}
                                    <li><a href="#"><span class="status status-{$status.statusClass}"
                                                          data-value="{$status.statustext}"
                                                          data-status-class="{$status.statusClass}">{$status.statustext}</span></a>
                                    </li>
                                {/if}
                            {/foreach}

                            {*$ALLST=['accepted','awaiting-reply','customer-reply','fraud','open','pending','rescue','suspended','active-server','cancelled','default','grace','paid','rebuild','running','terminated','active','closed','delivered','inprogress','payment-pending','redemption','shutoff','transferred-away','answered','collections','error','manual','pending-registration','refunded','status-all','unpaid','auto','completed','expired','onhold','pending-transfer','rejected','stopped']}
                            {foreach key=num item=status from=$ALLST}
                                <li>
                                    <a href="#">
                                                <span class="status status-{$status} {if $RSThemes.addonSettings.show_status_icon == 'displayed'}dot-hidden{/if}" data-value="{$status}" data-status-class="{$status}">
                                                    {if file_exists("templates/$template/assets/img/status-icons/{$status}.tpl")}
                                                        <span class="status-icon status-{$status}">
                                                                {include file="$template/assets/img/status-icons/{$status}.tpl"}
                                                            </span>
                                                        {else}
                                                            <span class="status-icon status-{$status}">
                                                                {include file="$template/assets/img/status-icons/default.tpl"}
                                                            </span>
                                                    {/if}
                                                    <span class="filter-name">
                                                        {$status}
                                                    </span>
                                                </span>
                                    </a>
                                </li>
                            {/foreach*}

                        </ul>
                    </div>
                </div>
            </div>
            <table id="tableSnapshotsList" class="table table-list">
                <thead>
                <tr>
                    <th class="cell-checkbox" data-priority="1">
                        <button type="button" class="btn-table-collapse"></button>
                        <input id="selectAll" class="icheck-control" type="checkbox" name="domids[]">
                    </th>
                    <th data-priority="1"><span><span>{$LANG.gandi.snapshot.snapname}</span><span
                                    class="sorting-arrows"></span></span></th>
                    <th data-priority="4"><span><span>{$LANG.gandi.snapshot.snapdate}</span><span
                                    class="sorting-arrows"></span></span></th>
                    <th data-priority="3"><span><span>{$LANG.gandi.snapshot.snaptrigger}</span><span
                                    class="sorting-arrows"></span></span></th>
                </tr>
                </thead>
                <tbody>
                {foreach key=num item=snapshot from=$snapshots}
                    <tr data-url="clientarea.php?action=domaindetails&domainid={$domainid}&modop=custom&a=Snapshot&snapid={$snapshot.id}">
                        <td class="cell-checkbox">
                            <button type="button" class="btn-table-collapse">aaa</button>
                            <input type="checkbox" name="domids[]" class="domids stopEventBubble icheck-control"
                                   value="{$snapshot.id}"/>
                        </td>
                        <td>{$snapshot.name}</td>
                        <td data-sort="{$snapshot.date}">{$snapshot.date}</td>
                        <td>
                            {if $RSThemes.addonSettings.show_status_icon == 'displayed'}
                                <span class="status status-{$snapshot.statusClass} {if $RSThemes.addonSettings.show_status_icon == 'displayed'}dot-hidden{/if}">
                                            {if $RSThemes.addonSettings.show_status_icon == 'displayed'}
                                                {if file_exists("templates/$template/assets/img/status-icons/{$snapshot.statusClass}.tpl")}
                                                    <span class="status-icon">
                                                        {include file="$template/assets/img/status-icons/{$snapshot.statusClass}.tpl"}
                                                    </span>

{else}

                                                    <span class="status-icon">
                                                        {include file="$template/assets/img/status-icons/default.tpl"}
                                                    </span>
                                                {/if}
                                            {/if}
                                    {$snapshot.statustext}
                                        </span>
                            {else}
                                <span class="status status-{$snapshot.statusClass}">{$snapshot.statustext}</span>
                            {/if}
                        </td>
                    </tr>
                {/foreach}

                </tbody>
            </table>
            <div class="loader loader-table" id="tableLoading">
                {include file="$template/includes/common/loader.tpl"}
            </div>
        </div>
        <div id="bottom-action-anchor" class="bottom-action-anchor"></div>

        <div class="bottom-action-sticky hidden" data-fixed-actions href="#bottom-action-anchor">
            <div class="container">
                <div class="sticky-content">
                    <div class="badge badge-circle-lg" id="domainSelectedCounter">0</div>
                    <span class="m-h-1x">{$LANG.gandi.snapshot.selected}</span>
                </div>
                <div class="sticky-actions">
                    <div class="dropdown d-xl-none dropup">
                        <button type="button" class="btn btn-link dropdown-toggle drop-up" data-toggle="dropdown">
                            {$LANG.withselected} <i class="ls ls-caret"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li><a href="#" id="deleteSnapshot" class="setBulkAction"><i
                                            class="fas fa-fw fa-trash-alt"></i>{$LANG.gandi.snapshot.delete}</a></li>
                        </ul>
                    </div>
                    <a href="#" id="deleteSnapshot" class="setBulkAction btn btn-link d-none d-xl-block">
                        <i class="fas fa-fw fa-trash-alt" style="margin-right: 0!important"></i>
                        <span class="btn-text">{$LANG.gandi.snapshot.delete}</span>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="section" style="padding-top: 40px!important;">
    <div class="section-header">
        <h2 class="section-title">{$LANG.gandi.snapshot.new}</h2>
        <p class="section-desc">{$LANG.gandi.snapshot.newdesc}</p>
    </div>
    <form class="form-horizontal" method="post"
          action="clientarea.php?action=domaindetails&domainid={$domainid}&modop=custom&a=Snapshot">
        <input type="hidden" name="sub" value="addSnapshot"/>
        <input type="hidden" name="domainid" value="{$domainid}"/>
        <input type="text" name="snapid" value="" size="20" class="form-control"/>
        <div class="form-actions">
            <input type="submit" value="{$LANG.gandi.snapshot.create}" class="btn btn-primary need-load"/>
        </div>

    </form>

    {else}

    <div class="section-body">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{$snapshot.name}</h3></div>
            <ul class="list-info list-info-50 list-info-bordered">
                <li><span class="list-info-title">{$LANG.gandi.snapshot.snaptrigger}</span><span class="list-info-text">
                        {if $RSThemes.addonSettings.show_status_icon == 'displayed'}
                            <span class="status status-{$snapshot.status} {if $RSThemes.addonSettings.show_status_icon == 'displayed'}dot-hidden{/if}">
                {if $RSThemes.addonSettings.show_status_icon == 'displayed'}
                    {if file_exists("templates/$template/assets/img/status-icons/{$snapshot.status}.tpl")}
                        <span class="status-icon">
                            {include file="$template/assets/img/status-icons/{$snapshot.status}.tpl"}
                        </span>

{else}

                        <span class="status-icon">
                            {include file="$template/assets/img/status-icons/default.tpl"}
                        </span>
                    {/if}
                {/if}
                                {$snapshot.statustext}
            </span>

{else}

                            <span class="status status-{$snapshot.status}">{$snapshot.statustext}</span>
                        {/if}
                        <span></span></span></li>
                <li><span class="list-info-title">{$LANG.gandi.snapshot.snapdate}</span><span
                            class="list-info-text"> {$snapshot.date}<span></span></span></li>
            </ul>
        </div>


        <div class="table-container">
            <table class="table m-b-0">
                <thead>
                <tr>
                    <th>{$LANG.domaindnshostname}</th>
                    <th>{$LANG.domaindnsrecordtype}</th>
                    <th>{$LANG.domaindnsaddress}</th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$snapshot.data item=dnsrecord}
                    <tr>
                        <td>{$dnsrecord.rrset_name}</td>
                        <td>{$dnsrecord.rrset_type}</td>
                        <td>{$dnsrecord.rrset_values.0}</td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>


    </div>
    <div class="section-body" style="padding-top: 40px!important;">
    <form style="float:left;" class="form-horizontal" method="post" action="clientarea.php?action=domaindetails&domainid={$domainid}&modop=custom&a=Snapshot">
        <input type="hidden" name="sub" value="restoreSnapshot"/>
        <input type="hidden" name="domainid" value="{$domainid}"/>
        <input type="hidden" name="snapid" value="{$snapshot.id}"/>
        <input type="submit" value="{$LANG.gandi.snapshot.restore}" class="btn btn-primary need-confirm-then-load"/>
    </form>
    <form style="float:right;" class="form-horizontal" method="post" action="clientarea.php?action=domaindetails&domainid={$domainid}&modop=custom&a=Snapshot">
        <input type="hidden" name="sub" value="deleteSnapshot"/>
        <input type="hidden" name="domainid" value="{$domainid}"/>
        <input type="hidden" name="snapid" value="{$snapshot.id}"/>
        <input type="submit" value="{$LANG.gandi.snapshot.delete}" class="btn btn-primary need-delete-confirm-then-load">
    </form>
        </div>

    {/if}


    <div class="modal fade" id="modalDeleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{$LANG.paymentMethods.areYouSure}</h4>
                </div>
                <div class="modal-body">
                    <p>{$LANG.paymentMethods.deletePaymentMethodConfirm}</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">{$LANG.yes}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$LANG.no}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal whmcs-modal fade" id="modalSpinner" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="loader-txt">
                        <p>{$LANG.gandi.spinnertmessage}</p>
                    </div>
                    <div class="loader">
                        <div class="spinner" style="justify-content: center !important;">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                            <div class="rect4"></div>
                            <div class="rect5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $(".need-load").on("click", function (e) {
                    $("#modalSpinner").modal({
                        backdrop: "static",
                        keyboard: false,
                        show: true
                    });
                });
                $(".need-delete-confirm-then-load").on("click", function (e) {
                    e.preventDefault();
                    if ( $("#modalDeleteConfirmation").modal('show') ) {
                        $("#modalSpinner").modal({
                            backdrop: "static",
                            keyboard: false,
                            show: true
                        });
                    }

                });
            });
        </script>
    </div>
</div>