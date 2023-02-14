<div class="section">
    <div class="section-header">
        <h2 class="section-title">{$LANG.gandi.dnssec.title}</h2>
        <p class="section-desc">{$desc}</p>
    </div>
    {if $addKey}
        <div class="section-body">
            <p class="section-desc">{$LANG.gandi.dnssec.activation}</p>
            <form method="post" action="clientarea.php?action=domaindetails" id="frmDNSSECToggle">
                <input type="hidden" name="domainid" value="{$domainid}" />
                <input type="hidden" name="modop" value="custom" />
                <input type="hidden" name="a" value="Dnssec" />
                <button href="#" type="submit" name="addKey" value="{$LANG.gandi.dnssec.enable}" class="btn btn-primary need-load">{$LANG.gandi.dnssec.enable}</button>
            </form>
        </div>
    {/if}
    {if $rmKey}
        <div class="section-body">
            {foreach from=$keys item=key}
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">{$LANG.gandi.dnssec.key} #{$key.id}</h3></div>
                    <ul class="list-info list-info-50 list-info-bordered">
                        <li><span class="list-info-title">{$key.type.name}</span><span class="list-info-text"><span>{$key.type.value}</span></span></li>
                        <li><span class="list-info-title">{$key.algorithm.name}</span><span class="list-info-text"><span>{$key.algorithm.value}</span></span></li>
                        <li><span class="list-info-title">{$key.digest.name}</span><span class="list-info-text"><span><code>{$key.digest.value}</code></span></span></li>
                        <li><span class="list-info-title">{$key.public.name}</span><span class="list-info-text"><span><code>{$key.public.value}</code></span></span></li>
                    </ul>
                </div>
            {/foreach}
            <p class="section-desc">{$LANG.gandi.dnssec.deactivation}</p>
            <form method="post" action="clientarea.php?action=domaindetails" id="frmDNSSECToggle">
                <input type="hidden" name="domainid" value="{$domainid}" />
                <input type="hidden" name="modop" value="custom" />
                <input type="hidden" name="a" value="Dnssec" />
                <button href="#" type="submit" name="rmKey" value="{$LANG.gandi.dnssec.disable}" class="btn btn-primary need-load">{$LANG.gandi.dnssec.disable}</button>
            </form>
        </div>
    {/if}
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
            $(document).ready(function() {
                $(".need-load").on("click", function(e) {
                    $("#modalSpinner").modal({
                        backdrop: "static",
                        keyboard: false,
                        show: true
                    });
                    $('#modalSpinner').on('shown.bs.modal', function (e) {
                        $('#frmDNSSECToggle').submit();
                    });
                });
            });
        </script>
    </div>
</div>