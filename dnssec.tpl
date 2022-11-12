<div class="section">
    <div class="section-header">
        <h2 class="section-title">{$LANG.gandi.dnssec.title}</h2>
        <p class="section-desc">{$desc}</p>
    </div>
    {if $addKey}
        <div class="section-body">
            <p class="section-desc">{$LANG.gandi.dnssec.activation}</p>
            <form method="post" action="clientarea.php?action=domaindetails">
                <input type="hidden" name="domainid" value="{$domainid}" />
                <input type="hidden" name="modop" value="custom" />
                <input type="hidden" name="a" value="Dnssec" />
                <input type="submit" name="addKey" value="{$LANG.gandi.dnssec.enable}"/>
            </form>
        </div>
    {/if}
    {if $rmKey}
            <div class="section-body">
                <p class="section-desc">{$LANG.gandi.dnssec.deactivation}</p>
                <form method="post" action="clientarea.php?action=domaindetails">
                    <input type="hidden" name="domainid" value="{$domainid}" />
                    <input type="hidden" name="modop" value="custom" />
                    <input type="hidden" name="a" value="Dnssec" />
                    <input type="submit" name="rmKey" value="{$LANG.gandi.dnssec.disable}"/>
                </form>
            </div>
    {/if}


</div>